<?php

namespace Tests\Feature\Orders;

use App\Models\Shop\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Fixtures for the order test suite. Every test runs in a transaction that
 * is rolled back (DatabaseTransactions), so the products, stock, regions and
 * users created here never reach the dev database.
 */
abstract class OrderTestCase extends TestCase
{
    use DatabaseTransactions;

    protected int $general_wh;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();

        $this->general_wh = DB::table('warehouses')->where('general', 1)->value('id')
            ?? DB::table('warehouses')->insertGetId(['name' => 'T general', 'general' => 1, 'is_sale_point' => 0, 'created_at' => now(), 'updated_at' => now()]);
    }

    // ───────────── builders ─────────────

    /** @return array{product_id:int, option_id:int} */
    protected function product(float $price, int $general_stock = 10, string $sale_type = 'online_order', array $stock_by_warehouse = [], float $discount = 0): array
    {
        $tag = 'test-' . Str::random(8);
        $us = DB::table('locale_products')->insertGetId(['locale' => 'us', 'title' => 'EN ' . $tag, 'created_at' => now(), 'updated_at' => now()]);
        $ka = DB::table('locale_products')->insertGetId(['locale' => 'ka', 'title' => 'KA ' . $tag, 'created_at' => now(), 'updated_at' => now()]);
        $product_id = DB::table('products')->insertGetId([
            'url_title' => $tag, 'published' => 1, 'sale_type' => $sale_type,
            'us_product_id' => $us, 'ka_product_id' => $ka, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $option_id = DB::table('product_options')->insertGetId([
            'name' => 'Opt ' . $tag, 'price' => $price, 'discount' => $discount, 'currency' => '₾', 'product_id' => $product_id, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $stock_by_warehouse = [$this->general_wh => $general_stock] + $stock_by_warehouse;
        foreach ($stock_by_warehouse as $wh => $qty) {
            if ($qty === null) continue;
            DB::table('warehouses_product_options')->insert(['warehouse_id' => $wh, 'product_option_id' => $option_id, 'quantity' => $qty, 'created_at' => now(), 'updated_at' => now()]);
        }

        return ['product_id' => $product_id, 'option_id' => $option_id];
    }

    protected function stock(int $option_id, ?int $warehouse_id = null): ?int
    {
        $q = DB::table('warehouses_product_options')->where('product_option_id', $option_id)->where('warehouse_id', $warehouse_id ?? $this->general_wh)->value('quantity');
        return $q === null ? null : (int) $q;
    }

    protected function region(float $price = 10, ?float $min = 50, ?float $free_after = 150): int
    {
        return DB::table('shiped_regions')->insertGetId([
            'region' => 'TestRegion ' . Str::random(5), 'shiping_price' => $price, 'ship_min_price' => $min,
            'free_shiping_price_after' => $free_after, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    protected function user(array $attrs = []): User
    {
        $id = DB::table('users')->insertGetId(array_merge([
            'name' => 'Test', 'surname' => 'Buyer' . Str::random(4),
            'email' => 'order-test-' . Str::random(10) . '@example.test',
            'password' => bcrypt('secret'), 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now(),
        ], $attrs));
        return User::find($id);
    }

    protected function admin(): User
    {
        $u = $this->user(['name' => 'Admin', 'surname' => 'Tester']);
        DB::table('user_role')->insert(['user_id' => $u->id, 'role_id' => DB::table('roles')->where('slug', 'admin')->value('id'), 'created_at' => now(), 'updated_at' => now()]);
        return $u->fresh();
    }

    /** A sale-point seller: only warehouse › sell_own, assigned to $warehouse_id (or none). */
    protected function seller(?int $warehouse_id): User
    {
        $u = $this->user(['name' => 'Seller', 'surname' => 'Tester']);
        DB::table('user_permissions')->insert([
            'user_id' => $u->id,
            'permission_id' => DB::table('permissions')->where('subject', 'warehouse')->where('action', 'sell_own')->value('id'),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        if ($warehouse_id) {
            DB::table('warehouse_user')->insert(['user_id' => $u->id, 'warehouse_id' => $warehouse_id, 'created_at' => now(), 'updated_at' => now()]);
        }
        return $u->fresh();
    }

    protected function warehouse(string $name = 'Sale point'): int
    {
        return DB::table('warehouses')->insertGetId(['name' => $name . ' ' . Str::random(4), 'general' => 0, 'is_sale_point' => 1, 'created_at' => now(), 'updated_at' => now()]);
    }

    protected function address(User $user, int $region_id): int
    {
        return DB::table('user_adreses')->insertGetId([
            'name' => 'Home', 'city' => 'Tbilisi', 'strit' => 'Rustaveli', 'number' => '12', 'floor' => '3', 'flat' => '7',
            'region_id' => $region_id, 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    protected function partner(float $discount, ?User $member_user = null, array $contact = []): int
    {
        $org = DB::table('partner_organizations')->insertGetId(['name' => 'Org ' . Str::random(4), 'discount' => $discount, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('partner_organization_members')->insert(array_merge([
            'organization_id' => $org,
            'name' => $member_user->name ?? 'Partner', 'surname' => $member_user->surname ?? 'Member',
            'email' => $member_user->email ?? ('partner-' . Str::random(8) . '@example.test'),
            'user_id' => $member_user->id ?? null, 'created_at' => now(), 'updated_at' => now(),
        ], $contact));
        return $org;
    }

    protected function sale_code(float $discount, bool $one_time = true, ?string $expires = null, ?string $used_at = null): string
    {
        $code = 'T' . strtoupper(Str::random(8));
        DB::table('sale_codes')->insert(['code' => $code, 'one_time_code' => $one_time, 'discount' => $discount, 'action_data' => $expires, 'used_at' => $used_at, 'created_at' => now(), 'updated_at' => now()]);
        return $code;
    }

    // ───────────── request helpers ─────────────

    /** Checkout payload line, in the cart's own shape. */
    protected function cart_line(array $p, int $qty): array
    {
        return ['product' => ['id' => $p['product_id']], 'option' => ['id' => $p['option_id'], 'name' => 'x'], 'quantity' => $qty];
    }

    protected function checkout(User $buyer, int $address_id, array $lines, string $payment = 'deliverd payment', array $extra = [])
    {
        Sanctum::actingAs($buyer);
        return $this->postJson('/api/set_order/create_order', array_merge([
            'order_product_list' => $lines, 'payment_tupe' => $payment, 'adres' => $address_id,
        ], $extra));
    }

    protected function manual_line(array $p, int $qty): array
    {
        return ['product_id' => $p['product_id'], 'product_option_id' => $p['option_id'], 'quantity' => $qty];
    }

    protected function manual(User $staff, array $lines, array $fields = [])
    {
        Sanctum::actingAs($staff);
        return $this->postJson('/api/custom_order/store', array_merge([
            'name' => 'Walk', 'surname' => 'In', 'delivery_type' => 'self_delivery', 'payment_type' => 'deliverd_payment',
            'order_product_list' => $lines,
        ], $fields));
    }

    protected function latest_order(): ?Order
    {
        return Order::latest('id')->first();
    }

    protected function order_count(): int
    {
        return DB::table('orders')->count();
    }
}
