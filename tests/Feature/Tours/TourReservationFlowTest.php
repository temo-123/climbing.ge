<?php

namespace Tests\Feature\Tours;

use App\Models\Shop\Tour_reservation;
use App\Notifications\tour\AdminNewReservationNotification;
use App\Notifications\tour\ReservationCreatingGuideNotification;
use App\Notifications\tour\ReservationGuideAssignedNotification;
use App\Notifications\tour\ReservationSacsesfulNotification;
use App\Notifications\tour\ReservationVerifyEmail;
use App\Services\TourGuideService;
use App\Services\TourReservationService;
use App\Services\TourScheduleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

/**
 * Whole tour reservation system over HTTP: booking → price / schedule / language checks →
 * email confirmation → guide in charge, switching, 15 % fee, completion → permissions → mails.
 * docs/FUNCTIONS_DOCS/shop/tour_*.md
 */
class TourReservationFlowTest extends TourTestCase
{
    // ───────────── booking ─────────────

    public function test_guest_booking_saves_price_language_and_general_guide_and_mails_guest_and_info(): void
    {
        $tour = $this->tour();
        $res = $this->book($tour, ['email' => 'nino@example.test', 'tour_language' => 'ka'])->assertOk();

        $this->assertSame('verify_email', $res->json('status'));
        $this->assertEquals(540, $res->json('price.total'), '180 € × 3 persons');

        $r = Tour_reservation::where('email', 'nino@example.test')->firstOrFail();
        $this->assertSame(0, (int) $r->verificate);
        $this->assertSame('ka', $r->tour_language);
        $this->assertSame(2, (int) $r->days);
        $this->assertEquals(540, $r->price_total);
        $this->assertSame('EUR', $r->price_currency);
        $this->assertSame(TourGuideService::main_guide_id($tour->id), (int) $r->guide_id);
        $this->assertNull($r->commission_percent, 'the general guide pays no fee');
        $this->assertSame(1, (int) $r->verify_sent_count);

        $this->assertTrue($this->mailedTo(ReservationVerifyEmail::class, 'nino@example.test'));
        $this->assertTrue($this->mailedTo(AdminNewReservationNotification::class, $this->siteEmail()));
        Notification::assertSentOnDemandTimes(ReservationCreatingGuideNotification::class, 0); // guides only after confirmation
    }

    public function test_every_field_is_required_including_language(): void
    {
        $tour = $this->tour();
        $this->book($tour, ['tour_language' => ''])->assertStatus(422)->assertJsonValidationErrors('form_data.tour_language');
        $this->book($tour, ['tour_language' => 'ru'])->assertStatus(422)->assertJsonValidationErrors('form_data.tour_language');
        $this->book($tour, ['days' => ''])->assertStatus(422)->assertJsonValidationErrors('form_data.days');
        $this->book($tour, ['text' => ''])->assertStatus(422)->assertJsonValidationErrors('form_data.text');
        $this->book($tour, ['email' => 'not-an-email'])->assertStatus(422)->assertJsonValidationErrors('form_data.email');
        $this->assertSame(0, Tour_reservation::where('tour_id', $tour->id)->count());
    }

    public function test_unpublished_tour_cannot_be_booked_but_public_by_url_can(): void
    {
        $this->book($this->tour(['published' => 0]))->assertStatus(422);
        $this->book($this->tour(['published' => 2]))->assertOk();
    }

