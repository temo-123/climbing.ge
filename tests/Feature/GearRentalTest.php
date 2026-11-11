<?php

namespace Tests\Feature;

use App\Models\Shop\Rental\Rental;
use App\Models\User;
use App\Services\GearRentalService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Gear rental: period prices, warehouses, stock / availability, rentals with
 * ID card photo, return / cancel, user linking, permissions, shop service link.
 * docs/FUNCTIONS_DOCS/shop/gear_rental.md
 */
class GearRentalTest extends TestCase
{
    use DatabaseTransactions;

    private const H = ['X-Requested-With' => 'XMLHttpRequest', 'Accept' => 'application/json'];

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    private function user(array $attrs = []): User
    {
        $id = DB::table('users')->insertGetId(array_merge([
            'name' => 'Rent', 'surname' => 'T' . Str::random(4),
            'email' => 'rent-test-' . Str::random(10) . '@example.test',
            'password' => bcrypt('secret'), 'email_verified_at' => now(),
            'created_at' => now(), 'updated_at' => now(),
        ], $attrs));
        return User::find($id);
    }

    private function admin(): User
    {
        $u = $this->user();
        DB::table('user_role')->insert(['user_id' => $u->id, 'role_id' => DB::table('roles')->where('slug', 'admin')->value('id'), 'created_at' => now(), 'updated_at' => now()]);
        Sanctum::actingAs($u);
        return $u;
    }

    private function warehouse(array $data = []): int
    {
        return $this->postJson('api/set_gear_rental/add_warehouse', array_merge(['name' => 'WH ' . Str::random(5)], $data), self::H)
            ->assertCreated()->json('id');
    }

    private function gear(int $warehouse_id, int $qty = 5, array $prices = [[1, 20], [2, 35], [7, 100]], array $data = []): array
    {
        return $this->postJson('api/set_gear_rental/add_gear', array_merge([
            'us_name' => 'Harness ' . Str::random(4),
            'ka_name' => 'სისტემა',
            'deposit' => 50,
            'show_in_shop' => true,
            'prices' => array_map(fn ($p) => ['days' => $p[0], 'price' => $p[1]], $prices),
            'stock' => [['warehouse_id' => $warehouse_id, 'quantity' => $qty]],
        ], $data), self::H)->assertCreated()->json();
    }

    private function rent(int $warehouse_id, array $items, array $data = [], ?UploadedFile $card = null)
    {
        $payload = ['data' => json_encode(array_merge([
            'rental_warehouse_id' => $warehouse_id,
            'name' => 'Nino', 'surname' => 'Beridze', 'phone' => '555123456',
            'start_at' => now()->format('Y-m-d H:i:s'), 'days' => 2,
            'items' => $items,
        ], $data))];
        if ($card) $payload['id_card'] = $card;
        return $this->post('api/set_gear_rental/add_rental', $payload, self::H);
    }

    // ───────────── prices ─────────────

    public function test_period_price_rules(): void
    {
        $p = [1 => 20, 2 => 35, 7 => 100];
        $this->assertSame(20.0, GearRentalService::price_for($p, 1));
        $this->assertSame(35.0, GearRentalService::price_for($p, 2), 'exact period');
        $this->assertSame(75.0, GearRentalService::price_for($p, 4), '2 days + 2 extra days at the 1-day price');
        $this->assertSame(100.0, GearRentalService::price_for($p, 7));
        $this->assertSame(140.0, GearRentalService::price_for($p, 9), '7 days + 2 × 20');
        $this->assertSame(60.0, GearRentalService::price_for([3 => 45], 4), 'no 1-day price: 45/3 per extra day');
        $this->assertSame(45.0, GearRentalService::price_for([3 => 45], 1), 'shorter than every period: minimum charge');
        $this->assertNull(GearRentalService::price_for([], 3));
    }

    // ───────────── warehouses ─────────────

    public function test_only_one_general_warehouse_and_the_first_is_general(): void
    {
        $this->admin();
        DB::table('rental_warehouses')->delete();

        $a = $this->warehouse();
        $this->assertTrue((bool) DB::table('rental_warehouses')->where('id', $a)->value('general'), 'first warehouse becomes general');

        $b = $this->warehouse(['general' => true]);
        $this->assertFalse((bool) DB::table('rental_warehouses')->where('id', $a)->value('general'));
        $this->assertTrue((bool) DB::table('rental_warehouses')->where('id', $b)->value('general'));

        // deleting the general one passes the flag on
        $this->deleteJson('api/set_gear_rental/del_warehouse/' . $b, [], self::H)->assertOk();
        $this->assertTrue((bool) DB::table('rental_warehouses')->where('id', $a)->value('general'));
    }

