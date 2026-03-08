<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class ActionTrackingService
{
    /**
     * Save action count for a block by day
     *
     * @param int $blockId
     * @param string $actionType (e.g., 'show_more_click')
     * @param array $additionalData
     * @return bool
     */
    public static function saveAction($blockId, $actionType = 'show_more_click', $additionalData = [])
    {
        try {
            $date = Carbon::now()->format('Y-m-d');
            $key = "block_actions:{$date}:{$blockId}:{$actionType}";

            // Increment the click count
            $clickCount = Redis::incr($key);

            // Set expiration for the key (keep data for 30 days)
            Redis::expire($key, 30 * 24 * 60 * 60);

            // Store additional data if provided
            if (!empty($additionalData)) {
                $dataKey = $key . ':data';
                Redis::hmset($dataKey, $additionalData);
                Redis::expire($dataKey, 30 * 24 * 60 * 60);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to save action to Redis', [
                'block_id' => $blockId,
                'action_type' => $actionType,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get action count for a specific block and date
     *
     * @param int $blockId
     * @param string $date (Y-m-d format)
     * @param string $actionType
     * @return int
     */
    public static function getActionCount($blockId, $date = null, $actionType = 'show_more_click')
    {
        $date = $date ?: Carbon::now()->format('Y-m-d');
        $key = "block_actions:{$date}:{$blockId}:{$actionType}";

        return (int) Redis::get($key);
    }

    /**
     * Get action counts for a date range
     *
     * @param string $startDate
     * @param string $endDate
     * @param int|null $blockId
     * @param string $actionType
     * @return array
     */
    public static function getActionCountsByDateRange($startDate, $endDate, $blockId = null, $actionType = 'show_more_click')
    {
        $counts = [];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($start <= $end) {
            $date = $start->format('Y-m-d');

            if ($blockId) {
                $counts[$date] = self::getActionCount($blockId, $date, $actionType);
            } else {
                // Get all blocks for this date (this would require scanning keys, which is expensive)
                // For now, return empty or implement a different approach
                $counts[$date] = 0;
            }

            $start->addDay();
        }

        return $counts;
    }

    /**
     * Get total actions for a block across all dates
     *
     * @param int $blockId
     * @param string $actionType
     * @return int
     */
    public static function getTotalActionsForBlock($blockId, $actionType = 'show_more_click')
    {
        // This would require scanning all keys with the pattern, which is expensive
        // Consider maintaining a separate counter for totals
        $pattern = "block_actions:*: {$blockId}:{$actionType}";
        $keys = Redis::keys($pattern);

        $total = 0;
        foreach ($keys as $key) {
            $total += (int) Redis::get($key);
        }

        return $total;
    }

    /**
     * Get action data for a specific block and date
     *
     * @param int $blockId
     * @param string $date
     * @param string $actionType
     * @return array
     */
    public static function getActionData($blockId, $date = null, $actionType = 'show_more_click')
    {
        $date = $date ?: Carbon::now()->format('Y-m-d');
        $key = "block_actions:{$date}:{$blockId}:{$actionType}";
        $dataKey = $key . ':data';

        return [
            'count' => (int) Redis::get($key),
            'data' => Redis::hgetall($dataKey)
        ];
    }
}
