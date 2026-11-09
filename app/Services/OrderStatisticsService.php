<?php

namespace App\Services;

use App\Models\Shop\Order;
use Illuminate\Support\Carbon;

/**
 * Numbers for the shop dashboard order / revenue charts
 * (GET get_order/get_order_dashboard/{period}, docs/FUNCTIONS_DOCS/shop/order_statistics.md).
 *
 * - Every bucket in the period is present (zero-filled), so a quiet week shows
 *   as 0 instead of the line jumping over it.
 * - Bucket size follows the period: hour (today), day (7 days – 1 month), week (3–6 months), month (longer).
 * - Revenue = the same order total the order screens show (OrderService:
 *   products − discount + shipping), not raw line sums.
 * - Only real orders count: confirmed, not failed / cancelled. Orders still
 *   waiting for e-mail confirmation or online payment, and failed / cancelled
 *   ones, are reported separately and left out of the charts.
 */
class OrderStatisticsService
{
    const PERIODS = ['today', '7days', '30days', '1month', '3months', '6months', '1year', '2years', '3years', 'all'];

    const CANCELLED_STATUSES = ['cancelled', 'canceled', 'Cancelled', 'Canceled'];
    const FAILED_STATUSES    = ['payment_failed'];

    public static function build(string $period, ?Carbon $now = null): ?array
    {
        if (!in_array($period, self::PERIODS, true)) return null;

        $now   = ($now ?? now())->copy();
        $start = self::period_start($period, $now);
        $unit  = self::granularity($period);
        $from  = self::bucket_start($start, $unit);

        $orders = Order::with('orderProducts.option:id,price,discount,currency')
            ->where('created_at', '>=', $from)
            ->where('created_at', '<=', $now)
            ->get();

        // Empty buckets first, so the series has no holes.
        $buckets = [];
        for ($cursor = $from->copy(); $cursor <= $now; $cursor = self::next_bucket($cursor, $unit)) {
            $key = self::bucket_key($cursor, $unit);
            $buckets[$key] = ['key' => $key, 'from' => $unit === 'hour' ? $cursor->format('Y-m-d\TH:i:s') : $cursor->toDateString(), 'website' => 0, 'manual' => 0, 'orders' => 0, 'revenue' => 0.0, 'items' => 0];
        }

        $summary = ['orders' => 0, 'website' => 0, 'manual' => 0, 'revenue' => 0.0, 'items' => 0, 'awaiting' => 0, 'failed' => 0, 'cancelled' => 0];

        foreach ($orders as $order) {
            $state = self::state($order);
            if ($state !== 'counted') {
                $summary[$state]++;
                continue;
            }

            $s    = OrderService::list_summary($order);
            $key  = self::bucket_key(Carbon::parse($order->created_at), $unit);
            $kind = $s['order_type'] === OrderService::TYPE_MANUAL ? 'manual' : 'website';
            if (!isset($buckets[$key])) continue;

            $buckets[$key][$kind]++;
            $buckets[$key]['orders']++;
            $buckets[$key]['revenue'] += $s['order_total'];
            $buckets[$key]['items']   += $s['items_count'];

            $summary[$kind]++;
            $summary['orders']++;
            $summary['revenue'] += $s['order_total'];
            $summary['items']   += $s['items_count'];
        }

        foreach ($buckets as &$b) $b['revenue'] = round($b['revenue'], 2);
        unset($b);

        $summary['revenue']   = round($summary['revenue'], 2);
        $summary['avg_order'] = $summary['orders'] ? round($summary['revenue'] / $summary['orders'], 2) : 0.0;
        $summary['previous']  = $period === 'all' ? null : self::previous($from, $now);

        return [
            'period'      => $period,
            'granularity' => $unit,
            'from'        => $from->toDateString(),
            'to'          => $now->toDateString(),
            'currency'    => '₾',
            'buckets'     => array_values($buckets),
            'summary'     => $summary,
        ];
    }

    /**
     * Shop tiles on the admin home / dashboards (DashboardOverviewController):
     * real orders, revenue and still-open orders — same rules as the charts.
     */
    public static function overview(string $period = '7days'): array
    {
        $s = self::build($period)['summary'];

        return [
            'orders'   => $s['orders'],
            'revenue'  => $s['revenue'],
            'awaiting' => $s['awaiting'],
            'open'     => self::open_orders_count(),
        ];
    }

