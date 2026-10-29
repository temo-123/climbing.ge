<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/**
 * Recommended services (suport_local_bisneses) statistics, kept in Redis:
 *
 *   cg:biz:{id}:{metric}          hash  Y-m-d → count          (all events)
 *   cg:biz:{id}:{metric}:uniq     hash  Y-m-d → count          (one per visitor per day)
 *   cg:biz:{id}:impression:art    hash  article_id → count     (where the card was shown)
 *   cg:biz:seen:{id}:{metric}:{visitor}:{Y-m-d}  key, 2-day TTL (unique-visitor marker)
 *
 * Visitors are a salted hash of IP + user agent, never stored raw. Days older
 * than KEEP_DAYS are trimmed when read. If Redis is unreachable the same
 * counters go to the default Laravel cache instead, so tracking never breaks
 * a page.
 */
class BusinessStatsService
{
    const METRICS = ['impression', 'view', 'click', 'photo'];
    const KEEP_DAYS = 400;

    public static function track(int $bisnes_id, string $metric, ?int $article_id, string $ip, string $agent): void
    {
        if (!in_array($metric, self::METRICS)) return;

        $day = now()->toDateString();
        $visitor = substr(hash('sha256', config('app.key') . '|' . $ip . '|' . $agent), 0, 20);
        $base = "cg:biz:{$bisnes_id}:{$metric}";

        try {
            $redis = Redis::connection();
            $redis->hincrby($base, $day, 1);
            if ($redis->set("cg:biz:seen:{$bisnes_id}:{$metric}:{$visitor}:{$day}", 1, 'EX', 172800, 'NX')) {
                $redis->hincrby("{$base}:uniq", $day, 1);
            }
            if ($metric === 'impression' && $article_id) {
                $redis->hincrby("cg:biz:{$bisnes_id}:impression:art", (string) $article_id, 1);
            }
        } catch (\Throwable $e) {
            self::fallback_track($base, $day, $visitor, $bisnes_id, $metric, $article_id);
        }
    }

    /**
     * Daily series for the last $days days + totals + per-article impressions.
     */
    public static function stats(int $bisnes_id, int $days = 30): array
    {
        $days = max(1, min($days, self::KEEP_DAYS));
        $dates = [];
        for ($i = $days - 1; $i >= 0; $i--) $dates[] = now()->subDays($i)->toDateString();

        $raw = [];
        $by_article = [];
        $source = 'redis';
        try {
            $redis = Redis::connection();
            foreach (self::METRICS as $m) {
                $raw[$m] = $redis->hgetall("cg:biz:{$bisnes_id}:{$m}") ?: [];
                $raw[$m . '_uniq'] = $redis->hgetall("cg:biz:{$bisnes_id}:{$m}:uniq") ?: [];
                self::trim($redis, "cg:biz:{$bisnes_id}:{$m}", $raw[$m]);
                self::trim($redis, "cg:biz:{$bisnes_id}:{$m}:uniq", $raw[$m . '_uniq']);
            }
            $by_article = $redis->hgetall("cg:biz:{$bisnes_id}:impression:art") ?: [];
        } catch (\Throwable $e) {
            $source = 'cache';
            foreach (self::METRICS as $m) {
                $raw[$m] = Cache::get("cg:biz:{$bisnes_id}:{$m}", []);
                $raw[$m . '_uniq'] = Cache::get("cg:biz:{$bisnes_id}:{$m}:uniq", []);
            }
            $by_article = Cache::get("cg:biz:{$bisnes_id}:impression:art", []);
        }

        $series = [];
        $totals = [];
        $all_time = [];
        foreach (array_keys($raw) as $key) {
            $series[$key] = array_map(fn ($d) => (int) ($raw[$key][$d] ?? 0), $dates);
            $totals[$key] = array_sum($series[$key]);
            $all_time[$key] = array_sum(array_map('intval', $raw[$key]));
        }

        arsort($by_article);

        return [
            'source'     => $source,
            'days'       => $days,
            'dates'      => $dates,
            'series'     => $series,
            'totals'     => $totals,
            'all_time'   => $all_time,
            'by_article' => array_map('intval', $by_article),
        ];
    }

    // drop days older than KEEP_DAYS (hash fields are dates)
    private static function trim($redis, string $key, array &$hash): void
    {
        $limit = now()->subDays(self::KEEP_DAYS)->toDateString();
        $old = array_filter(array_keys($hash), fn ($d) => $d < $limit);
        if ($old) {
            $redis->hdel($key, ...$old);
            foreach ($old as $d) unset($hash[$d]);
        }
    }

    private static function fallback_track(string $base, string $day, string $visitor, int $bisnes_id, string $metric, ?int $article_id): void
    {
        try {
            $bump = function ($key, $field) {
                $hash = Cache::get($key, []);
                $hash[$field] = ($hash[$field] ?? 0) + 1;
                Cache::forever($key, $hash);
            };
            $bump($base, $day);
            if (Cache::add("cg:biz:seen:{$bisnes_id}:{$metric}:{$visitor}:{$day}", 1, 172800)) $bump("{$base}:uniq", $day);
            if ($metric === 'impression' && $article_id) $bump("cg:biz:{$bisnes_id}:impression:art", (string) $article_id);
        } catch (\Throwable $e) {
            Log::warning('Business stats not saved: ' . $e->getMessage());
        }
    }
}