    public function test_max_group_season_lead_time_weekday_and_duration_are_enforced(): void
    {
        $tour = $this->tour();
        $this->book($tour, ['persons' => 7])->assertStatus(422)->assertJsonPath('errors', fn ($e) => isset($e['form_data.persons']));
        $this->book($tour, ['days' => 5])->assertStatus(422)->assertJsonPath('errors', fn ($e) => isset($e['form_data.days']));

        // winter only, Saturdays, 3 days ahead
        TourScheduleService::save($tour, ['all_year' => false, 'seasons' => [['from_month' => 12, 'from_day' => 1, 'to_month' => 3, 'to_day' => 15]], 'start_weekdays' => [6], 'lead_days' => 3]);
        $year = now()->month >= 4 ? now()->year + 1 : now()->year;
        $summer = \Carbon\Carbon::create($year, 7, 1)->next(\Carbon\Carbon::SATURDAY)->format('Y-m-d');
        $winter_sat = \Carbon\Carbon::create($year, 1, 10)->next(\Carbon\Carbon::SATURDAY);
        $this->book($tour, ['check_in' => $summer])->assertStatus(422)->assertJsonPath('errors', fn ($e) => str_contains($e['form_data.check_in'][0] ?? '', 'season'));
        $this->book($tour, ['check_in' => $winter_sat->copy()->addDay()->format('Y-m-d')])->assertStatus(422);
        // last season day is 15 Mar: a 2-day trip starting on 15 Mar ends out of season
        $this->book($tour, ['check_in' => "$year-03-15", 'days' => 2])->assertStatus(422);
        $this->book($tour, ['check_in' => $winter_sat->format('Y-m-d')])->assertOk();
    }

    public function test_price_on_request_when_no_rule_matches(): void
    {
        $tour = $this->tour();
        TourScheduleService::save($tour, ['all_year' => true, 'seasons' => [], 'duration_mode' => 'fixed', 'duration_days' => [1, 3]]);
        $this->book($tour, ['days' => 3, 'email' => 'req@example.test'])->assertOk()->assertJsonPath('price.total', null);
        $this->assertNull(Tour_reservation::where('email', 'req@example.test')->value('price_total'));
    }

    // ───────────── email confirmation ─────────────

    public function test_confirmation_link_status_verify_and_mails_go_to_guide_in_charge_and_info_only(): void
    {
        $general = $this->user(['name' => 'General']);
        $tour = $this->tour([], $general);
        $helper = $this->user(['name' => 'Helper']);
        TourGuideService::add_guide($tour, $helper->id);

        $this->book($tour, ['email' => 'guest@example.test']);
        $r = Tour_reservation::where('email', 'guest@example.test')->firstOrFail();
        $token = TourReservationService::token($r);

        $this->postJson("/api/set_user_reservation/reservation_status/{$r->id}", ['token' => 'wrong'])->assertNotFound();
        $this->postJson("/api/set_user_reservation/reservation_status/{$r->id}", ['token' => $token])
            ->assertOk()->assertJsonPath('verified', false)->assertJsonPath('tour_language', 'en')->assertJsonMissingPath('phone');

        $this->postJson("/api/set_user_reservation/verify_reservation/{$r->id}", ['token' => $token])->assertOk()->assertJsonPath('status', 'verified');
        $this->assertSame(1, (int) $r->fresh()->verificate);
        $this->assertSame('email', $r->fresh()->verify_method);

        $this->assertTrue($this->mailedTo(ReservationSacsesfulNotification::class, 'guest@example.test'));
        $this->assertTrue($this->mailedTo(ReservationCreatingGuideNotification::class, $general->email), 'guide in charge');
        $this->assertTrue($this->mailedTo(ReservationCreatingGuideNotification::class, $this->siteEmail()), 'info@');
        $this->assertFalse($this->mailedTo(ReservationCreatingGuideNotification::class, $helper->email), 'not every guide');

        // confirming twice sends nothing new
        $before = count(Notification::sentNotifications());
        $this->postJson("/api/set_user_reservation/verify_reservation/{$r->id}", ['token' => $token])->assertJsonPath('status', 'already_verified');
        $this->assertSame($before, count(Notification::sentNotifications()));
    }

    // ───────────── guides, switching, fee, completion ─────────────