    // Orders that still need work: real (confirmed, not failed / cancelled)
    // and not yet delivered / completed. Statuses are free text from several
    // flows ('pending', 'treatment', 'paid', 'Ready to ship'…), so this lists
    // the finished ones instead of guessing every open spelling.
    const DONE_STATUSES = ['Delivered', 'delivered', 'Order Complete', 'completed'];

    public static function open_orders_count(): int
    {
        return Order::where('confirm', 1)
            ->where(fn($q) => $q->whereNull('status')->orWhereNotIn('status', array_merge(self::DONE_STATUSES, self::FAILED_STATUSES, self::CANCELLED_STATUSES)))
            ->count();
    }

    /** 'counted' | 'awaiting' | 'failed' | 'cancelled' */
    public static function state(Order $order): string
    {
        if (in_array($order->status, self::FAILED_STATUSES, true)) return 'failed';
        if (in_array($order->status, self::CANCELLED_STATUSES, true)) return 'cancelled';
        if ((int) $order->confirm !== 1) return 'awaiting';
        return 'counted';
    }

    /** Same-length window right before this one, for "vs previous period". */
    private static function previous(Carbon $from, Carbon $now): array
    {
        $length = $from->diffInSeconds($now);
        $prev_from = $from->copy()->subSeconds($length);

        $orders = Order::with('orderProducts.option:id,price,discount,currency')
            ->where('created_at', '>=', $prev_from)
            ->where('created_at', '<', $from)
            ->get()
            ->filter(fn($o) => self::state($o) === 'counted');

        return [
            'from'    => $prev_from->toDateString(),
            'orders'  => $orders->count(),
            'revenue' => round($orders->sum(fn($o) => OrderService::list_summary($o)['order_total']), 2),
        ];
    }

    private static function period_start(string $period, Carbon $now): Carbon
    {
        return match ($period) {
            'today'   => $now->copy()->startOfDay(),
            '7days'   => $now->copy()->subDays(6)->startOfDay(),
            '30days'  => $now->copy()->subDays(29)->startOfDay(),
            '1month'  => $now->copy()->subMonthNoOverflow()->addDay()->startOfDay(),
            '3months' => $now->copy()->subMonthsNoOverflow(3),
            '6months' => $now->copy()->subMonthsNoOverflow(6),
            '1year'   => $now->copy()->subYear()->addMonth(),
            '2years'  => $now->copy()->subYears(2)->addMonth(),
            '3years'  => $now->copy()->subYears(3)->addMonth(),
            'all'     => ($first = Order::min('created_at')) ? Carbon::parse($first) : $now->copy(),
        };
    }

    public static function granularity(string $period): string
    {
        return match ($period) {
            'today'              => 'hour',
            '7days', '30days', '1month' => 'day',
            '3months', '6months' => 'week',
            default              => 'month',
        };
    }

    private static function bucket_start(Carbon $d, string $unit): Carbon
    {
        return match ($unit) {
            'hour'  => $d->copy()->startOfHour(),
            'day'   => $d->copy()->startOfDay(),
            'week'  => $d->copy()->startOfWeek(Carbon::MONDAY),
            'month' => $d->copy()->startOfMonth(),
        };
    }

    private static function next_bucket(Carbon $d, string $unit): Carbon
    {
        return match ($unit) {
            'hour'  => $d->copy()->addHour(),
            'day'   => $d->copy()->addDay(),
            'week'  => $d->copy()->addWeek(),
            'month' => $d->copy()->addMonthNoOverflow(),
        };
    }

    /** hour → 'Y-m-d H:00', day / week → 'Y-m-d' (week = its Monday), month → 'Y-m'. */
    private static function bucket_key(Carbon $d, string $unit): string
    {
        return match ($unit) {
            'hour'  => $d->format('Y-m-d H:00'),
            'day'   => $d->toDateString(),
            'week'  => $d->copy()->startOfWeek(Carbon::MONDAY)->toDateString(),
            'month' => $d->format('Y-m'),
        };
    }
}
