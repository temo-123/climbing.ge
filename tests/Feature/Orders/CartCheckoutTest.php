<?php

namespace Tests\Feature\Orders;

use App\Models\Shop\Order;
use App\Notifications\order\OrderConfirm;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

/** Website checkout: POST set_order/create_order → OrderPlacementService::place_website. */
class CartCheckoutTest extends OrderTestCase
{
    public function test_cash_on_delivery_order_is_created_with_snapshot_shipping_and_stock(): void
    {
        $buyer = $this->user();
        $region = $this->region(10, 50, 150);
        $a = $this->product(30, 10);
        $b = $this->product(12.5, 5);
        DB::table('carts')->insert(['user_id' => $buyer->id, 'option_id' => $a['option_id'], 'quantity' => 2, 'created_at' => now(), 'updated_at' => now()]);

        $res = $this->checkout($buyer, $this->address($buyer, $region), [$this->cart_line($a, 2), $this->cart_line($b, 1)]);

        $res->assertOk()->assertJsonStructure(['order_id']);
        $order = Order::find($res->json('order_id'));

        $this->assertFalse((bool) $order->is_custom);
        $this->assertSame($buyer->id, $order->user_id);
        $this->assertSame('pending', $order->status);
        $this->assertEquals(0, $order->confirm);
        $this->assertEquals(10, (float) $order->shiping, 'subtotal 72.50 is between min 50 and free 150 → region price');

        $lines = DB::table('order_products')->where('order_id', $order->id)->orderBy('id')->get();
        $this->assertCount(2, $lines);
        $this->assertEquals([30.0, 60.0], [(float) $lines[0]->item_price, (float) $lines[0]->total_price]);
        $this->assertEquals($this->general_wh, $lines[0]->stock_warehouse_id);

        $this->assertSame(8, $this->stock($a['option_id']));
        $this->assertSame(4, $this->stock($b['option_id']));
        $this->assertSame(0, DB::table('carts')->where('user_id', $buyer->id)->count(), 'cart is emptied');
        Notification::assertSentTo($buyer, OrderConfirm::class);

        $totals = OrderService::totals($order);
        $this->assertEquals(72.5, $totals['subtotal']);
        $this->assertEquals(82.5, $totals['total']);
    }

