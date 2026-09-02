<?php

namespace App\Services;

use App\Models\Shop\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

/*
 * Client for RS.ge's (Georgian Revenue Service) electronic waybill ("ზედნადები") SOAP API.
 *
 * CONFIRMED against RS.ge's live WSDL (services.rs.ge/WayBillService/WayBillService.asmx?WSDL,
 * fetched September 2026): operation names, su/sp auth on every call, save_waybill(su, sp,
 * waybill) → returns a waybill id, send_waybill(su, sp, waybill_id) activates/sends it,
 * get_waybill(su, sp, waybill_id) reads it back.
 *
 * NOT CONFIRMED / reconstructed: the WSDL declares <waybill> as untyped xs:any, so the exact
 * inner field names below are NOT from RS.ge's own schema — they're reconstructed from a
 * third-party open-source client's *response*-side field mapping (dimakura/rs.ge, a Ruby
 * gem), on the assumption that RS.ge's save/get shapes are symmetric (a documented convention
 * for this API, but not something this integration could verify directly). Confirm against a
 * real save_waybill round-trip before trusting this with a real filing.
 *
 * No separate RS.ge sandbox/test host could be found to exist (checked their WSDL and public
 * community docs) — 'services.rs_ge.mode' = 'test' here is therefore a LOCAL dry run (builds
 * and logs the request, never actually calls RS.ge) rather than a real RS.ge test environment.
 * RS.ge also requires the calling server's outbound IP to be allowlisted on their side before
 * any call succeeds at all, independent of whether credentials/fields are correct.
 */
class RsGeWaybillService
{
    // Waybill type ("ზედნადების სახე") — see RS.ge's get_waybill_types for the authoritative list.
    public const TYPE_INNER = 1;         // შიდა გადაზიდვა
    public const TYPE_WITH_TRANSPORT = 2;    // ტრანსპორტირებით
    public const TYPE_WITHOUT_TRANSPORT = 3; // ტრანსპორტირების გარეშე
    public const TYPE_DISTRIBUTION = 4;      // დისტრიბუცია
    public const TYPE_RETURN = 5;            // უკან დაბრუნება

    // Transport type — see RS.ge's get_trans_types for the authoritative list.
    public const TRANSPORT_VEHICLE = 1;

    // Who pays for transport.
    public const TRANSPORT_PAID_BY_BUYER = 1;
    public const TRANSPORT_PAID_BY_SELLER = 2;

    private function serviceUrl(): string
    {
        return config('services.rs_ge.service_url', 'http://services.rs.ge/WayBillService/WayBillService.asmx');
    }

    private function su(): string
    {
        return config('services.rs_ge.su') ?? '';
    }

    private function sp(): string
    {
        return config('services.rs_ge.sp') ?? '';
    }

    public function isConfigured(): bool
    {
        return $this->su() !== '' && $this->sp() !== '';
    }

    public function isTestMode(): bool
    {
        return config('services.rs_ge.mode', 'test') !== 'live';
    }

    // Builds a SOAP 1.1 envelope for the given operation and posts it, returning the parsed
    // <{operation}Result> node. $extraXml is raw XML injected as-is (used for the untyped
    // <waybill> element on save_waybill).
    private function call(string $operation, array $simpleParams = [], string $extraXml = ''): SimpleXMLElement
    {
        $body = '';
        foreach ($simpleParams as $tag => $value) {
            $body .= '<' . $tag . '>' . htmlspecialchars((string) $value, ENT_XML1) . '</' . $tag . '>';
        }
        $body .= $extraXml;

        $envelope = '<?xml version="1.0" encoding="utf-8"?>'
            . '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">'
            . '<soap:Body>'
            . '<' . $operation . ' xmlns="http://tempuri.org/">' . $body . '</' . $operation . '>'
            . '</soap:Body>'
            . '</soap:Envelope>';

        if ($this->isTestMode()) {
            Log::info("RS.ge [TEST MODE] would call {$operation}", ['envelope' => $envelope]);
            throw new \RuntimeException("RS.ge: test mode — {$operation} was not actually sent (services.rs_ge.mode is not 'live')");
        }

        $response = Http::timeout(20)
            ->withHeaders([
                'Content-Type' => 'text/xml; charset=utf-8',
                'SOAPAction' => '"http://tempuri.org/' . $operation . '"',
            ])
            ->withBody($envelope, 'text/xml')
            ->post($this->serviceUrl());

        if (!$response->successful()) {
            Log::error("RS.ge {$operation} error", ['status' => $response->status(), 'body' => $response->body()]);
            throw new \RuntimeException("RS.ge: {$operation} request failed (HTTP {$response->status()})");
        }

        $xml = new SimpleXMLElement($response->body());
        $xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('ns', 'http://tempuri.org/');

        $resultNodes = $xml->xpath("//*[local-name()='{$operation}Result']");
        if (empty($resultNodes)) {
            Log::error("RS.ge {$operation}: no result node in response", ['body' => $response->body()]);
            throw new \RuntimeException("RS.ge: {$operation} returned an unexpected response shape");
        }

        return $resultNodes[0];
    }

