<?php

namespace Tests\Feature\Orders;

use App\Models\Shop\Order;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

/**
 * Staff-created orders: POST custom_order/store (custom-order modal + Quick
 * Sale) and the legacy POST set_order/add_custom_order — all through
 * OrderPlacementService::place_manual.
 */
class ManualOrderTest extends OrderTestCase
{
    public function test_self_pickup_order_from_general_warehouse(): void
    {
        $p = $this->product(45, 10);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 2)], ['email' => 'walkin@example.test', 'phone' => '555 11 22 33']);

        $res->assertCreated();
        $order = Order::find($res->json('order_id'));
        $this->assertTrue((bool) $order->is_custom);
        $this->assertNull($order->user_id);
        $this->assertSame('self_delivery', $order->shiping);
        $this->assertNull($order->delivery_price);
        $this->assertEquals(1, $order->confirm);
        $this->assertSame('pending', $order->status);
        $this->assertEquals($this->general_wh, $order->warehouse_id);

        $line = DB::table('order_products')->where('order_id', $order->id)->first();
        $this->assertEquals(45, (float) $line->item_price, 'manual orders snapshot the price too');
        $this->assertEquals(90, (float) $line->total_price);
        $this->assertEquals($this->general_wh, $line->stock_warehouse_id);
        $this->assertSame(8, $this->stock($p['option_id']));

        $buyer = $order->buyerAddress()->first();
        $this->assertSame('Walk', $buyer->name);
        $this->assertSame('walkin@example.test', $buyer->email);
        $this->assertNull($buyer->address, 'pickup orders store no street address');

        $this->assertEquals(90, OrderService::totals($order)['total']);
    }

    public function test_sale_point_seller_sells_from_own_warehouse_only(): void
    {
        $wh = $this->warehouse();
        $p = $this->product(30, 10, 'online_order', [$wh => 4]);

        $res = $this->manual($this->seller($wh), [$this->manual_line($p, 3)]);

        $res->assertCreated();
        $this->assertSame(1, $this->stock($p['option_id'], $wh));
        $this->assertSame(10, $this->stock($p['option_id']), 'general warehouse untouched');
        $this->assertEquals($wh, Order::find($res->json('order_id'))->warehouse_id);

        $this->manual($this->seller($wh), [$this->manual_line($p, 2)])
            ->assertStatus(422)->assertJsonPath('code', 'out_of_stock');
    }

    public function test_seller_without_warehouse_is_refused(): void
    {
        $p = $this->product(30, 10);
        $before = $this->order_count();

        $this->manual($this->seller(null), [$this->manual_line($p, 1)])->assertStatus(422);
        $this->assertSame($before, $this->order_count());
    }

    public function test_plain_user_cannot_create_manual_orders(): void
    {
        $p = $this->product(30, 10);
        $this->manual($this->user(), [$this->manual_line($p, 1)])->assertForbidden();
    }

    public function test_option_not_stocked_in_warehouse_is_out_of_stock(): void
    {
        $wh = $this->warehouse();
        $p = $this->product(30, 10);   // nothing in $wh

        $this->manual($this->seller($wh), [$this->manual_line($p, 1)])
            ->assertStatus(422)->assertJsonPath('code', 'out_of_stock');
    }

    public function test_production_task_skips_stock(): void
    {
        $p = $this->product(30, 0);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 5)], ['create_production_task' => true]);

        $res->assertCreated();
        $this->assertSame(0, $this->stock($p['option_id']));
        $this->assertNull(DB::table('order_products')->where('order_id', $res->json('order_id'))->value('stock_warehouse_id'));
    }

    public function test_out_of_stock_rolls_back_everything(): void
    {
        $ok = $this->product(30, 10);
        $short = $this->product(30, 1);
        $before = [$this->order_count(), DB::table('custom_order_addresses')->count()];

        $this->manual($this->admin(), [$this->manual_line($ok, 2), $this->manual_line($short, 3)])
            ->assertStatus(422)->assertJsonPath('code', 'out_of_stock');

        $this->assertSame($before, [$this->order_count(), DB::table('custom_order_addresses')->count()]);
        $this->assertSame(10, $this->stock($ok['option_id']));
    }

    // ───── delivery & region rule ─────

    public function test_delivery_uses_region_price(): void
    {
        $region = $this->region(10, 50, 150);
        $p = $this->product(60, 10);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 1)], [
            'delivery_type' => 'delivery', 'region_id' => $region, 'address' => 'Chavchavadze 5', 'city' => 'Tbilisi',
        ]);

        $res->assertCreated();
        $order = Order::find($res->json('order_id'));
        $this->assertSame('delivery', $order->shiping);
        $this->assertEquals(10, (float) $order->delivery_price);
        $this->assertSame('Chavchavadze 5', $order->buyerAddress()->first()->address);
        $this->assertEquals(70, OrderService::totals($order)['total']);
        $this->assertEquals(10, OrderService::delivery($order)['cost']);
    }

    public function test_delivery_is_free_above_threshold(): void
    {
        $region = $this->region(10, 50, 150);
        $p = $this->product(80, 10);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 2)], ['delivery_type' => 'delivery', 'region_id' => $region]);

        $this->assertEquals(0, (float) Order::find($res->json('order_id'))->delivery_price);
    }

    public function test_delivery_below_minimum_is_rejected(): void
    {
        $region = $this->region(10, 50, 150);
        $p = $this->product(20, 10);
        $before = $this->order_count();

        $this->manual($this->admin(), [$this->manual_line($p, 1)], ['delivery_type' => 'delivery', 'region_id' => $region])
            ->assertStatus(422)->assertJsonPath('code', 'below_min_order');

        $this->assertSame($before, $this->order_count());
        $this->assertSame(10, $this->stock($p['option_id']));
    }

    public function test_minimum_also_applies_with_manual_delivery_price(): void
    {
        $region = $this->region(10, 50, 150);
        $p = $this->product(20, 10);

        $this->manual($this->admin(), [$this->manual_line($p, 1)], [
            'delivery_type' => 'delivery', 'region_id' => $region, 'delivery_price' => 3, 'delivery_price_manual' => true,
        ])->assertStatus(422);
    }

    public function test_manual_delivery_price_only_counts_when_flagged(): void
    {
        $region = $this->region(10, 50, 150);
        $p = $this->product(60, 10);

        $auto = $this->manual($this->admin(), [$this->manual_line($p, 1)], ['delivery_type' => 'delivery', 'region_id' => $region, 'delivery_price' => 3]);
        $this->assertEquals(10, (float) Order::find($auto->json('order_id'))->delivery_price, 'unflagged price is ignored');

        $manual = $this->manual($this->admin(), [$this->manual_line($p, 1)], ['delivery_type' => 'delivery', 'region_id' => $region, 'delivery_price' => 3, 'delivery_price_manual' => true]);
        $this->assertEquals(3, (float) Order::find($manual->json('order_id'))->delivery_price);
    }

    public function test_delivery_requires_region(): void
    {
        $p = $this->product(60, 10);
        $this->manual($this->admin(), [$this->manual_line($p, 1)], ['delivery_type' => 'delivery'])->assertStatus(422);
    }

    public function test_pay_now_is_always_pickup_and_complete(): void
    {
        $region = $this->region(10, 50, 150);
        $p = $this->product(60, 10);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 1)], [
            'payment_type' => 'online_payment', 'delivery_type' => 'delivery', 'region_id' => $region,
        ]);

        $res->assertCreated();
        $order = Order::find($res->json('order_id'));
        $this->assertSame('self_delivery', $order->shiping);
        $this->assertNull($order->delivery_price);
        $this->assertSame('Order Complete', $order->status);
    }

    public function test_invalid_payment_or_delivery_values_are_rejected(): void
    {
        $p = $this->product(60, 10);
        $this->manual($this->admin(), [$this->manual_line($p, 1)], ['payment_type' => 'bitcoin'])->assertStatus(422);
        $this->manual($this->admin(), [$this->manual_line($p, 1)], ['delivery_type' => 'drone'])->assertStatus(422);
    }

    public function test_option_from_another_product_is_rejected(): void
    {
        $a = $this->product(60, 10);
        $b = $this->product(60, 10);

        $this->manual($this->admin(), [['product_id' => $a['product_id'], 'product_option_id' => $b['option_id'], 'quantity' => 1]])
            ->assertStatus(422)->assertJsonPath('code', 'option_product_mismatch');
    }

    // ───── discounts ─────

    public function test_partner_discount_by_buyer_contact(): void
    {
        $this->partner(20, null, ['email' => 'club@example.test']);
        $p = $this->product(100, 10);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 1)], ['email' => 'club@example.test']);

        $order = Order::find($res->json('order_id'));
        $this->assertEquals(20, (float) $order->discount);
        $this->assertEquals(80, OrderService::totals($order)['total']);
        $res->assertJsonPath('partner_discount.discount', 20);
    }

    public function test_partner_discount_from_linked_account(): void
    {
        $member = $this->user();
        $this->partner(12, $member);
        $p = $this->product(100, 10);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 1)], ['name' => 'Other', 'surname' => 'Name', 'linked_user_id' => $member->id]);

        $this->assertEquals(12, (float) Order::find($res->json('order_id'))->discount);
    }

    // ───── account linking ─────

    public function test_picked_account_is_linked(): void
    {
        $customer = $this->user();
        $p = $this->product(30, 10);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 1)], ['linked_user_id' => $customer->id]);

        $this->assertEquals([$customer->id], Order::find($res->json('order_id'))->relatedUsers()->pluck('users.id')->all());
        $res->assertJsonPath('matched_users.0.id', $customer->id);
    }

    public function test_unknown_picked_account_is_rejected(): void
    {
        $p = $this->product(30, 10);
        $this->manual($this->admin(), [$this->manual_line($p, 1)], ['linked_user_id' => 999999999])->assertStatus(422);
    }

    public function test_exact_email_match_is_linked_automatically(): void
    {
        $customer = $this->user();
        $p = $this->product(30, 10);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 1)], ['email' => $customer->email]);

        $this->assertEquals([$customer->id], Order::find($res->json('order_id'))->relatedUsers()->pluck('users.id')->all());
    }

    public function test_unique_phone_match_is_linked_whatever_the_formatting(): void
    {
        $customer = $this->user(['phone_number' => '+995 599 12 34 56']);
        $p = $this->product(30, 10);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 1)], ['phone' => '599-123-456']);

        $this->assertEquals([$customer->id], Order::find($res->json('order_id'))->relatedUsers()->pluck('users.id')->all());
    }

    public function test_name_only_match_is_not_linked(): void
    {
        $namesake = $this->user(['name' => 'Giorgi', 'surname' => 'Namesake' . uniqid()]);
        $p = $this->product(30, 10);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 1)], ['name' => $namesake->name, 'surname' => $namesake->surname]);

        $this->assertSame(0, Order::find($res->json('order_id'))->relatedUsers()->count());
    }

    public function test_picked_and_email_accounts_are_both_linked_once(): void
    {
        $picked = $this->user();
        $p = $this->product(30, 10);

        $res = $this->manual($this->admin(), [$this->manual_line($p, 1)], ['linked_user_id' => $picked->id, 'email' => $picked->email]);

        $this->assertSame(1, Order::find($res->json('order_id'))->relatedUsers()->count());
    }

    // ───── legacy endpoint ─────

    public function test_legacy_add_custom_order_endpoint_works_again(): void
    {
        $admin = $this->admin();
        $p = $this->product(30, 10);
        Sanctum::actingAs($admin);

        $res = $this->postJson('/api/set_order/add_custom_order', [
            'delivery_type' => 'self_delivery', 'payment_type' => 'deliverd_payment',
            'order_product_list' => [$this->manual_line($p, 2)],
        ]);

        $res->assertOk()->assertJsonStructure(['order_id']);
        $order = Order::find($res->json('order_id'));
        $this->assertTrue((bool) $order->is_custom);
        $this->assertSame($admin->name, $order->buyerAddress()->first()->name);
        $this->assertSame(8, $this->stock($p['option_id']));
    }
}
