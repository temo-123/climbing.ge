<?php

namespace Tests\Feature\Orders;

use App\Models\Shop\Order;
use App\Services\OrderService;
use App\Services\OrderStatisticsService;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

/**
 * An option's own sale discount (product_options.discount %) must be what
 * every order flow charges — checkout, quote, manual / quick sale, legacy,
 * invoices, payment, statistics and the cart.
 */
class SaleDiscountTest extends OrderTestCase
{
    /** 55 ₾ −5% → 52.25 ₾ */
    private function sale_product(int $stock = 10): array
    {
        return $this->product(55, $stock, 'online_order', [], 5);
    }

    public function test_sale_price_formula_matches_the_shop(): void
    {
        $opt = (object) ['price' => 55, 'discount' => 5];
        $this->assertSame(52.25, ProductService::option_sale_price($opt));
        $this->assertSame(30.0, ProductService::option_sale_price((object) ['price' => 30, 'discount' => null]));
        $this->assertSame(0.0, ProductService::option_sale_price((object) ['price' => 30, 'discount' => 150]), 'clamped to 100%');
        $this->assertSame(30.0, ProductService::option_sale_price((object) ['price' => 30, 'discount' => -10]), 'negative ignored');
    }

    public function test_checkout_charges_the_sale_price_and_snapshots_it(): void
    {
        $buyer = $this->user();
        $p = $this->sale_product();
        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 2)]);

        $res->assertOk();
        $line = DB::table('order_products')->where('order_id', $res->json('order_id'))->first();
        $this->assertEquals(52.25, (float) $line->item_price);
        $this->assertEquals(104.5, (float) $line->total_price);
        $this->assertEquals(55, (float) $line->list_price);
        $this->assertEquals(5, (float) $line->item_discount);

        $order = Order::find($res->json('order_id'));
        $this->assertEquals(114.5, OrderService::totals($order)['total'], '104.50 + 10 shipping');

        // A later discount change never alters the placed order.
        DB::table('product_options')->where('id', $p['option_id'])->update(['discount' => 50]);
        $this->assertEquals(114.5, OrderService::totals($order->fresh())['total']);
    }

    public function test_partner_discount_applies_on_top_of_the_sale_price(): void
    {
        $buyer = $this->user();
        $this->partner(10, $buyer);
        $p = $this->sale_product();

        $order_id = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 2)])->json('order_id');

        // 104.50 − 10.45 + 10
        $this->assertEquals(104.05, OrderService::totals(Order::find($order_id))['total']);
    }

    public function test_region_minimum_uses_what_the_customer_pays(): void
    {
        $buyer = $this->user();
        $p = $this->product(52, 10, 'online_order', [], 10);   // 46.80 after sale, region minimum 50

        $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 1)])
            ->assertStatus(400)->assertJsonPath('code', 'below_min_order');
    }

    public function test_quote_shows_sale_price_list_price_and_savings(): void
    {
        $buyer = $this->user();
        $p = $this->sale_product();
        Sanctum::actingAs($buyer);

        $q = $this->postJson('/api/set_order/checkout_quote', [
            'order_product_list' => [$this->cart_line($p, 2)], 'adres' => $this->address($buyer, $this->region(10, 50, 150)),
        ])->assertOk();

        $this->assertEquals(52.25, $q->json('lines.0.unit_price'));
        $this->assertEquals(55, $q->json('lines.0.list_price'));
        $this->assertEquals(5, $q->json('lines.0.item_discount'));
        $this->assertEquals(104.5, $q->json('subtotal'));
        $this->assertEquals(5.5, $q->json('sale_savings'));
        $this->assertEquals(114.5, $q->json('total'));
    }

    public function test_manual_and_legacy_orders_charge_the_sale_price(): void
    {
        $p = $this->sale_product();

        $manual = $this->manual($this->admin(), [$this->manual_line($p, 2)])->assertCreated();
        $this->assertEquals(104.5, OrderService::totals(Order::find($manual->json('order_id')))['total']);

        Sanctum::actingAs($this->admin());
        $legacy = $this->postJson('/api/set_order/add_custom_order', [
            'delivery_type' => 'self_delivery', 'payment_type' => 'deliverd_payment', 'order_product_list' => [$this->manual_line($p, 1)],
        ])->assertOk();
        $this->assertEquals(52.25, (float) DB::table('order_products')->where('order_id', $legacy->json('order_id'))->value('item_price'));
    }

    public function test_custom_order_product_feed_and_preview_invoice_use_sale_price(): void
    {
        $p = $this->sale_product();
        Sanctum::actingAs($this->admin());

        $options = $this->getJson('/api/custom_order/get_product_options/' . $p['product_id'])->assertOk()->json();
        $this->assertEquals(52.25, $options[0]['price']);
        $this->assertEquals(55, $options[0]['list_price']);
        $this->assertEquals(5, $options[0]['discount']);

        $pdf = $this->post('/api/custom_order/export_invoice_pdf', [
            'name' => 'A', 'surname' => 'B', 'locale' => 'en', 'order_product_list' => [$this->manual_line($p, 2)],
        ]);
        $pdf->assertOk();
        $this->assertStringStartsWith('%PDF', $pdf->getContent());
    }

    public function test_invoice_and_detail_carry_the_was_price(): void
    {
        $buyer = $this->user();
        $p = $this->sale_product();
        $order = Order::find($this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 2)])->json('order_id'));

        $line = OrderService::invoice_data($order, 'en')['line_items'][0];
        $this->assertEquals([52.25, 55.0, 5.0, 104.5], [$line['unit_price'], $line['list_price'], $line['item_discount'], $line['line_total']]);

        Sanctum::actingAs($this->admin());
        $detail = $this->getJson('/api/get_order/get_order_detals/' . $order->id)->assertOk();
        $this->assertEquals(55, $detail->json('order_products.0.list_price'));
        $this->assertEquals(114.5, $detail->json('totals.total'));
        $this->get('/api/get_order/invoice_pdf/' . $order->id)->assertOk();
    }

    public function test_online_payment_amount_uses_the_sale_price(): void
    {
        config(['services.tbc_pay.base_url' => 'https://tbc.test/v1/tpay', 'services.tbc_pay.shop.client_id' => 'i', 'services.tbc_pay.shop.client_secret' => 's', 'services.tbc_pay.shop.api_key' => 'k']);
        \Illuminate\Support\Facades\Cache::forget('tbc_pay_token_shop');
        \Illuminate\Support\Facades\Http::fake([
            '*/access-token' => \Illuminate\Support\Facades\Http::response(['access_token' => 't']),
            '*/payments'     => \Illuminate\Support\Facades\Http::response(['payId' => 'P' . uniqid(), 'status' => 'CREATED', 'links' => [['method' => 'REDIRECT', 'uri' => 'https://x']]]),
        ]);
        $buyer = $this->user();
        $p = $this->sale_product();
        $order_id = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 2)], 'online payment')->json('order_id');

        $this->postJson('/api/payment/shop/initiate/' . $order_id)->assertOk();

        $this->assertEquals(114.5, (float) Order::find($order_id)->payment_amount);
    }

    public function test_cart_api_returns_the_sale_price(): void
    {
        $buyer = $this->user();
        $p = $this->sale_product();
        DB::table('carts')->insert(['user_id' => $buyer->id, 'option_id' => $p['option_id'], 'quantity' => 1, 'created_at' => now(), 'updated_at' => now()]);
        Sanctum::actingAs($buyer);

        $item = collect($this->getJson('/api/cart')->assertOk()->json())->firstWhere('option.id', $p['option_id']);

        $this->assertEquals(52.25, $item['unit_price']);
        $this->assertEquals(55, $item['list_price']);
        $this->assertEquals(5, $item['item_discount']);
    }

    public function test_statistics_revenue_uses_the_charged_price(): void
    {
        // Frozen far in the future so only this test's order is in the window.
        \Illuminate\Support\Carbon::setTestNow('2031-06-10 12:00:00');
        try {
            $p = $this->sale_product();
            $this->manual($this->admin(), [$this->manual_line($p, 2)])->assertCreated();

            $this->assertEquals(104.5, OrderStatisticsService::build('today')['summary']['revenue']);
        } finally {
            \Illuminate\Support\Carbon::setTestNow();
        }
    }
}
