<?php

namespace Tests\Feature\Orders;

use App\Models\Shop\Order;
use App\Notifications\order\OrderConfirm;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

/**
 * Shop dashboard charts (GET get_order/get_order_dashboard/{period}) and the
 * post-checkout "send the confirmation e-mail again" endpoint.
 *
 * "Now" is frozen far in the future so the dev database's real orders fall
 * outside every window except 'all'.
 */
class OrderStatisticsTest extends OrderTestCase
{
    private Carbon $now;

    protected function setUp(): void
    {
        parent::setUp();
        $this->now = Carbon::parse('2031-03-20 15:00:00');
        Carbon::setTestNow($this->now);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /** Confirmed website order: 2 × 30, 10 shipping, 10% discount → 64. */
    private function website_at(string $when, array $attrs = ['confirm' => 1]): Order
    {
        $buyer = $this->user();
        $this->partner(10, $buyer);
        $p = $this->product(30, 10);
        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 2)]);
        $res->assertOk();
        DB::table('orders')->where('id', $res->json('order_id'))->update(array_merge(['created_at' => $when], $attrs));
        return Order::find($res->json('order_id'));
    }

    /** Manual pickup order: 1 × 40 → 40 (manual orders are confirmed on creation). */
    private function manual_at(string $when): Order
    {
        $p = $this->product(40, 10);
        $res = $this->manual($this->admin(), [$this->manual_line($p, 1)]);
        $res->assertCreated();
        DB::table('orders')->where('id', $res->json('order_id'))->update(['created_at' => $when]);
        return Order::find($res->json('order_id'));
    }

    private function dashboard(string $period)
    {
        Sanctum::actingAs($this->admin());
        return $this->getJson('/api/get_order/get_order_dashboard/' . $period);
    }

    public function test_30_days_has_every_day_zero_filled(): void
    {
        $this->website_at('2031-03-05 10:00:00');
        $this->manual_at('2031-03-05 18:00:00');
        $this->website_at('2031-03-19 09:00:00');

        $res = $this->dashboard('30days')->assertOk();

        $buckets = collect($res->json('buckets'));
        $this->assertSame('day', $res->json('granularity'));
        $this->assertCount(30, $buckets);
        $this->assertSame('2031-02-19', $buckets->first()['key']);
        $this->assertSame('2031-03-20', $buckets->last()['key']);

        $mar5 = $buckets->firstWhere('key', '2031-03-05');
        $this->assertSame([1, 1, 2], [$mar5['website'], $mar5['manual'], $mar5['orders']]);
        $this->assertEquals(104, $mar5['revenue'], '64 + 40 — real order totals, not raw line sums');
        $this->assertSame(0, $buckets->firstWhere('key', '2031-03-10')['orders'], 'quiet day is 0, not missing');

        $res->assertJsonPath('summary.orders', 3)
            ->assertJsonPath('summary.website', 2)
            ->assertJsonPath('summary.manual', 1);
        $this->assertEquals(168, $res->json('summary.revenue'));
        $this->assertEquals(56, $res->json('summary.avg_order'));
        $this->assertSame(5, $res->json('summary.items'));
    }

    public function test_unconfirmed_failed_and_cancelled_orders_are_not_counted(): void
    {
        $this->website_at('2031-03-10 10:00:00');
        $this->website_at('2031-03-11 10:00:00', ['confirm' => 0]);                       // awaiting e-mail
        $this->website_at('2031-03-12 10:00:00', ['confirm' => 0, 'status' => 'payment_failed']);
        $this->website_at('2031-03-13 10:00:00', ['confirm' => 1, 'status' => 'Cancelled']);

        $res = $this->dashboard('30days')->assertOk();

        $res->assertJsonPath('summary.orders', 1)
            ->assertJsonPath('summary.awaiting', 1)
            ->assertJsonPath('summary.failed', 1)
            ->assertJsonPath('summary.cancelled', 1);
        $this->assertEquals(64, $res->json('summary.revenue'));
        $this->assertSame(0, collect($res->json('buckets'))->firstWhere('key', '2031-03-11')['orders']);
    }

    public function test_six_months_is_weekly_and_one_year_monthly_with_gaps_filled(): void
    {
        $this->website_at('2030-11-04 10:00:00');   // a Monday
        $this->website_at('2031-01-15 10:00:00');

        $weekly = $this->dashboard('6months')->assertOk();
        $this->assertSame('week', $weekly->json('granularity'));
        $weeks = collect($weekly->json('buckets'));
        $this->assertSame(1, $weeks->firstWhere('key', '2030-11-04')['orders']);
        $this->assertSame(1, $weeks->firstWhere('key', '2031-01-13')['orders'], 'Jan 15 falls in the week starting Monday Jan 13');
        $this->assertTrue($weeks->every(fn($w) => Carbon::parse($w['key'])->isMonday()));

        $monthly = $this->dashboard('1year')->assertOk();
        $months = collect($monthly->json('buckets'));
        $this->assertSame('month', $monthly->json('granularity'));
        $this->assertCount(12, $months);
        $this->assertSame(0, $months->firstWhere('key', '2030-12')['orders'], 'December has no orders but is present');
        $this->assertSame(1, $months->firstWhere('key', '2031-01')['orders']);
    }

    public function test_previous_period_comparison(): void
    {
        $this->website_at('2031-01-25 10:00:00');   // previous 30-day window
        $this->website_at('2031-01-30 10:00:00');
        $this->website_at('2031-03-01 10:00:00');   // current window

        $res = $this->dashboard('30days')->assertOk();

        $res->assertJsonPath('summary.orders', 1)->assertJsonPath('summary.previous.orders', 2);
        $this->assertEquals(128, $res->json('summary.previous.revenue'));
        $this->assertNull($this->dashboard('all')->json('summary.previous'));
    }

    public function test_legacy_table_endpoints_use_the_same_numbers(): void
    {
        $this->website_at('2031-03-05 10:00:00');
        Sanctum::actingAs($this->admin());

        $orders = $this->getJson('/api/get_order/get_order_statistics/30days')->assertOk()->json();
        $revenue = $this->getJson('/api/get_order/get_order_finance_statistics/30days')->assertOk()->json();

        $this->assertSame(['Date', 'Orders'], $orders[0]);
        $this->assertCount(31, $orders, 'header + 30 days');
        $this->assertContains(['2031-03-05', 1], $orders);
        $this->assertContains(['2031-03-05', 64], $revenue);
    }

    public function test_today_is_hourly_and_7_days_daily(): void
    {
        $this->website_at('2031-03-20 09:15:00');
        $this->manual_at('2031-03-20 09:40:00');
        $this->website_at('2031-03-14 12:00:00');   // inside 7 days (14th..20th)
        $this->website_at('2031-03-13 12:00:00');   // outside

        $today = $this->dashboard('today')->assertOk();
        $hours = collect($today->json('buckets'));
        $this->assertSame('hour', $today->json('granularity'));
        $this->assertCount(16, $hours, '00:00 … 15:00');
        $this->assertSame(2, $hours->firstWhere('key', '2031-03-20 09:00')['orders']);
        $this->assertEquals(104, $today->json('summary.revenue'));

        $week = $this->dashboard('7days')->assertOk();
        $this->assertCount(7, $week->json('buckets'));
        $week->assertJsonPath('summary.orders', 3);
        $this->assertEquals(168, $week->json('summary.revenue'));
        $week->assertJsonPath('summary.previous.orders', 1);
    }

    public function test_admin_overview_shop_tiles_use_real_totals(): void
    {
        $this->website_at('2031-03-18 10:00:00');                                       // 64, open
        $this->manual_at('2031-03-19 10:00:00');                                        // 40, open (pending)
        $this->website_at('2031-03-19 11:00:00', ['confirm' => 1, 'status' => 'Delivered']); // 64, done
        $this->website_at('2031-03-19 12:00:00', ['confirm' => 0]);                     // awaiting

        $overview = \App\Services\OrderStatisticsService::overview('7days');

        $this->assertSame(3, $overview['orders']);
        $this->assertEquals(168, $overview['revenue'], 'revenue_week used to read payment_amount (0 for cash orders)');
        $this->assertSame(1, $overview['awaiting']);

        Sanctum::actingAs($this->admin());
        $res = $this->getJson('/api/get_dashboard/get_admin_overview')->assertOk();
        $this->assertEquals(168, $res->json('blocks.shop.revenue_week'));
        $this->assertSame(3, $res->json('blocks.shop.orders_week'));
        $this->assertSame(1, $res->json('blocks.shop.awaiting_confirmation'));

        // "open" counts every real order that isn't delivered/complete, any age.
        $open_before = \App\Services\OrderStatisticsService::open_orders_count();
        $this->website_at('2031-03-20 08:00:00', ['confirm' => 1, 'status' => 'treatment']);
        $this->assertSame($open_before + 1, \App\Services\OrderStatisticsService::open_orders_count());

    }

    public function test_invalid_period_and_permission(): void
    {
        $this->dashboard('forever')->assertStatus(400);

        Sanctum::actingAs($this->user());
        $this->getJson('/api/get_order/get_order_dashboard/30days')->assertForbidden();
    }

    // ───── resend confirmation (post-checkout page) ─────

    public function test_buyer_can_resend_confirmation_once_a_minute(): void
    {
        $buyer = $this->user();
        $p = $this->product(60, 10);
        $order_id = $this->checkout($buyer, $this->address($buyer, $this->region()), [$this->cart_line($p, 1)])->json('order_id');
        Notification::fake();   // ignore the checkout's own mail

        $this->postJson('/api/set_order/resend_order_confirm/' . $order_id)->assertOk();
        Notification::assertSentToTimes($buyer, OrderConfirm::class, 1);

        $this->postJson('/api/set_order/resend_order_confirm/' . $order_id)->assertStatus(429)->assertJsonPath('code', 'too_soon');

        Carbon::setTestNow($this->now->copy()->addMinutes(2));   // cooldown is over
        $this->postJson('/api/set_order/resend_order_confirm/' . $order_id)->assertOk();
        Notification::assertSentToTimes($buyer, OrderConfirm::class, 2);
    }

    public function test_resend_is_refused_for_confirmed_online_or_foreign_orders(): void
    {
        $buyer = $this->user();
        $p = $this->product(60, 10);
        $address = $this->address($buyer, $this->region());

        $confirmed = $this->checkout($buyer, $address, [$this->cart_line($p, 1)])->json('order_id');
        DB::table('orders')->where('id', $confirmed)->update(['confirm' => 1]);
        $this->postJson('/api/set_order/resend_order_confirm/' . $confirmed)->assertStatus(400)->assertJsonPath('code', 'already_confirmed');

        $online = $this->checkout($buyer, $address, [$this->cart_line($p, 1)], 'online payment')->json('order_id');
        $this->postJson('/api/set_order/resend_order_confirm/' . $online)->assertStatus(400)->assertJsonPath('code', 'not_cash_order');

        Sanctum::actingAs($this->user());
        $this->postJson('/api/set_order/resend_order_confirm/' . $confirmed)->assertNotFound();
    }
}
