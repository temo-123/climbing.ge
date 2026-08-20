<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/*
 * Scaffold client for QuickShipper's REST API. Base URL, auth header, and
 * the /rates and /shipments endpoint paths/payloads below are placeholders
 * (assumed API-key-in-header + JSON REST) — none of this has been verified
 * against QuickShipper's real docs yet. Swap in the confirmed shapes once
 * available.
 */
class QuickShipperService
{
    private function baseUrl(): string
    {
        return rtrim(config('services.quickshipper.base_url', ''), '/');
    }

    private function apiKey(): string
    {
        return config('services.quickshipper.api_key') ?? '';
    }

    public function isConfigured(): bool
    {
        return $this->baseUrl() !== '' && $this->apiKey() !== '';
    }

    private function client()
    {
        return Http::timeout(15)
            ->baseUrl($this->baseUrl())
            ->withHeaders([
                'X-Api-Key' => $this->apiKey(),
                'Accept' => 'application/json',
            ]);
    }

    // Quote shipping rates for an origin/destination + parcel
    public function getRates(array $params): array
    {
        $response = $this->client()->post('/rates', $params);

        if (!$response->successful()) {
            Log::error('QuickShipper get rates error', ['body' => $response->body()]);
            throw new \RuntimeException('QuickShipper: failed to fetch rates');
        }

        return $response->json();
    }

    // Create a shipment, returns the provider's shipment id / tracking number / label url
    public function createShipment(array $params): array
    {
        $response = $this->client()->post('/shipments', $params);

        if (!$response->successful()) {
            Log::error('QuickShipper create shipment error', ['body' => $response->body()]);
            throw new \RuntimeException('QuickShipper: failed to create shipment');
        }

        return $response->json();
    }

    public function getShipment(string $shipmentId): array
    {
        $response = $this->client()->get("/shipments/{$shipmentId}");

        if (!$response->successful()) {
            Log::error('QuickShipper get shipment error', ['shipmentId' => $shipmentId, 'body' => $response->body()]);
            throw new \RuntimeException('QuickShipper: failed to fetch shipment');
        }

        return $response->json();
    }
}
