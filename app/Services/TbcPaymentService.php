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
        return config("services.tbc_pay.{$this->channel}.client_id") ?? '';
    }

    private function clientSecret(): string
    {
        return config("services.tbc_pay.{$this->channel}.client_secret") ?? '';
    }

    // Developer-portal app key, sent as the `apikey` header on every TBC request.
    // TBC_PAY_{CHANNEL}_API_KEY, falling back to the shared TBC_PAY_API_KEY
    private function apiKey(): string
    {
        return config("services.tbc_pay.{$this->channel}.api_key") ?: (config('services.tbc_pay.api_key') ?? '');
    }

    // True only when the API key and TBC_PAY_{CHANNEL}_CLIENT_ID / _CLIENT_SECRET are set in .env
    public function isConfigured(): bool
    {
        return $this->apiKey() !== '' && $this->clientId() !== '' && $this->clientSecret() !== '';
    }

    private function http(int $timeout)
    {
        return Http::timeout($timeout)->withHeaders(['apikey' => $this->apiKey()]);
    }

    // Cache token per channel; TBC tokens typically last 5 min, we refresh at 4.5 min
    private function getAccessToken(): string
    {
        $cacheKey = "tbc_pay_token_{$this->channel}";

        return Cache::remember($cacheKey, 270, function () {
            $response = $this->http(10)->asForm()->post($this->baseUrl() . '/access-token', [
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

        $response = $this->http(15)
            ->withToken($token)
            ->post($this->baseUrl() . '/payments', $params);

        if (!$response->successful()) {
            Log::error("TBC Pay [{$this->channel}] create order error", ['body' => $response->body()]);
            throw new \RuntimeException("TBC Pay [{$this->channel}]: failed to create order");
        }

        return $response->json();
    }

    public function getOrder(string $payId): array
    {
        $token = $this->getAccessToken();

        $response = $this->http(10)
            ->withToken($token)
            ->get($this->baseUrl() . '/payments/' . $payId);

        if (!$response->successful()) {
            Log::error("TBC Pay [{$this->channel}] get order error", ['payId' => $payId, 'body' => $response->body()]);
            throw new \RuntimeException("TBC Pay [{$this->channel}]: failed to get order");
        }

        return $response->json();
    }

    public function cancelOrder(string $payId): bool
    {
        $token = $this->getAccessToken();

        $response = $this->http(10)
            ->withToken($token)
            ->post($this->baseUrl() . '/payments/' . $payId . '/cancel');

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

    // Map TBC status to a local semantic status. TBC returns "Succeeded",
    // "Failed", "Expired"… — compared case-insensitively
    public static function isSucceeded(?string $tbcStatus): bool
    {
        return strtoupper((string) $tbcStatus) === 'SUCCEEDED';
    }

    public static function isFailed(?string $tbcStatus): bool
    {
        return in_array(strtoupper((string) $tbcStatus), ['FAILED', 'REJECTED', 'EXPIRED', 'CANCELLED'], true);
    }

    public static function isExpired(?string $tbcStatus): bool
    {
        return strtoupper((string) $tbcStatus) === 'EXPIRED';
    }
}