    // ───────────── rentals ─────────────

    public function test_rental_prices_stock_user_link_and_return(): void
    {
        $admin = $this->admin();
        $wh = $this->warehouse();
        $g = $this->gear($wh, 3);
        $renter = $this->user(['email' => 'renter-' . Str::random(6) . '@example.test']);

        $res = $this->rent($wh, [['gear_id' => $g['id'], 'quantity' => 2]], ['email' => $renter->email, 'days' => 4, 'discount' => 10, 'deposit' => 100])
            ->assertCreated();

        $this->assertEquals(75, $res->json('items.0.unit_price'), '4 days = 35 + 2 × 20');
        $this->assertEquals(150, $res->json('subtotal'));
        $this->assertEquals(140, $res->json('total'));
        $this->assertSame($renter->id, $res->json('user_id'), 'linked to the account with the same e-mail');
        $this->assertSame($admin->id, (int) Rental::find($res->json('id'))->created_by);
        $this->assertSame('2× ' . $g['us_name'], $res->json('gear_summary'));

        // gear list shows it rented
        $row = collect($this->getJson('api/set_gear_rental/gear', self::H)->assertOk()->json())->firstWhere('id', $g['id']);
        $this->assertSame(2, $row['rented']);
        $this->assertSame(1, $row['available']);
        $this->assertTrue($row['is_rented']);
        $this->assertSame('Nino Beridze', $row['renters'][0]['name']);

        // only 1 left → renting 2 more is refused
        $this->rent($wh, [['gear_id' => $g['id'], 'quantity' => 2]])->assertStatus(422)->assertJsonValidationErrors('items.0.quantity');

        // stock can't go below what is out; gear out can't be deleted
        $this->postJson('api/set_gear_rental/edit_gear/' . $g['id'], [
            'us_name' => $g['us_name'], 'stock' => [['warehouse_id' => $wh, 'quantity' => 1]],
        ], self::H)->assertStatus(422);
        $this->deleteJson('api/set_gear_rental/del_gear/' . $g['id'], [], self::H)->assertStatus(422);

        // return frees the stock
        $id = $res->json('id');
        $this->postJson('api/set_gear_rental/return_rental/' . $id, ['paid' => true, 'deposit_returned' => true], self::H)
            ->assertOk()->assertJsonPath('status', 'returned')->assertJsonPath('is_paid', true);
        $row = collect($this->getJson('api/set_gear_rental/gear', self::H)->json())->firstWhere('id', $g['id']);
        $this->assertSame(3, $row['available']);
        $this->assertNotContains($id, collect($this->getJson('api/set_gear_rental/rentals?scope=active', self::H)->json())->pluck('id')->all());
        $this->assertContains($id, collect($this->getJson('api/set_gear_rental/rentals?scope=history', self::H)->json())->pluck('id')->all());
    }

    public function test_editing_a_rental_counts_its_own_pieces_as_free_and_price_override_wins(): void
    {
        $this->admin();
        $wh = $this->warehouse();
        $g = $this->gear($wh, 2);

        $id = $this->rent($wh, [['gear_id' => $g['id'], 'quantity' => 2]])->assertCreated()->json('id');

        // same 2 pieces, other period and a hand-typed price
        $res = $this->post('api/set_gear_rental/edit_rental/' . $id, ['data' => json_encode([
            'rental_warehouse_id' => $wh, 'name' => 'Nino', 'surname' => 'Beridze', 'phone' => '555',
            'start_at' => now()->format('Y-m-d H:i:s'), 'days' => 7,
            'items' => [['gear_id' => $g['id'], 'quantity' => 2, 'unit_price' => 80]],
        ])], self::H)->assertOk();

        $this->assertEquals(80, $res->json('items.0.unit_price'));
        $this->assertEquals(160, $res->json('total'));
        $this->assertSame(7, $res->json('days'));
    }

