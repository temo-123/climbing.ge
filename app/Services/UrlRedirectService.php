<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Old content URLs → current ones (301). Table url_redirects: segment, old_slug, new_slug.
 * The whole map is small and cached; record() clears the cache.
 */
class UrlRedirectService
{
    private const CACHE_KEY = 'url_redirects:map';

    /** "segment/old_slug" → new_slug */
    public static function map(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            if (! Schema::hasTable('url_redirects')) {
                return [];
            }
            return DB::table('url_redirects')->get(['segment', 'old_slug', 'new_slug'])
                ->mapWithKeys(fn ($r) => [$r->segment . '/' . $r->old_slug => $r->new_slug])
                ->all();
        });
    }

    public static function find(string $segment, string $slug): ?string
    {
        return self::map()[$segment . '/' . $slug] ?? null;
    }

    /**
     * A slug changed from $old to $new under these URL segments. Keeps the map flat:
     * earlier redirects to $old now go straight to $new (no chains), and a redirect from
     * $new is dropped (renamed back — that URL is live again).
     */
    public static function record(array $segments, ?string $old, ?string $new): void
    {
        if (! $old || ! $new || $old === $new || ! Schema::hasTable('url_redirects')) {
            return;
        }
        $now = now();
        foreach (array_unique($segments) as $segment) {
            DB::table('url_redirects')->where('segment', $segment)->where('new_slug', $old)
                ->update(['new_slug' => $new, 'updated_at' => $now]);
            DB::table('url_redirects')->where('segment', $segment)->where('old_slug', $new)->delete();
            DB::table('url_redirects')->updateOrInsert(
                ['segment' => $segment, 'old_slug' => $old],
                ['new_slug' => $new, 'updated_at' => $now, 'created_at' => $now]
            );
        }
        Cache::forget(self::CACHE_KEY);
    }
}
