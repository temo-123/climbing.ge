<?php

namespace App\Services\Seo;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Pulls Search Console data into search_console_pages (daily, per URL) and
 * search_console_queries (last 28 days, per URL + query). Run nightly by
 * `php artisan app:sync-search-console`; `--days=480` back-fills (Google keeps 16 months).
 *
 * Search Console data is final ~2–3 days after the day, so every run re-reads the last
 * few days; rows are upserted per (date, url).
 */
class SearchConsoleSync
{
    public const STATUS_KEY = 'search_console:last_sync';

    public function __construct(private SearchConsoleClient $client, private ContentResolver $resolver)
    {
    }

    public function run(int $days = 5): array
    {
        $end   = Carbon::today()->subDay();
        $start = $end->copy()->subDays(max(1, $days) - 1);
        $stats = ['pages' => 0, 'queries' => 0, 'properties' => []];
        $properties = array_values((array) config('services.search_console.properties', []));

        foreach ($properties as $i => $property) {
            $pages = $this->client->query($property, [
                'startDate'  => $start->toDateString(),
                'endDate'    => $end->toDateString(),
                'dimensions' => ['date', 'page'],
            ]);
            $stats['pages'] += $this->storePages($pages);

            // queries: last 28 days ending at the newest day that has data
            $latest = DB::table('search_console_pages')->max('date') ?: $end->toDateString();
            $qEnd   = Carbon::parse($latest);
            $queries = $this->client->query($property, [
                'startDate'  => $qEnd->copy()->subDays(27)->toDateString(),
                'endDate'    => $qEnd->toDateString(),
                'dimensions' => ['page', 'query'],
            ], 100000);
            $stats['queries'] += $this->storeQueries($queries, $i === 0);
            $stats['properties'][] = $property;
        }

        $status = ['ok' => true, 'at' => now()->toIso8601String(), 'from' => $start->toDateString(), 'to' => $end->toDateString()] + $stats;
        Cache::forever(self::STATUS_KEY, $status);
        Cache::forget('seo_dashboard:content_quality');
        return $status;
    }

    public static function recordFailure(\Throwable $e): void
    {
        Cache::forever(self::STATUS_KEY, ['ok' => false, 'at' => now()->toIso8601String(), 'error' => $e->getMessage()]
            + (array) (Cache::get(self::STATUS_KEY) ?? []));
    }

    private function storePages(array $rows): int
    {
        $now = now();
        $batch = [];
        foreach ($rows as $row) {
            [$date, $url] = $row['keys'];
            $content = $this->resolver->resolve($url);
            $batch[] = [
                'date'         => $date,
                'url_hash'     => sha1($url),
                'url'          => $url,
                'host'         => (string) parse_url($url, PHP_URL_HOST),
                'locale'       => $content['locale'],
                'content_type' => $content['type'],
                'content_id'   => $content['id'],
                'clicks'       => (int) $row['clicks'],
                'impressions'  => (int) $row['impressions'],
                'position'     => round((float) $row['position'], 2),
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }
        foreach (array_chunk($batch, 1000) as $chunk) {
            DB::table('search_console_pages')->upsert(
                $chunk,
                ['date', 'url_hash'],
                ['url', 'host', 'locale', 'content_type', 'content_id', 'clicks', 'impressions', 'position', 'updated_at']
            );
        }
        return count($batch);
    }

    /** Replaces the 28-day query snapshot (the first property clears it). */
    private function storeQueries(array $rows, bool $replace): int
    {
        $now = now();
        $batch = array_map(fn ($row) => [
            'url_hash'    => sha1($row['keys'][0]),
            'query'       => mb_substr($row['keys'][1], 0, 500),
            'clicks'      => (int) $row['clicks'],
            'impressions' => (int) $row['impressions'],
            'position'    => round((float) $row['position'], 2),
            'created_at'  => $now,
            'updated_at'  => $now,
        ], $rows);

        DB::transaction(function () use ($batch, $replace) {
            if ($replace) {
                DB::table('search_console_queries')->delete();
            }
            foreach (array_chunk($batch, 1000) as $chunk) {
                DB::table('search_console_queries')->insert($chunk);
            }
        });
        return count($batch);
    }
}
