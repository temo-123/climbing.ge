<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

/**
 * Mobile browser-redirect social login:
 * GET login/{p}?mobile=1&nonce= → provider → GET login/{p}/mobile_callback → app deep link
 * → POST login/mobile/exchange {code, nonce}. Socialite is mocked.
 * docs/AUTH.md#mobile-browser-login
 */
class MobileBrowserSocialLoginTest extends TestCase
{
    use DatabaseTransactions;

    private const AJAX = ['X-Requested-With' => 'XMLHttpRequest', 'Accept' => 'application/json'];
    private const NONCE = 'app-nonce-0123456789abcdefABCDEF';

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.google.client_id'       => 'web-client.apps.googleusercontent.com',
            'services.google.client_secret'   => 'secret',
            'services.google.redirect'        => 'https://climbing.ge/login/google/callback',
            'services.google.mobile_redirect' => null,
            'services.mobile_app.scheme'      => 'climbinggeorgia',
        ]);
    }

    /** Socialite driver whose ->user() returns $user (or throws when null). */
    private function mock_socialite(?SocialiteUser $user): void
    {
        $driver = Mockery::mock();
        $driver->shouldReceive('stateless')->andReturnSelf();
        $driver->shouldReceive('redirectUrl')->with('https://climbing.ge/api/login/google/mobile_callback')->andReturnSelf();
        $user
            ? $driver->shouldReceive('user')->andReturn($user)
            : $driver->shouldReceive('user')->andThrow(new \RuntimeException('bad code'));
        Socialite::shouldReceive('driver')->with('google')->andReturn($driver);
    }

    private function google_user(string $email): SocialiteUser
    {
        return (new SocialiteUser())->map([
            'id' => '107827680723230391718', 'email' => $email, 'name' => 'Nino Beridze', 'avatar' => null,
        ]);
    }

    /** step 1 → the state sent to Google */
    private function start(string $nonce = self::NONCE): string
    {
        $url = $this->getJson('api/login/google?mobile=1&nonce=' . $nonce, self::AJAX)->assertOk()->json('url');
        parse_str(parse_url($url, PHP_URL_QUERY), $q);
        $this->assertSame('https://climbing.ge/api/login/google/mobile_callback', $q['redirect_uri']);
        $this->assertNotEmpty($q['state']);
        return $q['state'];
    }

    /** step 2 → deep link query */
    private function hit_callback(string $state, array $extra = ['code' => 'google-code']): array
    {
        $res = $this->get('api/login/google/mobile_callback?' . http_build_query(['state' => $state] + $extra));
        $res->assertStatus(302);
        $location = $res->headers->get('Location');
        $this->assertStringStartsWith('climbinggeorgia://oauth-callback?', $location);
        parse_str(parse_url($location, PHP_URL_QUERY), $q);
        return $q;
    }

    public function test_status_reports_mobile(): void
    {
        $this->getJson('api/login/status', self::AJAX)->assertOk()->assertJsonPath('mobile', true);
    }

    public function test_bad_or_missing_nonce_is_rejected(): void
    {
        $this->getJson('api/login/google?mobile=1&nonce=bad', self::AJAX)->assertStatus(422);
        $this->getJson('api/login/google?mobile=1', self::AJAX)->assertStatus(422);
        $this->getJson('api/login/strava?mobile=1&nonce=' . self::NONCE, self::AJAX)->assertStatus(404);
    }

    public function test_full_flow_registers_new_user_then_code_is_single_use(): void
    {
        $email = 'mobile-browser-' . Str::random(10) . '@example.test';
        $state = $this->start();
        $this->mock_socialite($this->google_user($email));

        $q = $this->hit_callback($state);
        $this->assertArrayHasKey('code', $q);
        $this->assertArrayNotHasKey('token', $q);

        $this->postJson('api/login/mobile/exchange', ['code' => $q['code'], 'nonce' => self::NONCE], self::AJAX)
            ->assertOk()
            ->assertJsonPath('status', 'registratione')
            ->assertJsonPath('new_user_email', $email)
            ->assertJsonStructure(['setup_key']);

        $user_id = DB::table('users')->where('email', $email)->value('id');
        $this->assertSame('107827680723230391718',
            DB::table('social_accounts')->where(['user_id' => $user_id, 'provider' => 'google'])->value('provider_user_id'));

        // code is single-use
        $this->postJson('api/login/mobile/exchange', ['code' => $q['code'], 'nonce' => self::NONCE], self::AJAX)
            ->assertStatus(410);
    }

    public function test_existing_user_gets_token(): void
    {
        $email = 'mobile-browser-' . Str::random(10) . '@example.test';
        DB::table('users')->insert([
            'name' => 'Old', 'surname' => 'User', 'email' => $email, 'password' => bcrypt('secret'),
            'birthday' => '1990-01-01', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $state = $this->start();
        $this->mock_socialite($this->google_user($email));
        $q = $this->hit_callback($state);

        $this->postJson('api/login/mobile/exchange', ['code' => $q['code'], 'nonce' => self::NONCE], self::AJAX)
            ->assertOk()
            ->assertJsonPath('status', 'login')
            ->assertJsonStructure(['token']);
    }

    public function test_wrong_nonce_burns_the_code(): void
    {
        $state = $this->start();
        $this->mock_socialite($this->google_user('mobile-browser-' . Str::random(10) . '@example.test'));
        $q = $this->hit_callback($state);

        $this->postJson('api/login/mobile/exchange', ['code' => $q['code'], 'nonce' => 'someone-elses-nonce-000000'], self::AJAX)
            ->assertStatus(403);
        $this->postJson('api/login/mobile/exchange', ['code' => $q['code'], 'nonce' => self::NONCE], self::AJAX)
            ->assertStatus(410);
    }

    public function test_fake_code_is_410(): void
    {
        $this->postJson('api/login/mobile/exchange', ['code' => 'fake', 'nonce' => self::NONCE], self::AJAX)
            ->assertStatus(410);
    }

    public function test_callback_errors_deep_link_back(): void
    {
        $this->assertSame(['error' => 'invalid_state'], $this->hit_callback('unknown-state'));

        $state = $this->start();
        $this->assertSame(['error' => 'cancelled'], $this->hit_callback($state, ['error' => 'access_denied']));
        // state is single-use
        $this->assertSame(['error' => 'invalid_state'], $this->hit_callback($state));

        $state = $this->start();
        $this->mock_socialite(null);
        $this->assertSame(['error' => 'provider_failed'], $this->hit_callback($state));
    }
}
