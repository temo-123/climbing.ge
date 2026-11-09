<?php

namespace Tests\Feature\Orders;

use App\Models\Shop\Order;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

/**
 * POST set_order/checkout_quote — what both checkout steps display. It must
 * match what create_order then charges, and must never write anything.
 */
class CheckoutQuoteTest extends OrderTestCase
{
    private function quote($buyer, array $lines, ?int $address = null, ?string $code = null, string $locale = 'en')
    {
        Sanctum::actingAs($buyer);
        return $this->postJson('/api/set_order/checkout_quote', [
            'order_product_list' => $lines, 'adres' => $address, 'sale_code' => $code, 'locale' => $locale,
        ])->assertOk();
    }

    public function test_quote_equals_the_order_that_is_then_placed(): void
    {
        $buyer = $this->user();
        $this->partner(10, $buyer);
        $a = $this->product(30, 10);
        $b = $this->product(12.5, 10);
        $address = $this->address($buyer, $this->region(10, 50, 150));
        $lines = [$this->cart_line($a, 2), $this->cart_line($b, 1)];

        $q = $this->quote($buyer, $lines, $address);
        $this->assertTrue($q->json('can_place'));
        $this->assertEquals(72.5, $q->json('subtotal'));
        $this->assertEquals(10, $q->json('discount.percent'));
        $this->assertSame('partner', $q->json('discount.source'));
        $this->assertEquals(10, $q->json('shipping'));
        $this->assertEquals(75.25, $q->json('total'));   // 72.50 − 7.25 + 10

        $order = Order::find($this->checkout($buyer, $address, $lines)->assertOk()->json('order_id'));
        $this->assertEquals($q->json('total'), OrderService::totals($order)['total']);
    }

    public function test_quote_writes_nothing(): void
    {
        $buyer = $this->user();
        $p = $this->product(60, 3);
        $code = $this->sale_code(20);
        $before = $this->order_count();

        $this->quote($buyer, [$this->cart_line($p, 2)], $this->address($buyer, $this->region()), $code);

        $this->assertSame($before, $this->order_count());
        $this->assertSame(3, $this->stock($p['option_id']));
        $this->assertNull(DB::table('sale_codes')->where('code', $code)->value('used_at'), 'one-time code is not used up by a preview');
    }

    public function test_sale_code_states(): void
    {
        $buyer = $this->user();
        $p = $this->product(100, 10);
        $address = $this->address($buyer, $this->region(10, 50, 150));

        $this->quote($buyer, [$this->cart_line($p, 1)], $address, $this->sale_code(15))
            ->assertJsonPath('discount.code.status', 'applied')->assertJsonPath('discount.source', 'code');

        $this->quote($buyer, [$this->cart_line($p, 1)], $address, 'NOPE-' . uniqid())
            ->assertJsonPath('discount.code.status', 'invalid')->assertJsonPath('discount.percent', 0);

        $this->partner(25, $buyer);
        $this->quote($buyer, [$this->cart_line($p, 1)], $address, $this->sale_code(15))
            ->assertJsonPath('discount.code.status', 'not_better')->assertJsonPath('discount.source', 'partner');
    }

    public function test_sale_code_from_the_review_step_reaches_the_order(): void
    {
        // The review page sends ?code= as sale_code — this is what used to be lost.
        $buyer = $this->user();
        $p = $this->product(100, 10);
        $code = $this->sale_code(15);

        $order_id = $this->checkout($buyer, $this->address($buyer, $this->region()), [$this->cart_line($p, 1)], 'deliverd payment', ['sale_code' => $code])->json('order_id');

        $this->assertEquals(15, (float) Order::find($order_id)->discount);
    }

    public function test_region_rule_and_free_shipping_progress(): void
    {
        $buyer = $this->user();
        $p = $this->product(20, 10);
        $address = $this->address($buyer, $this->region(10, 50, 150));

        $low = $this->quote($buyer, [$this->cart_line($p, 2)], $address);
        $this->assertFalse($low->json('can_place'));
        $this->assertTrue($low->json('region.below_min'));
        $this->assertEquals(10, $low->json('region.missing_for_min'));
        $this->assertContains('below_min_order', collect($low->json('issues'))->pluck('code'));

        $mid = $this->quote($buyer, [$this->cart_line($p, 3)], $address);
        $this->assertTrue($mid->json('can_place'));
        $this->assertEquals(90, $mid->json('region.missing_for_free'));

        $free = $this->quote($buyer, [$this->cart_line($p, 8)], $address);
        $this->assertTrue($free->json('region.is_free'));
        $this->assertEquals(0, $free->json('shipping'));
    }

    public function test_problems_are_reported_not_thrown(): void
    {
        $buyer = $this->user();
        $other = $this->user();
        $p = $this->product(60, 1);
        $made = $this->product(60, 0, 'produced_by_order');

        $q = $this->quote($buyer, [$this->cart_line($p, 3), $this->cart_line($made, 2)]);
        $codes = collect($q->json('issues'))->pluck('code');
        $this->assertContains('out_of_stock', $codes);
        $this->assertContains('no_address', $codes);
        $this->assertNull($q->json('shipping'));
        $this->assertTrue($q->json('lines.1.stock_ok'), 'made-to-order without stock is fine');
        $this->assertNull($q->json('lines.1.available'));

        $foreign = $this->quote($buyer, [$this->cart_line($p, 1)], $this->address($other, $this->region()));
        $this->assertContains('invalid_address', collect($foreign->json('issues'))->pluck('code'));

        $this->quote($buyer, [])->assertJsonPath('can_place', false)->assertJsonPath('issues.0.code', 'empty');
    }

    public function test_localized_titles(): void
    {
        $buyer = $this->user();
        $p = $this->product(60, 10);

        $this->assertStringStartsWith('KA ', $this->quote($buyer, [$this->cart_line($p, 1)], null, null, 'ka')->json('lines.0.title'));
        $this->assertStringStartsWith('EN ', $this->quote($buyer, [$this->cart_line($p, 1)])->json('lines.0.title'));
    }

    public function test_guest_gets_401(): void
    {
        $this->postJson('/api/set_order/checkout_quote', [])->assertUnauthorized();
    }
}