    // Builds the <waybill> XML for an Order — see the class docblock for how confident each
    // field is. Regular orders pull the buyer from User + User_adreses; is_custom orders use
    // their own CustomOrderAddress instead.
    private function buildWaybillXml(Order $order): string
    {
        $sellerTin = config('services.rs_ge.seller_tin', '');
        $startAddress = config('services.rs_ge.start_address', '');

        if ($order->is_custom) {
            $address = $order->buyerAddress()->first();
            $buyerName = trim(($address->name ?? '') . ' ' . ($address->surname ?? ''));
            $buyerPhone = $address->phone ?? '';
            $endAddress = trim(($address->city ?? '') . ' ' . ($address->address ?? ''));
        } else {
            $buyer = $order->user()->first();
            $adres = $order->userAdres()->first();
            $buyerName = trim(($buyer->name ?? '') . ' ' . ($buyer->surname ?? ''));
            $buyerPhone = $buyer->phone_number ?? '';
            $endAddress = trim(($adres->city ?? '') . ' ' . ($adres->strit ?? '') . ' ' . ($adres->number ?? ''));
        }

        $goods = '';
        foreach ($order->orderProducts()->with(['product.us_product', 'option'])->get() as $line) {
            $name = optional(optional($line->product)->us_product)->title ?? optional($line->option)->name ?? ('Product #' . $line->product_id);
            $price = optional($line->option)->price ?? 0;
            $barcode = optional($line->option)->barcode ?? '';
            $quantity = $line->quantity ?? 1;
            $amount = $price * $quantity;

            $goods .= '<goods>'
                . '<w_name>' . htmlspecialchars($name, ENT_XML1) . '</w_name>'
                . '<bar_code>' . htmlspecialchars((string) $barcode, ENT_XML1) . '</bar_code>'
                . '<quantity>' . htmlspecialchars((string) $quantity, ENT_XML1) . '</quantity>'
                . '<price>' . htmlspecialchars((string) $price, ENT_XML1) . '</price>'
                . '<amount>' . htmlspecialchars((string) $amount, ENT_XML1) . '</amount>'
                . '</goods>';
        }

        $type = self::TYPE_WITHOUT_TRANSPORT;

        return '<waybill>'
            . '<type>' . $type . '</type>'
            . '<seller_tin>' . htmlspecialchars($sellerTin, ENT_XML1) . '</seller_tin>'
            . '<buyer_name>' . htmlspecialchars($buyerName, ENT_XML1) . '</buyer_name>'
            . '<chek_buyer_tin>0</chek_buyer_tin>'
            . '<start_address>' . htmlspecialchars($startAddress, ENT_XML1) . '</start_address>'
            . '<end_address>' . htmlspecialchars($endAddress, ENT_XML1) . '</end_address>'
            . '<comment>' . htmlspecialchars('climbing.ge shop order #' . $order->id . ' — ' . $buyerPhone, ENT_XML1) . '</comment>'
            . '<goods_list>' . $goods . '</goods_list>'
            . '</waybill>';
    }

    // save_waybill — creates the waybill in RS.ge (SAVED/draft status). Returns the waybill id.
    public function saveWaybill(Order $order): int
    {
        $result = $this->call('save_waybill', [
            'su' => $this->su(),
            'sp' => $this->sp(),
        ], $this->buildWaybillXml($order));

        $id = (int) ($result->xpath(".//*[local-name()='id']")[0] ?? 0);
        if ($id === 0) {
            Log::error('RS.ge save_waybill: no id in response', ['xml' => $result->asXML()]);
            throw new \RuntimeException('RS.ge: save_waybill did not return a waybill id');
        }

        return $id;
    }

    // send_waybill — activates/sends a previously-saved waybill.
    public function sendWaybill(int $waybillId): string
    {
        $result = $this->call('send_waybill', [
            'su' => $this->su(),
            'sp' => $this->sp(),
            'waybill_id' => $waybillId,
        ]);

        return (string) $result;
    }

    // get_waybill — reads the current state of a waybill back from RS.ge.
    public function getWaybill(int $waybillId): SimpleXMLElement
    {
        return $this->call('get_waybill', [
            'su' => $this->su(),
            'sp' => $this->sp(),
            'waybill_id' => $waybillId,
        ]);
    }

    // Dictionary lookups — use these to confirm the real codes for unit/transport/waybill
    // type before relying on the hardcoded constants above.
    public function getWaybillUnits(): SimpleXMLElement
    {
        return $this->call('get_waybill_units', ['su' => $this->su(), 'sp' => $this->sp()]);
    }

    public function getWaybillTypes(): SimpleXMLElement
    {
        return $this->call('get_waybill_types', ['su' => $this->su(), 'sp' => $this->sp()]);
    }

    public function getTransTypes(): SimpleXMLElement
    {
        return $this->call('get_trans_types', ['su' => $this->su(), 'sp' => $this->sp()]);
    }

    // Saves and immediately sends the waybill for an order, persisting the result onto it —
    // the single shared path for both the automatic "Ready to ship" trigger and any manual
    // admin retry, so the two can't drift apart.
    public function sendWaybillForOrderAndPersist(Order $order): array
    {
        $waybillId = $this->saveWaybill($order);
        $sendResult = $this->sendWaybill($waybillId);

        $order->update([
            'rs_ge_waybill_id' => $waybillId,
            'rs_ge_waybill_status' => 'sent',
            'rs_ge_waybill_sent_at' => now(),
        ]);

        return ['waybill_id' => $waybillId, 'send_result' => $sendResult];
    }
}