    public function test_switch_to_helper_sets_15_percent_fee_mails_guide_and_info_and_logs_history(): void
    {
        $general = $this->user(['name' => 'General']);
        $tour = $this->tour([], $general);
        $helper = $this->user(['name' => 'Helper']);
        $outsider = $this->user(['name' => 'Outsider']);
        TourGuideService::add_guide($tour, $helper->id);
        $this->book($tour, ['email' => 'g2@example.test']);
        $r = Tour_reservation::where('email', 'g2@example.test')->firstOrFail();
        TourReservationService::verify($r, 'email');

        Sanctum::actingAs($this->admin());
        $this->postJson("/api/set_tour/set_reservation/assign_guide/{$r->id}", ['user_id' => $outsider->id])->assertStatus(422);
        $this->postJson("/api/set_tour/set_reservation/assign_guide/{$r->id}", ['user_id' => $helper->id, 'note' => 'Over to you', 'notify_client' => true])
            ->assertOk()->assertJsonPath('guide.id', $helper->id);

        $r = $r->fresh();
        $this->assertEquals(15, $r->commission_percent);
        $this->assertEquals(81, $r->commission_amount, '15 % of 540');
        $this->assertTrue($this->mailedTo(ReservationGuideAssignedNotification::class, $helper->email));
        $this->assertTrue($this->mailedTo(ReservationGuideAssignedNotification::class, $this->siteEmail()), 'copy to info@');
        Notification::assertSentOnDemandTimes(ReservationGuideAssignedNotification::class, 2); // exactly: new guide + info@
        $this->assertSame(1, DB::table('tour_reservation_guide_changes')->where('reservation_id', $r->id)->where('note', 'Over to you')->count());

        // the assigned-guide mail shows the fee; the guest's mails never do
        $md = TourReservationService::mail_data($r);
        $this->assertStringContainsString('climbing.ge fee', (string) (new ReservationGuideAssignedNotification($md, null, null, null, '#'))->toMail(null)->render());
        $this->assertStringNotContainsString('climbing.ge fee', (string) (new ReservationSacsesfulNotification($md))->toMail(null)->render());

        // back to the general guide: unpaid fee cleared
        $this->postJson("/api/set_tour/set_reservation/assign_guide/{$r->id}", ['user_id' => $general->id])->assertOk();
        $this->assertNull($r->fresh()->commission_percent);
    }

    public function test_completion_and_fee_paid_marks(): void
    {
        $tour = $this->tour();
        $helper = $this->user();
        TourGuideService::add_guide($tour, $helper->id);
        $this->book($tour, ['email' => 'g3@example.test']);
        $r = Tour_reservation::where('email', 'g3@example.test')->firstOrFail();

        Sanctum::actingAs($this->admin());
        $this->postJson("/api/set_tour/set_reservation/set_status/{$r->id}", ['commission_paid' => true])->assertStatus(422)->assertJsonPath('message', 'This reservation has no fee.');
        $this->postJson("/api/set_tour/set_reservation/set_status/{$r->id}", ['completed' => true])->assertOk();
        $this->assertNotNull($r->fresh()->completed_at, 'general guide reservations can be completed too');

        TourGuideService::assign($r->fresh(), $helper->id);
        $this->postJson("/api/set_tour/set_reservation/set_status/{$r->id}", ['commission_paid' => true])->assertOk();
        $this->assertNotNull($r->fresh()->commission_paid_at);
        // a paid fee survives a switch back
        TourGuideService::assign($r->fresh(), TourGuideService::relations($tour->id)->firstWhere('is_main', true)->user_id);
        $this->assertEquals(15, $r->fresh()->commission_percent);
    }

    public function test_first_guide_is_general_and_removing_general_promotes_next_and_moves_open_reservations(): void
    {
        $general = $this->user();
        $tour = $this->tour([], $general);
        $helper = $this->user();
        Sanctum::actingAs($this->admin());

        $this->postJson("/api/set_tour/add_tour_user/{$tour->id}", ['user_id' => $helper->id])->assertOk();
        $this->postJson("/api/set_tour/add_tour_user/{$tour->id}", ['user_id' => $helper->id])->assertStatus(422);
        $guides = collect($this->getJson("/api/set_tour/get_tour_users/{$tour->id}")->assertOk()->json());
        $this->assertTrue($guides->firstWhere('user.id', $general->id)['is_main']);
        $this->assertFalse($guides->firstWhere('user.id', $helper->id)['is_main']);

        $this->book($tour, ['email' => 'g4@example.test']);
        $r = Tour_reservation::where('email', 'g4@example.test')->firstOrFail();

        $this->deleteJson("/api/set_tour/remove_tour_user/{$tour->id}/{$general->id}")->assertOk()->assertJsonPath('moved', 1);
        $this->assertSame($helper->id, TourGuideService::main_guide_id($tour->id));
        $this->assertSame($helper->id, (int) $r->fresh()->guide_id);
        $this->assertNull($r->fresh()->commission_percent, 'moved to the (new) general guide → no fee');
    }