    public function test_paid_for_the_rental_period_when_creating_and_editing(): void
    {
        $this->admin();
        $wh = $this->warehouse();
        $g = $this->gear($wh, 3);
        $base = ['rental_warehouse_id' => $wh, 'name' => 'Nino', 'surname' => 'Beridze', 'phone' => '555',
            'start_at' => now()->format('Y-m-d H:i:s'), 'days' => 2, 'items' => [['gear_id' => $g['id'], 'quantity' => 1]]];

        $res = $this->rent($wh, $base['items'], ['paid' => true])->assertCreated()->assertJsonPath('is_paid', true);
        $id = $res->json('id');
        $paid_at = Rental::find($id)->paid_at;
        $this->assertNotNull($paid_at);

        // edit without the flag keeps it, and the first payment time stays
        $this->travel(5)->minutes();
        $this->post('api/set_gear_rental/edit_rental/' . $id, ['data' => json_encode($base)], self::H)->assertOk()->assertJsonPath('is_paid', true);
        $this->post('api/set_gear_rental/edit_rental/' . $id, ['data' => json_encode($base + ['paid' => true])], self::H)->assertOk();
        $this->assertEquals($paid_at, Rental::find($id)->paid_at);

        // unticked → not paid
        $this->post('api/set_gear_rental/edit_rental/' . $id, ['data' => json_encode($base + ['paid' => false])], self::H)->assertOk()->assertJsonPath('is_paid', false);
        $this->assertNull(Rental::find($id)->paid_at);

        // default: not paid
        $this->rent($wh, $base['items'])->assertCreated()->assertJsonPath('is_paid', false);
    }

    public function test_gear_without_price_for_the_period_needs_a_typed_price(): void
    {
        $this->admin();
        $wh = $this->warehouse();
        $g = $this->gear($wh, 2, []);

        $this->rent($wh, [['gear_id' => $g['id'], 'quantity' => 1]])->assertStatus(422)->assertJsonValidationErrors('items.0.unit_price');
        $this->rent($wh, [['gear_id' => $g['id'], 'quantity' => 1, 'unit_price' => 15]])->assertCreated()->assertJsonPath('total', 15);
    }

    public function test_cancel_frees_the_stock(): void
    {
        $this->admin();
        $wh = $this->warehouse();
        $g = $this->gear($wh, 1);
        $id = $this->rent($wh, [['gear_id' => $g['id'], 'quantity' => 1]])->assertCreated()->json('id');

        $this->rent($wh, [['gear_id' => $g['id'], 'quantity' => 1]])->assertStatus(422);
        $this->postJson('api/set_gear_rental/cancel_rental/' . $id, [], self::H)->assertOk()->assertJsonPath('status', 'cancelled');
        $this->rent($wh, [['gear_id' => $g['id'], 'quantity' => 1]])->assertCreated();
    }

    public function test_overdue_shows_in_summary(): void
    {
        $this->admin();
        $wh = $this->warehouse();
        $g = $this->gear($wh, 5);

        $before = $this->getJson('api/set_gear_rental/summary', self::H)->json('overdue');
        $res = $this->rent($wh, [['gear_id' => $g['id'], 'quantity' => 1]], ['start_at' => now()->subDays(5)->format('Y-m-d H:i:s'), 'days' => 2])->assertCreated();

        $this->assertTrue($res->json('is_overdue'));
        $this->assertSame($before + 1, $this->getJson('api/set_gear_rental/summary', self::H)->json('overdue'));
    }

    // ───────────── ID card (private) ─────────────

    public function test_id_card_is_private_and_deleted_with_the_rental(): void
    {
        $this->admin();
        $wh = $this->warehouse();
        $g = $this->gear($wh, 2);

        $res = $this->rent($wh, [['gear_id' => $g['id'], 'quantity' => 1]], [], UploadedFile::fake()->image('card.jpg', 800, 500))
            ->assertCreated()->assertJsonPath('has_id_card', true);
        $this->assertArrayNotHasKey('id_card_image', $res->json(), 'the storage path never goes to the browser');

        $id = $res->json('id');
        $path = Rental::find($id)->getRawOriginal('id_card_image');
        $this->assertStringStartsWith('rental_id_cards/', $path);
        Storage::disk('local')->assertExists($path);
        $this->assertFileDoesNotExist(public_path($path));

        $this->get('api/set_gear_rental/id_card/' . $id, self::H)->assertOk()->assertHeader('Cache-Control', 'no-store, private');

        // a plain user can't see it
        $this->app['auth']->forgetGuards();
        Sanctum::actingAs($this->user());
        $this->get('api/set_gear_rental/id_card/' . $id, self::H)->assertStatus(403);

        $this->app['auth']->forgetGuards();
        $this->admin();
        $this->deleteJson('api/set_gear_rental/del_rental/' . $id, [], self::H)->assertOk();
        Storage::disk('local')->assertMissing($path);
    }

