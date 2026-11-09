<?php

namespace Tests\Feature\Orders;

use App\Models\Shop\Order;
use App\Models\Site;
use App\Notifications\order\AdminOrderDeclorationNotification;
use App\Notifications\order\OrderStatusNotification;
use App\Services\AdminUserOverviewService;
use App\Services\OrderService;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

/**
 * After placement: e-mail confirmation, status changes, and every place an
 * order is read (lists, details, invoice, user overview) showing the same
 * numbers for both order kinds.
 */
class OrderLifecycleTest extends OrderTestCase
{
    /** website COD order: 2 × 30 + 10 shipping, 10% partner discount → 64 */
    private function website_order(): array
    {
        $buyer = $this->user();
        $this->partner(10, $buyer);
        $p = $this->product(30, 10);
        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 2)]);
        $res->assertOk();
        return [Order::find($res->json('order_id')), $buyer];
    }

    /** manual delivery order: 3 × 25 + 10 shipping → 85, buyer linked by e-mail */
    private function manual_order(): array
    {
        $customer = $this->user();
        $p = $this->product(25, 10);
        $res = $this->manual($this->admin(), [$this->manual_line($p, 3)], [
            'email' => $customer->email, 'delivery_type' => 'delivery', 'region_id' => $this->region(10, 50, 150), 'address' => 'Pekini 1',
        ]);
        $res->assertCreated();
        return [Order::find($res->json('order_id')), $customer];
    }

    // ───── confirmation & status ─────

    public function test_buyer_confirms_cash_order_by_email_link(): void
    {
        [$order, $buyer] = $this->website_order();
        Sanctum::actingAs($buyer);
        $url = '/api/set_order/order_is_confirm/' . $order->id;

        $this->postJson($url, ['token' => 'wrong'])->assertStatus(400);

        $token = hash_hmac('sha256', $order->id . '|' . $order->user_id, config('app.key'));
        $this->postJson($url, ['token' => $token])->assertOk();
        $order->refresh();
        $this->assertEquals(1, $order->confirm);
        $this->assertSame('treatment', $order->status);
        Notification::assertSentTo(Site::first(), AdminOrderDeclorationNotification::class);

        $this->postJson($url, ['token' => $token])->assertStatus(400);   // only once
    }

    public function test_status_change_mails_the_website_buyer(): void
    {
        [$order, $buyer] = $this->website_order();
        Sanctum::actingAs($this->admin());

        $this->postJson('/api/set_order/edit_order_status/' . $order->id, ['status' => 'shipped'])->assertOk();

        $this->assertSame('shipped', $order->fresh()->status);
        Notification::assertSentTo($buyer, OrderStatusNotification::class);
    }

    public function test_status_change_mails_the_manual_buyer_email(): void
    {
        [$order, $customer] = $this->manual_order();
        Sanctum::actingAs($this->admin());

        $this->postJson('/api/set_order/edit_order_status/' . $order->id, ['status' => 'delivered'])->assertOk();

        Notification::assertSentOnDemand(OrderStatusNotification::class, function ($n, $channels, AnonymousNotifiable $to) use ($customer) {
            return ($to->routes['mail'] ?? null) === $customer->email;
        });
    }

    public function test_plain_user_cannot_change_status(): void
    {
        [$order] = $this->website_order();
        Sanctum::actingAs($this->user());
        $this->postJson('/api/set_order/edit_order_status/' . $order->id, ['status' => 'shipped'])->assertForbidden();
    }

    // ───── same numbers everywhere ─────

    public function test_website_order_numbers_match_in_list_detail_invoice(): void
    {
        [$order] = $this->website_order();
        $this->assert_consistent($order, 64.0, 10.0, 'website', 'delivery', 'cash_on_delivery');
    }

    public function test_manual_order_numbers_match_in_list_detail_invoice(): void
    {
        [$order] = $this->manual_order();
        $this->assert_consistent($order, 85.0, 10.0, 'manual', 'delivery', 'cash_on_delivery');
    }

    private function assert_consistent(Order $order, float $total, float $shipping, string $type, string $method, string $payment): void
    {
        Sanctum::actingAs($this->admin());

        $row = collect($this->getJson('/api/get_order/get_all_orders')->assertOk()->json())->firstWhere('id', $order->id);
        $this->assertSame($type, $row['order_type']);
        $this->assertSame($method, $row['shipping_method']);
        $this->assertSame($payment, $row['payment_method']);
        $this->assertEquals($shipping, $row['shipping_cost']);
        $this->assertEquals($total, $row['order_total']);

        $detail = $this->getJson('/api/get_order/get_order_detals/' . $order->id . '?locale=ka')->assertOk();
        $detail->assertJsonPath('order_type', $type);
        $this->assertEquals($total, $detail->json('totals.total'));
        $this->assertEquals($shipping, $detail->json('totals.shipping'));
        $this->assertEquals($shipping, $detail->json('delivery.cost'));
        $this->assertStringStartsWith('KA ', $detail->json('order_products.0.title'), 'localized product title');

        $invoice = OrderService::invoice_data($order, 'en');
        $this->assertEquals($total, $invoice['total']);
        $this->assertEquals($shipping, $invoice['shipping']);

        $pdf = $this->get('/api/get_order/invoice_pdf/' . $order->id . '?locale=ka');
        $pdf->assertOk();
        $this->assertStringContainsString('application/pdf', $pdf->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF', $pdf->getContent());
    }

    // ───── buyer's own views ─────

    public function test_buyer_sees_own_order_and_invoice_only(): void
    {
        [$order, $buyer] = $this->website_order();
        [$other_order] = $this->website_order();

        Sanctum::actingAs($buyer);
        $list = collect($this->getJson('/api/get_order/get_user_purchules')->assertOk()->json());
        $this->assertEquals([$order->id], $list->pluck('id')->all());
        $this->assertEquals(64, $list->first()['order_total']);

        // The buyer's own view never carries the admin-only buyer account / linked accounts.
        $this->getJson('/api/get_order/get_my_purchase_detail/' . $order->id)->assertOk()
            ->assertJsonPath('buyer', null)->assertJsonPath('related_users', []);
        $this->getJson('/api/get_order/get_my_purchase_detail/' . $other_order->id)->assertNotFound();

        $this->get('/api/get_order/my_invoice_pdf/' . $order->id)->assertOk();
        $this->get('/api/get_order/my_invoice_pdf/' . $other_order->id)->assertNotFound();
        $this->get('/api/get_order/invoice_pdf/' . $order->id)->assertForbidden();   // admin endpoint
    }

    public function test_linked_manual_order_shows_in_user_overview(): void
    {
        [$order, $customer] = $this->manual_order();

        $shop = AdminUserOverviewService::build($customer->id)['shop'];

        $this->assertSame(1, $shop['orders']['count']);
        $this->assertSame($order->id, $shop['orders']['items'][0]->id);
    }
}