    public function test_handover_moves_open_reservations_without_fee(): void
    {
        $general = $this->user();
        $tour = $this->tour([], $general);
        $helper = $this->user();
        TourGuideService::add_guide($tour, $helper->id);
        $this->book($tour, ['email' => 'g5@example.test']);
        Sanctum::actingAs($this->admin());

        $this->postJson("/api/set_tour/handover_tour/{$tour->id}", ['user_id' => $helper->id, 'move_reservations' => true, 'notify_clients' => false])
            ->assertOk()->assertJsonPath('moved', 1);
        $r = Tour_reservation::where('email', 'g5@example.test')->first();
        $this->assertSame($helper->id, (int) $r->guide_id);
        $this->assertNull($r->commission_percent);
    }

    public function test_deleting_a_guides_account_hands_their_tours_over(): void
    {
        $general = $this->user();
        $tour = $this->tour([], $general);
        $helper = $this->user();
        TourGuideService::add_guide($tour, $helper->id);
        $this->book($tour, ['email' => 'g6@example.test']);

        $general->delete();

        $this->assertSame($helper->id, TourGuideService::main_guide_id($tour->id));
        $this->assertSame($helper->id, (int) Tour_reservation::where('email', 'g6@example.test')->value('guide_id'));
    }

    // ───────────── permissions & privacy ─────────────

    public function test_only_users_with_the_guide_role_can_be_added_and_banned_users_get_no_mail(): void
    {
        $tour = $this->tour();
        $plain = $this->user();
        DB::table('user_role')->where('user_id', $plain->id)->delete(); // no Guide role
        Sanctum::actingAs($this->admin());
        $this->postJson("/api/set_tour/add_tour_user/{$tour->id}", ['user_id' => $plain->id])->assertStatus(422);
        $ids = collect($this->getJson('/api/set_tour/get_guide_candidates')->assertOk()->json())->pluck('id');
        $this->assertFalse($ids->contains($plain->id));

        // banned addresses are dropped from every mail (MailGuardService)
        config(['mail.default' => 'array']);
        $banned = $this->user(['email' => 'banned-guest@example.test']);
        DB::table('user_role')->insert(['user_id' => $banned->id, 'role_id' => DB::table('roles')->where('slug', 'ban')->value('id'), 'created_at' => now(), 'updated_at' => now()]);
        \Illuminate\Support\Facades\Mail::raw('x', fn ($m) => $m->to('banned-guest@example.test')->subject('t'));
        \Illuminate\Support\Facades\Mail::raw('y', fn ($m) => $m->to(['ok@example.test', 'banned-guest@example.test'])->subject('t2'));
        $sent = app('mailer')->getSymfonyTransport()->messages();
        $this->assertCount(1, $sent);
        $this->assertSame(['ok@example.test'], array_map(fn ($a) => $a->getAddress(), $sent[0]->getOriginalMessage()->getTo()));
    }

    public function test_guide_page_shows_my_tours_reservations_and_fees(): void
    {
        $general = $this->user();
        $tour = $this->tour([], $general);
        $helper = $this->user();
        TourGuideService::add_guide($tour, $helper->id);
        $this->book($tour, ['email' => 'gp@example.test']);
        TourGuideService::assign(Tour_reservation::where('email', 'gp@example.test')->first(), $helper->id);

        Sanctum::actingAs($helper);
        $p = $this->getJson('/api/set_tour/get_guide_panel')->assertOk()->json();
        $this->assertSame($tour->id, $p['tours'][0]['id']);
        $this->assertFalse($p['tours'][0]['is_main']);
        $this->assertTrue(collect($p['reservations'])->contains('email', 'gp@example.test'));
        $this->assertEquals(81, $p['stats']['fees_owed']['EUR']);

        Sanctum::actingAs(\App\Models\User::find(DB::table('users')->insertGetId(['name' => 'No', 'surname' => 'Role', 'email' => 'norole-' . uniqid() . '@example.test', 'password' => 'x', 'created_at' => now(), 'updated_at' => now()])));
        $this->getJson('/api/set_tour/get_guide_panel')->assertForbidden();
    }