    public function test_plain_users_get_no_access(): void
    {
        Sanctum::actingAs($this->user());
        $this->getJson('api/set_gear_rental/rentals', self::H)->assertStatus(403);
        $this->getJson('api/set_gear_rental/gear', self::H)->assertStatus(403);
        $this->postJson('api/set_gear_rental/add_warehouse', ['name' => 'x'], self::H)->assertStatus(403);
        $this->getJson('api/set_gear_rental/search_users?q=ni', self::H)->assertStatus(403);
    }

    public function test_user_search_returns_only_contact_fields(): void
    {
        $this->admin();
        $u = $this->user(['name' => 'Zurabexample', 'phone_number' => '599000111']);

        $hit = collect($this->getJson('api/set_gear_rental/search_users?q=Zurabex', self::H)->assertOk()->json())->firstWhere('id', $u->id);
        $this->assertNotNull($hit);
        $this->assertSame(['id', 'name', 'surname', 'email', 'phone_number', 'image'], array_keys($hit));
    }

    // ───────────── shop service link ─────────────

    public function test_linked_service_page_shows_the_rental_price_list(): void
    {
        $this->admin();
        $wh = $this->warehouse(['general' => true]);
        $shown = $this->gear($wh, 4);
        $this->gear($wh, 4, [[1, 10]], ['show_in_shop' => false]);   // hidden
        $this->gear($wh, 0);                                          // no stock here → not listed

        $us = DB::table('locale_services')->insertGetId(['locale' => 'us', 'title' => 'Rental', 'short_description' => 's', 'text' => 't', 'created_at' => now(), 'updated_at' => now()]);
        $ka = DB::table('locale_services')->insertGetId(['locale' => 'ka', 'title' => 'გაქირავება', 'short_description' => 's', 'text' => 't', 'created_at' => now(), 'updated_at' => now()]);
        $slug = 'rental-test-' . Str::lower(Str::random(6));
        DB::table('services')->insert(['url_title' => $slug, 'published' => 1, 'us_service_id' => $us, 'ka_service_id' => $ka, 'link_type' => 'gear_rental', 'link_id' => null, 'created_at' => now(), 'updated_at' => now()]);

        // the service form gets the warehouses to pick from
        $this->assertContains($wh, collect($this->getJson('api/set_service/get_link_targets', self::H)->assertOk()->json('types.0.targets'))->pluck('id')->all());

        $this->app['auth']->forgetGuards();
        $linked = $this->getJson('api/get_service/get_local_service_in_page/ka/' . $slug, self::H)->assertOk()->json('linked');

        $this->assertSame('gear_rental', $linked['type']);
        $this->assertSame([1, 2, 7], $linked['periods']);
        $this->assertCount(1, $linked['gear']);
        $this->assertSame('სისტემა', $linked['gear'][0]['name'], 'Georgian name on /ka');
        $this->assertEquals(35, $linked['gear'][0]['prices'][2]);
        $this->assertSame(4, $linked['gear'][0]['available']);
        $this->assertSame($shown['id'], $linked['gear'][0]['id']);
        $this->assertArrayNotHasKey('renters', $linked['gear'][0]);
    }

    public function test_service_linked_to_the_wall_calculator(): void
    {
        $this->admin();
        $types = collect($this->getJson('api/set_service/get_link_targets', self::H)->assertOk()->json('types'))->pluck('key')->all();
        $this->assertContains('wall_calculator', $types);

        $us = DB::table('locale_services')->insertGetId(['locale' => 'us', 'title' => 'Walls', 'short_description' => 's', 'text' => 't', 'created_at' => now(), 'updated_at' => now()]);
        $ka = DB::table('locale_services')->insertGetId(['locale' => 'ka', 'title' => 'კედლები', 'short_description' => 's', 'text' => 't', 'created_at' => now(), 'updated_at' => now()]);
        $slug = 'wall-test-' . Str::lower(Str::random(6));
        DB::table('services')->insert(['url_title' => $slug, 'published' => 1, 'us_service_id' => $us, 'ka_service_id' => $ka, 'link_type' => 'wall_calculator', 'created_at' => now(), 'updated_at' => now()]);

        $this->app['auth']->forgetGuards();
        $this->getJson('api/get_service/get_local_service_in_page/us/' . $slug, self::H)->assertOk()->assertJsonPath('linked', ['type' => 'wall_calculator']);
    }
}
