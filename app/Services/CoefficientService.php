<?php

namespace App\Services;

use App\Models\Coefficient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Single read path for admin-editable coefficients. Merges the `coefficients`
 * table over the defaults in config/coefficients.php and caches the result,
 * so reading them costs nothing per request. The same map is printed into
 * every SPA page as window.__COEFFICIENTS__ (partials/coefficients.blade.php)
 * — the frontend never has to call an API for it.
 */
class CoefficientService
{
    private const CACHE_KEY = 'coefficients.all';

    // Admin writes through the model clear the cache immediately; the TTL
    // only matters for rows edited directly in the DB (e.g. phpMyAdmin).
    private const CACHE_TTL = 600;

    public static function all(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $values = array_map('floatval', config('coefficients', []));

            // Table may not exist yet on an environment that hasn't migrated —
            // fall back to defaults instead of breaking every page render.
            if (Schema::hasTable('coefficients')) {
                foreach (Coefficient::pluck('value', 'slug') as $slug => $value) {
                    $values[$slug] = (float) $value;
                }
            }

            return $values;
        });
    }

    public static function get(string $slug, float $default = 0): float
    {
        return self::all()[$slug] ?? $default;
    }

    public static function forget(): void
    {
        // With the file cache driver, a cache file written by a different OS
        // user (e.g. artisan/tinker run as a non-web user) can't be deleted by
        // the web server — the edit would then silently show up only after
        // CACHE_TTL. Log it so it isn't a mystery.
        if (!Cache::forget(self::CACHE_KEY) && Cache::has(self::CACHE_KEY)) {
            Log::warning('CoefficientService: could not clear the coefficients cache (check storage/framework/cache ownership); changes apply after ' . self::CACHE_TTL . 's.');
        }
    }

    /**
     * Delivery period ("min-max" business days) for an order/cart, depending
     * on whether it contains a made-to-order product.
     */
    public static function deliveryDays(bool $hasProducedByOrder): string
    {
        $type = $hasProducedByOrder ? 'produced_by_order' : 'online_order';

        return (int) self::get("delivery_{$type}_min_days") . '-' . (int) self::get("delivery_{$type}_max_days");
    }
}
