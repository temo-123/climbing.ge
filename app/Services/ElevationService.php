<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ElevationService
{
    public function lookup(float $latitude, float $longitude): ?int
    {
        try {
            $response = Http::timeout(5)->get('https://api.open-elevation.com/api/v1/lookup', [
                'locations' => "{$latitude},{$longitude}",
            ]);

            if (!$response->successful()) {
                return null;
            }

            $elevation = $response->json('results.0.elevation');

            return is_numeric($elevation) ? (int) round($elevation) : null;

        } catch (\Exception $e) {
            return null;
        }
    }
}