    public function test_free_shipping_at_threshold(): void
    {
        $buyer = $this->user();
        $p = $this->product(75, 10);
        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 2)]);

        $res->assertOk();
        $this->assertEquals(0, (float) Order::find($res->json('order_id'))->shiping);
    }

    public function test_below_region_minimum_is_rejected_and_nothing_is_written(): void
    {
        $buyer = $this->user();
        $p = $this->product(20, 10);
        $before = $this->order_count();

        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 2)]);

        $res->assertStatus(400)->assertJsonPath('code', 'below_min_order');
        $this->assertSame($before, $this->order_count());
        $this->assertSame(10, $this->stock($p['option_id']));
    }

    public function test_client_sent_prices_and_shipping_are_ignored(): void
    {
        $buyer = $this->user();
        $p = $this->product(60, 10);
        $line = $this->cart_line($p, 1);
        $line['option']['price'] = 1;     // tampered
        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$line], 'deliverd payment', ['shiping' => 0]);

        $res->assertOk();
        $order = Order::find($res->json('order_id'));
        $this->assertEquals(60, (float) DB::table('order_products')->where('order_id', $order->id)->value('item_price'));
        $this->assertEquals(10, (float) $order->shiping);
    }

    public function test_out_of_stock_rolls_back_every_line(): void
    {
        $buyer = $this->user();
        $ok = $this->product(40, 10);
        $short = $this->product(40, 1);
        $before = $this->order_count();

        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($ok, 3), $this->cart_line($short, 2)]);

        $res->assertStatus(400)->assertJsonPath('code', 'out_of_stock');
        $this->assertSame($before, $this->order_count());
        $this->assertSame(10, $this->stock($ok['option_id']), 'stock of the first line is given back');
        $this->assertSame(1, $this->stock($short['option_id']));
    }

    public function test_made_to_order_without_stock_is_allowed_and_reserves_nothing(): void
    {
        $buyer = $this->user();
        $p = $this->product(80, 0, 'produced_by_order');

        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 2)]);

        $res->assertOk();
        $line = DB::table('order_products')->where('order_id', $res->json('order_id'))->first();
        $this->assertNull($line->stock_warehouse_id);
        $this->assertSame(0, $this->stock($p['option_id']));
    }

    public function test_made_to_order_with_stock_takes_stock_normally(): void
    {
        $buyer = $this->user();
        $p = $this->product(80, 3, 'produced_by_order');

        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 2)]);

        $res->assertOk();
        $this->assertSame(1, $this->stock($p['option_id']));
    }

    public function test_same_option_twice_is_merged_into_one_line(): void
    {
        $buyer = $this->user();
        $p = $this->product(30, 10);

        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 1), $this->cart_line($p, 2)]);

        $res->assertOk();
        $lines = DB::table('order_products')->where('order_id', $res->json('order_id'))->get();
        $this->assertCount(1, $lines);
        $this->assertSame(3, (int) $lines[0]->quantity);
        $this->assertSame(7, $this->stock($p['option_id']));
    }

    public function test_option_from_another_product_is_rejected(): void
    {
        $buyer = $this->user();
        $a = $this->product(30, 10);
        $b = $this->product(30, 10);
        $line = ['product' => ['id' => $a['product_id']], 'option' => ['id' => $b['option_id']], 'quantity' => 2];

        $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$line])
            ->assertStatus(400)->assertJsonPath('code', 'option_product_mismatch');
    }

    public function test_invalid_quantity_is_rejected(): void
    {
        $buyer = $this->user();
        $p = $this->product(60, 10);
        $address = $this->address($buyer, $this->region(10, 50, 150));

        foreach ([0, -1, 1.5, 'abc'] as $qty) {
            $line = $this->cart_line($p, 1);
            $line['quantity'] = $qty;
            $this->checkout($buyer, $address, [$line])->assertStatus(400)->assertJsonPath('code', 'invalid_quantity');
        }
        $this->assertSame(10, $this->stock($p['option_id']));
    }

    public function test_someone_elses_address_is_rejected(): void
    {
        $buyer = $this->user();
        $other = $this->user();
        $p = $this->product(60, 10);

        $this->checkout($buyer, $this->address($other, $this->region()), [$this->cart_line($p, 1)])
            ->assertStatus(400)->assertJsonPath('code', 'invalid_address');
    }

    public function test_unknown_payment_method_is_rejected(): void
    {
        $buyer = $this->user();
        $p = $this->product(60, 10);

        $this->checkout($buyer, $this->address($buyer, $this->region()), [$this->cart_line($p, 1)], 'free stuff')
            ->assertStatus(400)->assertJsonPath('code', 'invalid_payment');
    }

    public function test_empty_cart_and_guest_are_rejected(): void
    {
        $buyer = $this->user();
        $this->checkout($buyer, $this->address($buyer, $this->region()), [])->assertStatus(400);

        $this->app['auth']->forgetGuards();
        $this->postJson('/api/set_order/create_order', ['order_product_list' => []])->assertUnauthorized();
    }

    public function test_one_time_sale_code_is_applied_once(): void
    {
        $buyer = $this->user();
        $p = $this->product(100, 10);
        $address = $this->address($buyer, $this->region(10, 50, 150));
        $code = $this->sale_code(15);

        $first = $this->checkout($buyer, $address, [$this->cart_line($p, 1)], 'deliverd payment', ['sale_code' => $code]);
        $first->assertOk();
        $this->assertEquals(15, (float) Order::find($first->json('order_id'))->discount);
        $this->assertNotNull(DB::table('sale_codes')->where('code', $code)->value('used_at'));

        $second = $this->checkout($buyer, $address, [$this->cart_line($p, 1)], 'deliverd payment', ['sale_code' => $code]);
        $second->assertOk();
        $this->assertEquals(0, (float) Order::find($second->json('order_id'))->discount, 'used one-time code is ignored');
    }

    public function test_expired_code_is_ignored(): void
    {
        $buyer = $this->user();
        $p = $this->product(100, 10);
        $code = $this->sale_code(20, false, now()->subDay()->toDateTimeString());

        $res = $this->checkout($buyer, $this->address($buyer, $this->region()), [$this->cart_line($p, 1)], 'deliverd payment', ['sale_code' => $code]);
        $this->assertEquals(0, (float) Order::find($res->json('order_id'))->discount);
    }

    public function test_partner_and_sale_code_discounts_do_not_stack(): void
    {
        $buyer = $this->user();
        $this->partner(10, $buyer);
        $p = $this->product(100, 10);
        $address = $this->address($buyer, $this->region(10, 50, 150));

        $res = $this->checkout($buyer, $address, [$this->cart_line($p, 1)], 'deliverd payment', ['sale_code' => $this->sale_code(25)]);
        $this->assertEquals(25, (float) Order::find($res->json('order_id'))->discount, 'bigger one wins');

        $res = $this->checkout($buyer, $address, [$this->cart_line($p, 1)]);
        $order = Order::find($res->json('order_id'));
        $this->assertEquals(10, (float) $order->discount, 'partner discount alone');
        $this->assertEquals(100 - 10 + 10, OrderService::totals($order)['total']);
    }

    public function test_last_unit_is_sold_only_once_across_website_and_manual_orders(): void
    {
        $p = $this->product(60, 1);
        $buyer = $this->user();

        $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 1)])->assertOk();
        $this->manual($this->admin(), [$this->manual_line($p, 1)])->assertStatus(422)->assertJsonPath('code', 'out_of_stock');

        $other = $this->user();
        $this->checkout($other, $this->address($other, $this->region(10, 50, 150)), [$this->cart_line($p, 1)])
            ->assertStatus(400)->assertJsonPath('code', 'out_of_stock');
        $this->assertSame(0, $this->stock($p['option_id']), 'never negative');
    }

    public function test_price_change_after_checkout_does_not_change_the_order(): void
    {
        $buyer = $this->user();
        $p = $this->product(60, 10);
        $res = $this->checkout($buyer, $this->address($buyer, $this->region(10, 50, 150)), [$this->cart_line($p, 1)]);

        DB::table('product_options')->where('id', $p['option_id'])->update(['price' => 999]);

        $this->assertEquals(70, OrderService::totals(Order::find($res->json('order_id')))['total']);
    }
}
