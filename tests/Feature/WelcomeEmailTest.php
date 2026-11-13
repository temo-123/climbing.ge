<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Welcome mail: sent once, when the address is confirmed.
 * docs/FUNCTIONS_DOCS/global/welcome_email.md
 */
class WelcomeEmailTest extends TestCase
{
    use DatabaseTransactions;

    private const AJAX = ['X-Requested-With' => 'XMLHttpRequest', 'Accept' => 'application/json'];

    private function make_user(bool $verified): User
    {
        $id = DB::table('users')->insertGetId([
            'name' => 'Nino', 'surname' => 'Test', 'email' => 'welcome-' . Str::random(10) . '@example.test',
            'password' => bcrypt('secret123'), 'birthday' => '1990-01-01',
            'email_verified_at' => $verified ? now() : null, 'created_at' => now(), 'updated_at' => now(),
        ]);
        return User::find($id);
    }

    private function verify_url(User $user): string
    {
        $hash = hash_hmac('sha256', $user->email . '|' . $user->id, config('app.key'));
        return "api/email/verify/{$user->id}/{$hash}";
    }

    public function test_verifying_email_sends_welcome_once(): void
    {
        Notification::fake();
        $user = $this->make_user(false);

        $this->getJson($this->verify_url($user), self::AJAX)->assertOk();
        Notification::assertSentTo($user, WelcomeEmailNotification::class);

        // clicking the link again doesn't send a second one
        $this->getJson($this->verify_url($user), self::AJAX)->assertOk();
        Notification::assertSentToTimes($user, WelcomeEmailNotification::class, 1);
    }

    public function test_new_social_account_gets_welcome_existing_does_not(): void
    {
        Notification::fake();
        config(['services.google.client_id' => 'web-client.apps.googleusercontent.com', 'services.google.mobile_client_ids' => []]);
        $email = 'welcome-social-' . Str::random(10) . '@example.test';
        Http::fake(['oauth2.googleapis.com/*' => Http::response([
            'iss' => 'https://accounts.google.com', 'aud' => 'web-client.apps.googleusercontent.com',
            'sub' => '107827680723230391718', 'email' => $email, 'email_verified' => 'true',
            'exp' => (string) (time() + 3600), 'name' => 'Nino Beridze',
        ])]);

        $this->postJson('api/login/google/mobile', ['id_token' => 'tok'], self::AJAX)->assertJsonPath('status', 'registratione');
        $user = User::where('email', $email)->first();
        Notification::assertSentToTimes($user, WelcomeEmailNotification::class, 1);

        // second sign-in = login, no new welcome
        $this->postJson('api/login/google/mobile', ['id_token' => 'tok'], self::AJAX)->assertJsonPath('status', 'login');
        Notification::assertSentToTimes($user, WelcomeEmailNotification::class, 1);
    }

    public function test_welcome_is_queued_on_emails_and_renders(): void
    {
        $n = new WelcomeEmailNotification();
        $this->assertSame('emails', $n->queue);

        $html = (string) $n->toMail($this->make_user(true))->render();
        $this->assertStringContainsString('Hi Nino', $html);
        $this->assertStringContainsString('/outdoor', $html);
    }
}
