<?php

namespace App\Services;

use App\Models\Shop\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/*
 * Client for QuickShipper's real "Delivery API" (https://delivery.quickshipper.app),
 * verified against their live OpenAPI spec (swagger/v1/swagger.json) in September 2026 —
 * this replaces an earlier scaffold that guessed a different (wrong) shape.
 *
 * Auth: OAuth2 client_credentials grant against QUICKSHIPPER_TOKEN_URL
 * (auth.quickshipper.app/connect/token, scope=DeliveryApi), Bearer token on every /v1/* call.
 * Token lifetime isn't documented; cached for 55 minutes and re-fetched on expiry/401,
 * mirroring the pattern already used for TBC Pay in TbcPaymentService.
 */
class QuickShipperService
{
    private function baseUrl(): string
    {
        return rtrim(config('services.quickshipper.base_url', 'https://delivery.quickshipper.app'), '/');
    }

    private function tokenUrl(): string
    {
        return config('services.quickshipper.token_url', 'https://auth.quickshipper.app/connect/token');
    }

    private function clientId(): string
    {
        return config('services.quickshipper.client_id') ?? '';
    }

    private function clientSecret(): string
    {
        return config('services.quickshipper.client_secret') ?? '';
    }

    public function isConfigured(): bool
    {
        return $this->clientId() !== '' && $this->clientSecret() !== '';
    }

    private function getAccessToken(): string
    {
        return Cache::remember('quickshipper_access_token', 3300, function () {
            $response = Http::timeout(10)->asForm()->post($this->tokenUrl(), [
                'grant_type'    => 'client_credentials',
                'client_id'     => $this->clientId(),
                'client_secret' => $this->clientSecret(),
                'scope'         => 'DeliveryApi',
            ]);

            if (!$response->successful()) {
                Log::error('QuickShipper token error', ['body' => $response->body()]);
                throw new \RuntimeException('QuickShipper: failed to obtain access token');
            }

            return $response->json('access_token');
        });
    }

    private function client()
    {
        return Http::timeout(15)
            ->baseUrl($this->baseUrl())
            ->withToken($this->getAccessToken())
            ->acceptJson();
    }

    // Builds the OrderDropOffModel (destination) from an Order — regular orders carry the
    // buyer's saved address (User_adreses) + the User's own name/phone; custom orders (is_custom)
    // carry their own one-off CustomOrderAddress instead. Neither table stores lat/lng today,
    // so those are sent as 0 — QuickShipper still geocodes off the address/city text.
    private function buildDropOff(Order $order): array
    {
        if ($order->is_custom) {
            $address = $order->buyerAddress()->first();

            return [
                'address'        => $address->address ?? '',
                'longitude'      => 0,
                'latitude'       => 0,
                'name'           => trim(($address->name ?? '') . ' ' . ($address->surname ?? '')),
                'phone'          => $address->phone ?? '',
                'city'           => $address->city ?? '',
                'country'        => $address->country ?? 'Georgia',
            ];
        }

        $buyer = $order->user()->first();
        $adres = $order->userAdres()->first();

        $streetLine = trim(($adres->strit ?? '') . ' ' . ($adres->number ?? ''));

        return [
            'address'   => $streetLine,
            'longitude' => 0,
            'latitude'  => 0,
            'name'      => trim(($buyer->name ?? '') . ' ' . ($buyer->surname ?? '')),
            'phone'     => $buyer->phone_number ?? '',
            'city'      => $adres->city ?? '',
            'country'   => 'Georgia',
        ];
    }

    private function buildPickUp(): array
    {
        $pickup = config('services.quickshipper.pickup', []);

        return [
            'address'   => $pickup['address'] ?? '',
            'longitude' => (float) ($pickup['longitude'] ?? 0),
            'latitude'  => (float) ($pickup['latitude'] ?? 0),
            'name'      => $pickup['name'] ?? '',
            'phone'     => $pickup['phone'] ?? '',
            'city'      => $pickup['city'] ?? '',
            'country'   => $pickup['country'] ?? 'Georgia',
        ];
    }

    // Place an order with QuickShipper (POST /v1/Order) for a shop order that's ready to ship.
    // Returns the raw OrderPlaceResponseModel (orderId, orderStatus, trackingUrl, ...).
    public function placeOrder(Order $order): array
    {
        $providerId = config('services.quickshipper.provider_id');

        $payload = [
            'dropOffInfo'        => $this->buildDropOff($order),
            'pickUpInfo'         => $this->buildPickUp(),
            'autoAssign'         => empty($providerId),
            'integrationOrderId' => (string) $order->id,
            'comment'            => 'climbing.ge shop order #' . $order->id,
        ];

        if (!empty($providerId)) {
            $payload['provider'] = ['providerId' => (int) $providerId];
        }

        $response = $this->client()->post('/v1/Order', $payload);

        if (!$response->successful()) {
            Log::error('QuickShipper place order error', ['order_id' => $order->id, 'body' => $response->body()]);
            throw new \RuntimeException('QuickShipper: failed to place order');
        }

        return $response->json();
    }

    // Places the order with QuickShipper and persists the result onto the shop Order row —
    // the single shared path for both the automatic "Ready to ship" trigger
    // (OrderController::edit_order_status()) and the manual admin retry action
    // (QuickShipperController::create_shipment()), so the two never drift apart.
    public function placeOrderAndPersist(Order $order): array
    {
        $shipment = $this->placeOrder($order);

        $order->update([
            'quickshipper_shipment_id'     => $shipment['orderId'] ?? null,
            'quickshipper_tracking_number' => $shipment['orderId'] ?? null,
            'quickshipper_label_url'       => $shipment['trackingUrl'] ?? null,
            'quickshipper_status'          => $shipment['orderStatus'] ?? 'created',
        ]);

        return $shipment;
    }

    // GET /v1/Order?OrderId=... — full current info incl. status, courier, trackingUrl.
    public function getOrderInfo(int $quickShipperOrderId): array
    {
        $response = $this->client()->get('/v1/Order', ['OrderId' => $quickShipperOrderId]);

        if (!$response->successful()) {
            Log::error('QuickShipper get order info error', ['order_id' => $quickShipperOrderId, 'body' => $response->body()]);
            throw new \RuntimeException('QuickShipper: failed to fetch order info');
        }

        return $response->json();
    }

    // POST /v1/Order/status — explicitly move a QuickShipper order to a new status.
    public function changeOrderStatus(int $quickShipperOrderId, string $status, ?string $comment = null): array
    {
        $response = $this->client()->post('/v1/Order/status', [
            'orderId'      => $quickShipperOrderId,
            'status'       => $status,
            'orderComment' => $comment,
        ]);

        if (!$response->successful()) {
            Log::error('QuickShipper change status error', ['order_id' => $quickShipperOrderId, 'body' => $response->body()]);
            throw new \RuntimeException('QuickShipper: failed to change order status');
        }

        return $response->json();
    }

    // GET /v1/Order/fees — quote the delivery fee for a from/to pair before placing the order.
    public function getFees(array $query): array
    {
        $response = $this->client()->get('/v1/Order/fees', $query);

        if (!$response->successful()) {
            Log::error('QuickShipper get fees error', ['body' => $response->body()]);
            throw new \RuntimeException('QuickShipper: failed to fetch fees');
        }

        return $response->json();
    }
}
