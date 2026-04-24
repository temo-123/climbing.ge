<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class ActionTrackingService
{
    /**
     * Increment click counter for a block on the current day.
     */
    public static function saveAction(int $blockId, string $actionType = 'show_more_click'): bool
    {
        try {
            $date = Carbon::now()->format('Y-m-d');
            $key  = "block_actions:{$blockId}:{$actionType}:{$date}";

            Redis::incr($key);
            // Keep individual day keys for 1 year
            Redis::expire($key, 365 * 24 * 3600);

            return true;
        } catch (\Exception $e) {
            \Log::error('ActionTrackingService::saveAction failed', [
                'block_id'    => $blockId,
                'action_type' => $actionType,
                'error'       => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Count clicks for a single day (default: today).
     */
    public static function getDay(int $blockId, string $actionType = 'show_more_click', string $date = null): int
    {
        $date = $date ?? Carbon::now()->format('Y-m-d');
        return (int) Redis::get("block_actions:{$blockId}:{$actionType}:{$date}");
    }

    /**
     * Sum clicks across a range of dates (inclusive).
     * Returns ['total' => int, 'by_day' => ['Y-m-d' => int, ...]].
     */
    public static function getRange(int $blockId, string $actionType, Carbon $from, Carbon $to): array
    {
        $by_day = [];
        $cursor = $from->copy()->startOfDay();
        $end    = $to->copy()->startOfDay();

        $keys = [];
        while ($cursor <= $end) {
            $date        = $cursor->format('Y-m-d');
            $keys[$date] = "block_actions:{$blockId}:{$actionType}:{$date}";
            $cursor->addDay();
        }

        if (empty($keys)) {
            return ['total' => 0, 'by_day' => []];
        }

        $values = Redis::mget(array_values($keys));
        $dates  = array_keys($keys);
        $total  = 0;

        foreach ($dates as $i => $date) {
            $count        = (int) ($values[$i] ?? 0);
            $by_day[$date] = $count;
            $total        += $count;
        }

        return ['total' => $total, 'by_day' => $by_day];
    }

    /**
     * Build the full stats payload used by the admin modal:
     *   today / this_week / this_month / this_year counts
     *   + daily breakdown for the last 30 days (for a simple chart/table).
     */
    public static function getSummary(int $blockId, string $actionType = 'show_more_click'): array
    {
        $now = Carbon::now();

        $today      = self::getRange($blockId, $actionType, $now->copy()->startOfDay(),   $now->copy()->endOfDay());
        $week       = self::getRange($blockId, $actionType, $now->copy()->startOfWeek(),  $now->copy()->endOfWeek());
        $month      = self::getRange($blockId, $actionType, $now->copy()->startOfMonth(), $now->copy()->endOfMonth());
        $year       = self::getRange($blockId, $actionType, $now->copy()->startOfYear(),  $now->copy()->endOfYear());
        $last30     = self::getRange($blockId, $actionType, $now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay());

        return [
            'today'      => $today['total'],
            'this_week'  => $week['total'],
            'this_month' => $month['total'],
            'this_year'  => $year['total'],
            'last_30_days' => $last30['by_day'],
        ];
    }
}