    public function test_admin_actions_need_permissions(): void
    {
        $tour = $this->tour();
        $this->book($tour, ['email' => 'g7@example.test']);
        $r = Tour_reservation::where('email', 'g7@example.test')->firstOrFail();

        $this->postJson("/api/set_tour/set_reservation/assign_guide/{$r->id}", ['user_id' => 1])->assertUnauthorized();
        Sanctum::actingAs($this->user());
        $this->postJson("/api/set_tour/set_reservation/assign_guide/{$r->id}", ['user_id' => 1])->assertForbidden();
        $this->postJson("/api/set_tour/set_reservation/set_status/{$r->id}", ['completed' => true])->assertForbidden();
        $this->getJson('/api/set_tour/get_reservation/get_reservation_stats')->assertForbidden();
        $this->postJson("/api/set_tour/save_tour_prices/{$tour->id}", ['rules' => []])->assertForbidden();
    }

    public function test_a_plain_guide_sees_only_reservations_of_their_own_tours(): void
    {
        $guide = $this->user();
        $mine = $this->tour([], $guide);
        $other = $this->tour();
        $this->book($mine, ['email' => 'mine@example.test']);
        $this->book($other, ['email' => 'other@example.test']);

        Sanctum::actingAs($guide);
        $rows = collect($this->getJson('/api/set_tour/get_reservation/get_user_reservations')->assertOk()->json());
        $this->assertTrue($rows->contains('email', 'mine@example.test'));
        $this->assertFalse($rows->contains('email', 'other@example.test'));
        $this->assertTrue($rows->firstWhere('email', 'mine@example.test')['is_mine']);
    }

    public function test_public_tour_payloads_do_not_leak_guide_contacts(): void
    {
        $tour = $this->tour();
        $list = collect($this->getJson('/api/get_tour/get_all_tours')->assertOk()->json())->firstWhere('id', $tour->id);
        $this->assertNotNull($list);
        $this->assertArrayNotHasKey('email', $list['user']);
        $this->assertArrayNotHasKey('phone_number', $list['user']);

        $page = $this->getJson('/api/get_tour/get_tour/us/' . $tour->url_title)->assertOk()->json();
        $this->assertArrayNotHasKey('phone_number', $page['users'][0]);
        $this->assertArrayNotHasKey('password', $page['users'][0]);
    }

    // ───────────── admin data ─────────────

    public function test_prices_overlap_is_refused_and_dashboard_and_details_have_the_new_data(): void
    {
        $tour = $this->tour();
        Sanctum::actingAs($this->admin());
        $this->postJson("/api/set_tour/save_tour_prices/{$tour->id}", ['currency' => 'EUR', 'rules' => [
            ['days' => 1, 'persons_min' => 1, 'persons_max' => 3, 'price' => 10, 'price_type' => 'per_person'],
            ['days' => 1, 'persons_min' => 3, 'persons_max' => null, 'price' => 9, 'price_type' => 'per_person'],
        ]])->assertStatus(422);

        $this->book($tour, ['email' => 'g8@example.test']);
        $r = Tour_reservation::where('email', 'g8@example.test')->firstOrFail();

        $stats = $this->getJson('/api/set_tour/get_reservation/get_reservation_stats')->assertOk()->json();
        $this->assertArrayHasKey('fees', $stats);
        $this->assertArrayHasKey('by_guide', $stats);
        $this->assertTrue(collect($stats['calendar'])->contains('id', $r->id));

        $d = $this->getJson("/api/set_tour/get_reservation/get_reservation/{$r->id}")->assertOk()->json();
        $this->assertSame('en', $d['tour_language']);
        $this->assertNotEmpty($d['guide']['name']);
        $this->assertNotEmpty($d['tour_guides']);

        $rows = collect($this->getJson('/api/set_tour/get_reservation/get_all_reservations')->assertOk()->json());
        $this->assertNotEmpty($rows->firstWhere('id', $r->id)['guide_name']);
    }
}
