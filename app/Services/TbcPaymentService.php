<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TbcPaymentService
{
    private string $channel;

    // 'shop' uses TBC_PAY_SHOP_* credentials, 'donation' uses TBC_PAY_DONATION_*
    public function __construct(string $channel = 'shop')
    {
        $this->channel = $channel;
    }

    private function baseUrl(): string
    {
        return rtrim(config('services.tbc_pay.base_url', 'https://api.tbcbank.ge/v1/tpay'), '/');
    }

    private function clientId(): string
    {
        return config("services.tbc_pay.{$this->channel}.client_id", '');
    }

    private function clientSecret(): string
    {
        return config("services.tbc_pay.{$this->channel}.client_secret", '');
    }

    // Cache token per channel; TBC tokens typically last 5 min, we refresh at 4.5 min
    private function getAccessToken(): string
    {
        $cacheKey = "tbc_pay_token_{$this->channel}";

        return Cache::remember($cacheKey, 270, function () {
            $response = Http::timeout(10)->asForm()->post($this->baseUrl() . '/access-token', [
                'client_id'     => $this->clientId(),
                'client_secret' => $this->clientSecret(),
            ]);

            if (!$response->successful()) {
                Log::error("TBC Pay [{$this->channel}] token error", ['body' => $response->body()]);
                throw new \RuntimeException("TBC Pay [{$this->channel}]: failed to obtain access token");
            }

            return $response->json('access_token');
        });
    }

    public function createOrder(array $params): array
    {
        $token = $this->getAccessToken();

        $response = Http::timeout(15)
            ->withToken($token)
            ->post($this->baseUrl() . '/orders', $params);

        if (!$response->successful()) {
            Log::error("TBC Pay [{$this->channel}] create order error", ['body' => $response->body()]);
            throw new \RuntimeException("TBC Pay [{$this->channel}]: failed to create order");
        }

        return $response->json();
    }

    public function getOrder(string $payId): array
    {
        $token = $this->getAccessToken();

        $response = Http::timeout(10)
            ->withToken($token)
            ->get($this->baseUrl() . '/orders/' . $payId);

        if (!$response->successful()) {
            Log::error("TBC Pay [{$this->channel}] get order error", ['payId' => $payId, 'body' => $response->body()]);
            throw new \RuntimeException("TBC Pay [{$this->channel}]: failed to get order");
        }

        return $response->json();
    }

    public function cancelOrder(string $payId): bool
    {
        $token = $this->getAccessToken();

        $response = Http::timeout(10)
            ->withToken($token)
            ->delete($this->baseUrl() . '/orders/' . $payId);

        return $response->successful();
    }

    // Extract the browser redirect URL from TBC's "links" array
    public static function extractPaymentUrl(array $tbcResponse): ?string
    {
        foreach ($tbcResponse['links'] ?? [] as $link) {
            if (($link['method'] ?? '') === 'REDIRECT') {
                return $link['uri'] ?? null;
            }
        }
        return null;
    }

    // Map TBC status to a local semantic status
    public static function isSucceeded(string $tbcStatus): bool
    {
        return $tbcStatus === 'SUCCEEDED';
    }

    public static function isFailed(string $tbcStatus): bool
    {
        return in_array($tbcStatus, ['FAILED', 'REJECTED', 'EXPIRED', 'CANCELLED'], true);
    }
}
