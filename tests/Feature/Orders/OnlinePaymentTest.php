<?php

namespace Tests\Feature\Orders;

use App\Models\Shop\Order;
use App\Models\Site;
use App\Notifications\order\AdminOrderDeclorationNotification;
use App\Notifications\order\OrderConfirm;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

/**
 * Website "online payment": checkout → payment/shop/initiate → TBC callback /
 * status poll. TBC is faked at the HTTP layer, so TbcPaymentService runs for real.
 */
class OnlinePaymentTest extends OrderTestCase
{
    private string $tbc_status = 'CREATED';

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.tbc_pay.base_url'            => 'https://tbc.test/v1/tpay',
            'services.tbc_pay.shop.client_id'      => 'id',
            'services.tbc_pay.shop.client_secret'  => 'secret',
            'services.tbc_pay.shop.api_key'        => 'key',
        ]);
        Cache::forget('tbc_pay_token_shop');

        Http::fake(function ($request) {
            $url = $request->url();
            if (str_ends_with($url, '/access-token')) return Http::response(['access_token' => 'tok']);
            if (str_ends_with($url, '/payments') && $request->method() === 'POST') {
                return Http::response(['payId' => 'PAY-' . uniqid(), 'status' => 'CREATED', 'links' => [['method' => 'REDIRECT', 'uri' => 'https://tbc.test/pay']]]);
            }
            if (str_contains($url, '/payments/')) return Http::response(['status' => $this->tbc_status]);
            return Http::response([], 404);
        });
    }

    /** Places an online-payment order and initiates payment; returns the order. */
    private function paid_checkout(array $lines): Order
    {
        $buyer = $this->user();
        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), $lines, 'online payment');
        $res->assertOk();
        $order = Order::find($res->json('order_id'));

        Notification::assertNotSentTo($buyer, OrderConfirm::class);   // no e-mail confirmation for online payment

        $this->postJson('/api/payment/shop/initiate/' . $order->id)
            ->assertOk()->assertJsonPath('payment_url', 'https://tbc.test/pay');

        return $order->fresh();
    }

    private function tbc_callback(Order $order, string $status)
    {
        $this->tbc_status = $status;
        $this->app['auth']->forgetGuards();
        return $this->postJson('/api/payment/shop/callback', ['PaymentId' => $order->tbc_pay_id]);
    }

    public function test_initiate_stores_pay_id_and_amount(): void
    {
        $p = $this->product(60, 10);
        $order = $this->paid_checkout([$this->cart_line($p, 1)]);

        $this->assertNotNull($order->tbc_pay_id);
        $this->assertEquals(70, (float) $order->payment_amount, '60 + 10 shipping, no discount');

        $this->postJson('/api/payment/shop/initiate/' . $order->id)->assertStatus(400);   // only once
    }

    public function test_only_the_buyer_can_initiate(): void
    {
        $p = $this->product(60, 10);
        $buyer = $this->user();
        $res = $this->checkout($buyer, $this->address($buyer, $this->region()), [$this->cart_line($p, 1)], 'online payment');

        Sanctum::actingAs($this->user());
        $this->postJson('/api/payment/shop/initiate/' . $res->json('order_id'))->assertNotFound();
    }

    public function test_successful_payment_confirms_and_notifies_seller_once(): void
    {
        $p = $this->product(60, 10);
        $order = $this->paid_checkout([$this->cart_line($p, 1)]);

        $this->tbc_callback($order, 'Succeeded')->assertOk();
        $order->refresh();
        $this->assertSame('paid', $order->status);
        $this->assertEquals(1, $order->confirm);
        $this->assertSame(9, $this->stock($p['option_id']), 'stock stays taken');

        // Status poll seeing the same success must not notify again.
        Sanctum::actingAs(\App\Models\User::find($order->user_id));
        $this->getJson('/api/payment/shop/status/' . $order->id)->assertOk()->assertJsonPath('status', 'paid');

        Notification::assertSentToTimes(Site::first(), AdminOrderDeclorationNotification::class, 1);
    }

    public function test_failed_payment_gives_stock_back_exactly_once(): void
    {
        $a = $this->product(30, 10);
        $made = $this->product(40, 0, 'produced_by_order');
        $order = $this->paid_checkout([$this->cart_line($a, 2), $this->cart_line($made, 1)]);
        $this->assertSame(8, $this->stock($a['option_id']));

        $this->tbc_callback($order, 'Failed')->assertOk();
        $this->assertSame('payment_failed', $order->fresh()->status);
        $this->assertSame(10, $this->stock($a['option_id']));
        $this->assertSame(0, $this->stock($made['option_id']), 'made-to-order line never took stock, so nothing comes back');

        // Repeated callback + status poll: still not double-restocked.
        $this->tbc_callback($order, 'Failed')->assertOk();
        Sanctum::actingAs(\App\Models\User::find($order->user_id));
        $this->tbc_status = 'Expired';
        $this->getJson('/api/payment/shop/status/' . $order->id)->assertOk();

        $this->assertSame(10, $this->stock($a['option_id']));
        $this->assertSame(0, DB::table('order_products')->where('order_id', $order->id)->whereNotNull('stock_warehouse_id')->count());
    }

    public function test_callback_for_unknown_payment_is_404(): void
    {
        $this->postJson('/api/payment/shop/callback', ['PaymentId' => 'nope-' . uniqid()])->assertNotFound();
    }

    public function test_status_of_someone_elses_order_is_404(): void
    {
        $p = $this->product(60, 10);
        $order = $this->paid_checkout([$this->cart_line($p, 1)]);

        Sanctum::actingAs($this->user());
        $this->getJson('/api/payment/shop/status/' . $order->id)->assertNotFound();
    }
}
