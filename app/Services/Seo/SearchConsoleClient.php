<?php

namespace App\Services\Seo;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Minimal Google Search Console API client (read-only), no SDK needed:
 * service-account JWT (RS256, openssl) → OAuth access token → searchAnalytics.query.
 *
 * Credentials: the service account's JSON key at config('services.search_console.credentials').
 * The service account's e-mail must be added as a user (Restricted is enough) on each
 * Search Console property.
 */
class SearchConsoleClient
{
    private const SCOPE = 'https://www.googleapis.com/auth/webmasters.readonly';
    private const API   = 'https://searchconsole.googleapis.com/webmasters/v3';
    private const PAGE  = 25000;   // API maximum per request

    private array $key;

    public function __construct(?string $credentialsPath = null)
    {
        $path = $credentialsPath ?? config('services.search_console.credentials');
        if (! $path || ! is_readable($path)) {
            throw new RuntimeException("Search Console credentials not found at {$path}");
        }
        $key = json_decode((string) file_get_contents($path), true);
        if (! is_array($key) || empty($key['client_email']) || empty($key['private_key'])) {
            throw new RuntimeException('Search Console credentials file is not a service-account JSON key');
        }
        $this->key = $key;
    }

    public static function configured(): bool
    {
        $path = config('services.search_console.credentials');
        return $path && is_readable($path) && ! empty((array) config('services.search_console.properties', []));
    }

    public function serviceAccountEmail(): string
    {
        return $this->key['client_email'];
    }

    /** Properties this service account can read (to check the setup). */
    public function sites(): array
    {
        $res = Http::withToken($this->token())->acceptJson()->get(self::API . '/sites');
        $this->fail_unless_ok($res, 'sites.list');
        return collect($res->json('siteEntry', []))->pluck('permissionLevel', 'siteUrl')->all();
    }

    /**
     * All rows for one query, paging through the API's 25 000-row limit.
     * $body: startDate, endDate (Y-m-d), dimensions, optional dimensionFilterGroups.
     * Each row: ['keys' => [...], 'clicks', 'impressions', 'ctr', 'position'].
     */
    public function query(string $property, array $body, int $maxRows = 200000): array
    {
        $url  = self::API . '/sites/' . rawurlencode($property) . '/searchAnalytics/query';
        $rows = [];
        for ($start = 0; $start < $maxRows; $start += self::PAGE) {
            $res = Http::withToken($this->token())->acceptJson()->timeout(120)->retry(2, 2000, throw: false)
                ->post($url, $body + ['rowLimit' => self::PAGE, 'startRow' => $start, 'dataState' => 'final']);
            $this->fail_unless_ok($res, 'searchAnalytics.query');
            $page = $res->json('rows', []);
            array_push($rows, ...$page);
            if (count($page) < self::PAGE) {
                break;
            }
        }
        return $rows;
    }

    private function token(): string
    {
        return Cache::remember('search_console:token:' . sha1($this->key['client_email']), 50 * 60, function () {
            $now = time();
            $tokenUri = $this->key['token_uri'] ?? 'https://oauth2.googleapis.com/token';
            $b64 = fn ($v) => rtrim(strtr(base64_encode(is_string($v) ? $v : json_encode($v)), '+/', '-_'), '=');
            $unsigned = $b64(['alg' => 'RS256', 'typ' => 'JWT']) . '.' . $b64([
                'iss'   => $this->key['client_email'],
                'scope' => self::SCOPE,
                'aud'   => $tokenUri,
                'iat'   => $now,
                'exp'   => $now + 3600,
            ]);
            if (! openssl_sign($unsigned, $signature, $this->key['private_key'], OPENSSL_ALGO_SHA256)) {
                throw new RuntimeException('Could not sign the Search Console token (bad private key?)');
            }
            $res = Http::asForm()->post($tokenUri, [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $unsigned . '.' . $b64($signature),
            ]);
            $this->fail_unless_ok($res, 'token');
            return (string) $res->json('access_token');
        });
    }

    private function fail_unless_ok($res, string $what): void
    {
        if (! $res->successful()) {
            $msg = $res->json('error.message') ?? $res->json('error_description') ?? $res->body();
            throw new RuntimeException("Search Console {$what} failed ({$res->status()}): " . mb_substr((string) $msg, 0, 300));
        }
    }
}
