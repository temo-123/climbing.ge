<?php

namespace Tests\Feature;

use App\Mail\SeasonalCampaignMessage;
use App\Models\User;
use App\Models\User\SeasonalCampaign;
use App\Services\SeasonalCampaignService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Seasonal campaigns: season window, audience, per-user picks + throttles, admin API.
 * docs/FUNCTIONS_DOCS/user/seasonal_campaigns.md
 */
class SeasonalCampaignTest extends TestCase
{
    use DatabaseTransactions;

    private const H = ['X-Requested-With' => 'XMLHttpRequest', 'Accept' => 'application/json'];

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        // campaigns from the dev DB must not interfere with the global-gap checks
        DB::table('content_notification_logs')->where('notification_key', 'like', 'seasonal\_%')->delete();
    }

    private function user(array $attrs = [], array $prefs = ['add_new_ice_spot' => true]): User
    {
        $id = DB::table('users')->insertGetId(array_merge([
            'name' => 'Nino', 'surname' => 'S' . Str::random(4),
            'email' => 'seasonal-' . Str::random(10) . '@example.test',
            'password' => bcrypt('secret'), 'email_verified_at' => now(), 'birthday' => '1990-01-01',
            'last_seen_at' => now(), 'created_at' => now()->subYear(), 'updated_at' => now(),
        ], $attrs));
        DB::table('user_notifications')->insert([
            'user_id' => $id, 'notification_type' => json_encode($prefs), 'created_at' => now(), 'updated_at' => now(),
        ]);
        return User::find($id);
    }

    private function admin(): User
    {
        $u = $this->user();
        DB::table('user_role')->insert(['user_id' => $u->id, 'role_id' => DB::table('roles')->where('slug', 'admin')->value('id'), 'created_at' => now(), 'updated_at' => now()]);
        Sanctum::actingAs($u);
        return $u;
    }

    /** a published ice article of our own, so the campaign always has content */
    private function ice_article(string $title = 'Test icefall'): int
    {
        $us = DB::table('locale_articles')->insertGetId(['locale' => 'us', 'title' => $title, 'short_description' => 'Steep blue ice &amp; a short walk', 'created_at' => now(), 'updated_at' => now()]);
        $ka = DB::table('locale_articles')->insertGetId(['locale' => 'ka', 'title' => $title . ' KA', 'short_description' => 'ყინული', 'created_at' => now(), 'updated_at' => now()]);
        return DB::table('articles')->insertGetId([
            'category' => 'ice', 'url_title' => Str::slug($title) . '-' . Str::random(6), 'published' => 1,
            'us_article_id' => $us, 'ka_article_id' => $ka, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function campaign(array $attrs = []): SeasonalCampaign
    {
        return SeasonalCampaign::create(array_merge([
            'name' => 'Ice ' . Str::random(4), 'content_type' => 'article', 'category' => 'ice', 'sun_filter' => 'any',
            'start_md' => '01-01', 'end_md' => '12-31', 'every_days' => 14, 'items_per_mail' => 1,
            'pick' => 'random', 'audience' => 'interested', 'max_per_run' => 5000,
            'subject_us' => 'Ice season ❄️', 'subject_ka' => 'ყინული ❄️', 'intro_us' => 'Go climb ice', 'enabled' => true,
        ], $attrs));
    }

    public function test_season_window_including_new_year_wrap(): void
    {
        $c = $this->campaign(['start_md' => '12-01', 'end_md' => '02-28']);
        $this->assertTrue(SeasonalCampaignService::in_season($c, Carbon::parse('2026-12-15')));
        $this->assertTrue(SeasonalCampaignService::in_season($c, Carbon::parse('2027-01-20')));
        $this->assertFalse(SeasonalCampaignService::in_season($c, Carbon::parse('2026-11-13')));
        $this->assertSame('2026-12-01', SeasonalCampaignService::next_start($c, Carbon::parse('2026-11-13'))->toDateString());
        $this->assertSame('2027-02-28', SeasonalCampaignService::season_end($c, Carbon::parse('2026-12-15'))->toDateString());

        $summer = $this->campaign(['start_md' => '06-15', 'end_md' => '08-31']);
        $this->assertSame('2027-06-15', SeasonalCampaignService::next_start($summer, Carbon::parse('2026-11-13'))->toDateString());
    }

    public function test_run_mails_interested_user_and_respects_every_days(): void
    {
        $this->ice_article();
        $user = $this->user(['lang' => 'ka']);
        $c = $this->campaign();

        $r = SeasonalCampaignService::run($c);
        $this->assertGreaterThanOrEqual(1, $r['sent']);
        Mail::assertQueued(SeasonalCampaignMessage::class, fn ($m) => $m->hasTo($user->email)
            && $m->data['subject'] === 'ყინული ❄️' && count($m->data['items']) === 1
            && str_contains($m->data['items'][0]['url'], 'utm_campaign=seasonal_' . $c->id));
        $this->assertSame(1, DB::table('content_notification_logs')->where(['user_id' => $user->id, 'notification_key' => $c->logKey()])->count());

        // second run the same day: throttled for this user
        Mail::fake();
        SeasonalCampaignService::run($c);
        Mail::assertNotQueued(SeasonalCampaignMessage::class, fn ($m) => $m->hasTo($user->email));
    }

    public function test_global_gap_blocks_a_second_campaign(): void
    {
        $this->ice_article();
        $user = $this->user();
        SeasonalCampaignService::run($this->campaign());

        Mail::fake();
        SeasonalCampaignService::run($this->campaign(['name' => 'Other ice']));
        Mail::assertNotQueued(SeasonalCampaignMessage::class, fn ($m) => $m->hasTo($user->email));
    }

    public function test_never_repeats_an_item_and_skips_favorites(): void
    {
        $fav = $this->ice_article('Favourite ice');
        $this->ice_article('Second ice');
        $user = $this->user();
        DB::table('favorite_outdoor_areas')->insert(['user_id' => $user->id, 'article_id' => $fav, 'created_at' => now(), 'updated_at' => now()]);
        $c = $this->campaign();
        $candidates = SeasonalCampaignService::candidates($c);

        $seen = [];
        for ($i = 0; $i < $candidates->count() + 1; $i++) {
            $items = SeasonalCampaignService::pick_for($c, $user, $candidates);
            if (!$items) break;
            $this->assertNotContains($items[0]['id'], $seen);
            $this->assertNotSame($fav, $items[0]['id']);
            $seen[] = $items[0]['id'];
            SeasonalCampaignService::send($c, $user, $items);
        }
        $this->assertSame([], SeasonalCampaignService::pick_for($c, $user, $candidates));   // all used up
        $this->assertCount($candidates->count() - 1, $seen);                                 // everything except the favourite
    }

    public function test_audience_rules(): void
    {
        $c = $this->campaign();
        $in = $this->user();
        $opted_out = $this->user([], ['add_new_ice_spot' => true, 'seasonal_picks' => false]);
        $not_interested = $this->user([], ['add_new_gym' => true]);
        $unverified = $this->user(['email_verified_at' => null]);

        $ids = SeasonalCampaignService::audience_query($c)->pluck('id');
        $this->assertContains($in->id, $ids);
        $this->assertNotContains($opted_out->id, $ids);
        $this->assertNotContains($not_interested->id, $ids);
        $this->assertNotContains($unverified->id, $ids);

        $inactive = $this->campaign(['audience' => 'inactive', 'inactive_days' => 30]);
        $away = $this->user(['last_seen_at' => now()->subDays(60)], ['add_new_gym' => true]);
        $ids = SeasonalCampaignService::audience_query($inactive)->pluck('id');
        $this->assertContains($away->id, $ids);
        $this->assertNotContains($in->id, $ids);   // seen today
    }

    public function test_out_of_season_sends_nothing(): void
    {
        $this->ice_article();
        $this->user();
        $today = now(config('app.timezone'));
        $c = $this->campaign(['start_md' => $today->copy()->addDays(10)->format('m-d'), 'end_md' => $today->copy()->addDays(20)->format('m-d')]);
        $this->assertSame('out_of_season', SeasonalCampaignService::run($c)['skipped']);
        Mail::assertNothingQueued();
    }

    public function test_admin_api(): void
    {
        Sanctum::actingAs($this->user());
        $this->getJson('api/set_seasonal_campaign/list', self::H)->assertStatus(403);

        $this->admin();
        $this->ice_article();
        $payload = [
            'name' => 'Winter ice', 'content_type' => 'article', 'category' => 'ice', 'start_md' => '12-01', 'end_md' => '02-28',
            'every_days' => 21, 'items_per_mail' => 2, 'pick' => 'popular', 'audience' => 'interested', 'max_per_run' => 300,
            'subject_us' => 'Ice season ❄️',
        ];
        $this->postJson('api/set_seasonal_campaign/store', array_merge($payload, ['start_md' => '13-01']), self::H)->assertStatus(422);
        $id = $this->postJson('api/set_seasonal_campaign/store', $payload, self::H)->assertCreated()->json('id');

        $row = collect($this->getJson('api/set_seasonal_campaign/list', self::H)->assertOk()->json('campaigns'))->firstWhere('id', $id);
        $this->assertFalse($row['enabled']);
        $this->assertGreaterThanOrEqual(1, $row['content_count']);
        $this->assertArrayHasKey('mails_total', $row['stats']);

        $this->postJson("api/set_seasonal_campaign/toggle/{$id}", [], self::H)->assertJsonPath('enabled', true);
        $this->getJson("api/set_seasonal_campaign/preview/{$id}", self::H)->assertOk()->assertJsonStructure(['items', 'due', 'in_season']);
        $this->postJson("api/set_seasonal_campaign/send_test/{$id}", [], self::H)->assertOk();
        Mail::assertQueued(SeasonalCampaignMessage::class);
        $this->assertSame(0, DB::table('content_notification_logs')->where('notification_key', 'seasonal_' . $id)->count());   // tests aren't logged
        $this->deleteJson("api/set_seasonal_campaign/delete/{$id}", [], self::H)->assertOk();
    }

    public function test_seasonal_picks_preference_defaults_on_and_survives_old_clients(): void
    {
        $user = $this->user();
        Sanctum::actingAs($user);
        $this->getJson('api/get_options/get_user_notification_data', self::H)->assertJsonPath('seasonal_picks', true);

        // an old client that doesn't know the key saves its settings → stays on
        $this->postJson('api/get_options/update_user_notification_data', ['data' => ['add_new_ice_spot' => true]], self::H)->assertOk();
        $this->getJson('api/get_options/get_user_notification_data', self::H)->assertJsonPath('seasonal_picks', true);

        $this->postJson('api/get_options/update_user_notification_data', ['data' => ['seasonal_picks' => false]], self::H)->assertOk();
        $this->getJson('api/get_options/get_user_notification_data', self::H)->assertJsonPath('seasonal_picks', false);
    }

    public function test_mail_renders_with_items(): void
    {
        $html = (new SeasonalCampaignMessage([
            'locale' => 'us', 'subject' => 'Ice season ❄️', 'intro' => 'Go climb ice', 'name' => 'Nino',
            'items' => [['title' => 'Test icefall', 'text' => 'Steep blue ice', 'url' => 'https://climbing.ge/ice/x?utm_campaign=seasonal_1', 'image' => null]],
            'manage_url' => 'https://user.climbing.ge/options?tab=notifications',
        ]))->render();
        $this->assertStringContainsString('Hi Nino', $html);
        $this->assertStringContainsString('Test icefall', $html);
        $this->assertStringContainsString('options?tab=notifications', $html);
    }
}
