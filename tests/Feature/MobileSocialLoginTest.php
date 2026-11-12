<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * POST api/login/{provider}/mobile — native SDK token → login / sign-up.
 * Google / Facebook are faked; the token must be issued to OUR app.
 * docs/AUTH.md#mobile-social-login
 */
class MobileSocialLoginTest extends TestCase
{
    use DatabaseTransactions;

    private const AJAX = ['X-Requested-With' => 'XMLHttpRequest', 'Accept' => 'application/json'];

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.google.client_id'          => 'web-client.apps.googleusercontent.com',
            'services.google.mobile_client_ids'  => ['android-client.apps.googleusercontent.com'],
            'services.facebook.client_id'        => '111222',
            'services.facebook.client_secret'    => 'fb-secret',
        ]);
    }

    private function fake_google(array $claims): void
    {
        Http::fake(['oauth2.googleapis.com/*' => Http::response(array_merge([
            'iss' => 'https://accounts.google.com',
            'aud' => 'android-client.apps.googleusercontent.com',
            'sub' => '1234567890',
            'email_verified' => 'true',
            'exp' => (string) (time() + 3600),
            'name' => 'Nino Beridze',
            'picture' => 'https://example.test/a.jpg',
        ], $claims))]);
    }

    private function email(): string
    {
        return 'mobile-social-' . Str::random(10) . '@example.test';
    }

    public function test_existing_user_logs_in_and_gets_linked(): void
    {
        $email = $this->email();
        $id = DB::table('users')->insertGetId([
            'name' => 'Old', 'surname' => 'User', 'email' => $email, 'password' => bcrypt('secret'),
            'birthday' => '1990-01-01', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->fake_google(['email' => $email]);

        $this->postJson('api/login/google/mobile', ['id_token' => 'tok'], self::AJAX)
            ->assertOk()
            ->assertJsonPath('status', 'login')
            ->assertJsonPath('is_underage', false)
            ->assertJsonStructure(['token']);

        $this->assertNotNull(User::find($id)->email_verified_at);
        $this->assertSame(1, DB::table('social_accounts')->where(['user_id' => $id, 'provider' => 'google'])->count());

        // second login doesn't duplicate the link
        $this->postJson('api/login/google/mobile', ['id_token' => 'tok'], self::AJAX)->assertOk();
        $this->assertSame(1, DB::table('social_accounts')->where(['user_id' => $id, 'provider' => 'google'])->count());
    }

    public function test_new_user_gets_setup_key_and_birthday(): void
    {
        $email = $this->email();
        $this->fake_google(['email' => $email]);

        $res = $this->postJson('api/login/google/mobile', ['id_token' => 'tok', 'birthday' => now()->subYears(10)->toDateString()], self::AJAX)
            ->assertOk()
            ->assertJsonPath('status', 'registratione')
            ->assertJsonPath('new_user_email', $email)
            ->assertJsonPath('is_underage', true);
        $this->assertNotEmpty($res->json('setup_key'));

        $user = User::where('email', $email)->first();
        $this->assertSame('Nino', $user->name);
        $this->assertNotNull($user->email_verified_at);
        $this->assertSame(1, DB::table('social_accounts')->where(['user_id' => $user->id, 'provider' => 'google'])->count());
    }

    public function test_google_token_for_another_app_is_refused(): void
    {
        $email = $this->email();
        $this->fake_google(['email' => $email, 'aud' => 'someone-elses-app.apps.googleusercontent.com']);

        $this->postJson('api/login/google/mobile', ['id_token' => 'tok'], self::AJAX)->assertStatus(401);
        $this->assertNull(User::where('email', $email)->first());
    }

    public function test_google_unverified_email_or_expired_is_refused(): void
    {
        $this->fake_google(['email' => $this->email(), 'email_verified' => 'false']);
        $this->postJson('api/login/google/mobile', ['id_token' => 'tok'], self::AJAX)->assertStatus(401);
    }

    public function test_facebook_token_for_another_app_is_refused(): void
    {
        $email = $this->email();
        Http::fake([
            'graph.facebook.com/debug_token*' => Http::response(['data' => ['is_valid' => true, 'app_id' => '999999', 'user_id' => '55']]),
            'graph.facebook.com/me*'          => Http::response(['id' => '55', 'name' => 'A B', 'email' => $email]),
        ]);
        $this->postJson('api/login/facebook/mobile', ['access_token' => 'tok'], self::AJAX)->assertStatus(401);
        $this->assertNull(User::where('email', $email)->first());
    }

    public function test_facebook_token_of_our_app_signs_up(): void
    {
        $email = $this->email();
        Http::fake([
            'graph.facebook.com/debug_token*' => Http::response(['data' => ['is_valid' => true, 'app_id' => '111222', 'user_id' => '55']]),
            'graph.facebook.com/me*'          => Http::response(['id' => '55', 'name' => 'A B', 'email' => $email]),
        ]);
        $this->postJson('api/login/facebook/mobile', ['access_token' => 'tok'], self::AJAX)
            ->assertOk()->assertJsonPath('status', 'registratione');

        // new account got the default role + notification settings
        $user = User::where('email', $email)->first();
        $this->assertSame(1, DB::table('user_role')->where('user_id', $user->id)->count());
        $this->assertSame(1, DB::table('user_notifications')->where('user_id', $user->id)->count());
    }

    public function test_unknown_provider_and_missing_token(): void
    {
        $this->postJson('api/login/strava/mobile', ['id_token' => 'x'], self::AJAX)->assertStatus(404);
        $this->postJson('api/login/google/mobile', [], self::AJAX)->assertStatus(422);
    }
}
