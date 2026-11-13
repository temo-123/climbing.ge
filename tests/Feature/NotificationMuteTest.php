<?php

namespace Tests\Feature;

use App\Jobs\UserNotifications;
use App\Mail\UserNotificationMessage;
use App\Models\User;
use App\Models\User\SeasonalCampaign;
use App\Services\NotificationDispatchService;
use App\Services\NotificationPreferenceService;
use App\Services\SeasonalCampaignService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * "Turn off all notification e-mails": the all_off switch, its three enforcement points and
 * the one-click unsubscribe link. docs/FUNCTIONS_DOCS/user/notification_mute.md
 */
class NotificationMuteTest extends TestCase
{
    use DatabaseTransactions;

    private const H = ['X-Requested-With' => 'XMLHttpRequest', 'Accept' => 'application/json'];

    private function user(array $prefs = ['add_new_ice_spot' => true]): User
    {
        $id = DB::table('users')->insertGetId([
            'name' => 'Mute', 'surname' => 'T' . Str::random(4), 'email' => 'mute-' . Str::random(10) . '@example.test',
            'password' => bcrypt('secret'), 'email_verified_at' => now(), 'birthday' => '1990-01-01',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('user_notifications')->insert(['user_id' => $id, 'notification_type' => json_encode($prefs), 'created_at' => now(), 'updated_at' => now()]);
        return User::find($id);
    }

    /** a real (array-transport) send, so MailGuardService runs */
    private function sent_to(string $email, callable $send): bool
    {
        config(['mail.default' => 'array']);
        app('mail.manager')->forgetMailers();
        $send();
        $messages = app('mailer')->getSymfonyTransport()->messages();
        foreach ($messages as $m) {
            foreach ($m->getOriginalMessage()->getTo() as $to) {
                if (strcasecmp($to->getAddress(), $email) === 0) return true;
            }
        }
        return false;
    }

    public function test_send_once_skips_muted_user_and_logs_nothing(): void
    {
        Bus::fake();
        $user = $this->user(['add_new_ice_spot' => true, 'all_off' => true]);
        $article = \App\Models\Guide\Article::first();

        $this->assertFalse(NotificationDispatchService::sendOnce($user, $article, 'new_content', 'https://x', 'S', 'T'));
        Bus::assertNotDispatched(UserNotifications::class);
        $this->assertSame(0, DB::table('content_notification_logs')->where('user_id', $user->id)->count());

        $other = $this->user();
        $this->assertTrue(NotificationDispatchService::sendOnce($other, $article, 'new_content', 'https://x', 'S', 'T'));
    }

    public function test_seasonal_audience_skips_muted_user(): void
    {
        $muted = $this->user(['add_new_ice_spot' => true, 'all_off' => true]);
        $c = SeasonalCampaign::create([
            'name' => 'x', 'content_type' => 'article', 'category' => 'ice', 'start_md' => '01-01', 'end_md' => '12-31',
            'every_days' => 14, 'items_per_mail' => 1, 'pick' => 'random', 'audience' => 'interested', 'max_per_run' => 10, 'subject_us' => 's',
        ]);
        $this->assertNotContains($muted->id, SeasonalCampaignService::audience_query($c)->pluck('id'));
    }

    public function test_mail_guard_stops_notification_mail_but_not_account_mail(): void
    {
        $user = $this->user(['all_off' => true]);
        $data = ['url' => 'https://climbing.ge', 'subject' => 'New sector', 'text' => 'hi', 'email' => $user->email];

        // queued before the user muted → still stopped at send time
        $this->assertFalse($this->sent_to($user->email, fn () => Mail::to($user->email)->send(new UserNotificationMessage($data))));
        // account mail has no notification header → goes out
        $this->assertTrue($this->sent_to($user->email, fn () => Mail::raw('Your order', fn ($m) => $m->to($user->email)->subject('Order'))));

        NotificationPreferenceService::set_muted($user, false);
        $this->assertTrue($this->sent_to($user->email, fn () => Mail::to($user->email)->send(new UserNotificationMessage($data))));
    }

    public function test_notification_mail_has_one_click_headers_and_link(): void
    {
        $user = $this->user();
        $mail = new UserNotificationMessage(['url' => 'https://climbing.ge', 'subject' => 'S', 'text' => 'T', 'email' => $user->email]);
        $html = $mail->render();
        $this->assertStringContainsString(NotificationPreferenceService::unsubscribe_url($user), $html);

        config(['mail.default' => 'array']);
        app('mail.manager')->forgetMailers();
        Mail::to($user->email)->send($mail);
        $headers = collect(app('mailer')->getSymfonyTransport()->messages())->last()->getOriginalMessage()->getHeaders();
        $this->assertTrue($headers->has(NotificationPreferenceService::HEADER));
        $this->assertStringContainsString('/api/notifications/unsubscribe/' . $user->id . '/', $headers->get('List-Unsubscribe')->getBodyAsString());
        $this->assertSame('List-Unsubscribe=One-Click', $headers->get('List-Unsubscribe-Post')->getBodyAsString());
    }

    public function test_unsubscribe_link(): void
    {
        $user = $this->user();
        $path = parse_url(NotificationPreferenceService::unsubscribe_url($user), PHP_URL_PATH);

        // GET only shows the page (mail scanners open links)
        $this->get($path)->assertOk()->assertSee('Turn off all notification emails?');
        $this->assertFalse(NotificationPreferenceService::is_muted($user));

        // the button / a mail client's one-click POST
        $this->post($path, ['List-Unsubscribe' => 'One-Click'])->assertOk()->assertSee('notifications are off');
        $this->assertTrue(NotificationPreferenceService::is_muted($user));

        $this->post($path, ['action' => 'on'])->assertOk()->assertSee('back on');
        $this->assertFalse(NotificationPreferenceService::is_muted($user));

        // wrong hash, or an address change since the mail went out
        $this->get('/api/notifications/unsubscribe/' . $user->id . '/' . str_repeat('a', 64))->assertNotFound();
        DB::table('users')->where('id', $user->id)->update(['email' => 'changed-' . Str::random(6) . '@example.test']);
        $this->post($path)->assertNotFound();
    }

    public function test_settings_api(): void
    {
        $user = $this->user();
        Sanctum::actingAs($user);
        $this->getJson('api/get_options/get_user_notification_data', self::H)->assertJsonPath('all_off', false);

        $this->postJson('api/get_options/update_user_notification_data', ['data' => ['all_off' => true]], self::H)->assertOk();
        $this->assertTrue(NotificationPreferenceService::is_muted($user));

        // an older client that doesn't know the key doesn't switch mail back on
        $this->postJson('api/get_options/update_user_notification_data', ['data' => ['news' => true]], self::H)->assertOk();
        $this->getJson('api/get_options/get_user_notification_data', self::H)->assertJsonPath('all_off', true);
    }
}
