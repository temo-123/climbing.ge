<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureEmailVerifiedForActions;
use App\Models\User;
use App\Notifications\VerifyEmail;
use App\Services\ReCaptchaV3Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Unverified e-mail → user actions refused (EnsureEmailVerifiedForActions),
 * account management still open, e-mail change re-verifies, admin verify / resend.
 * docs/AUTH.md#email-verification
 */
class EmailVerificationGateTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        $this->mock(ReCaptchaV3Service::class, fn ($m) => $m->shouldReceive('isConfigured')->andReturn(false));
    }

    private function user(bool $verified, array $attrs = []): User
    {
        $id = DB::table('users')->insertGetId(array_merge([
            'name' => 'Verify', 'surname' => 'T' . Str::random(4),
            'email' => 'verify-test-' . Str::random(10) . '@example.test',
            'password' => bcrypt('secret'),
            'email_verified_at' => $verified ? now() : null,
            'created_at' => now(), 'updated_at' => now(),
        ], $attrs));
        return User::find($id);
    }

    private function as(User $user): array
    {
        return [
            'Authorization' => 'Bearer ' . $user->createToken('test')->plainTextToken,
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ];
    }

    private const AJAX = ['X-Requested-With' => 'XMLHttpRequest', 'Accept' => 'application/json'];

    public function test_unverified_user_is_refused_on_every_write(): void
    {
        $user = $this->user(false);
        $other = $this->user(true);

        foreach ([
            ['post', 'api/set_user_follow/follow/' . $other->id],
            ['post', 'api/set_guide_comment_by_gest/create_comment/1'],
            ['post', 'api/set_route/set_route_review/create_route_review/1'],
            ['post', 'api/set_route_ascent/create_route_ascent/1'],
            ['post', 'api/summit/ascent/1'],
            ['post', 'api/summit_track/gpx/1'],
            ['post', 'api/set_product_feedback_by_gest/create_feedback/1'],
            ['post', 'api/set_order/create_order'],
            ['post', 'api/set_faworite_by_user/add_to_interested_events'],
            // user panel / admin CMS writes are closed too (closed by default)
            ['post', 'api/set_article/add_article/outdoor'],
            ['post', 'api/set_task/create_task'],
            ['delete', 'api/set_user/del_user/' . $other->id],
        ] as [$method, $uri]) {
            $this->json(strtoupper($method), $uri, [], $this->as($user))
                ->assertStatus(403)
                ->assertJsonPath('code', 'email_not_verified')
                ->assertJsonPath('email', $user->email);
        }

        $this->assertSame(0, DB::table('user_follows')->where('follower_id', $user->id)->count());
        $this->assertNotNull(User::find($other->id));
    }

    public function test_verified_user_and_guest_pass_the_gate(): void
    {
        $user = $this->user(true);
        $other = $this->user(true);

        $res = $this->postJson('api/set_user_follow/follow/' . $other->id, [], $this->as($user));
        $this->assertNotSame('email_not_verified', $res->json('code'));
        $res->assertOk();

        // guest (no token) on a guest-or-user route: not this middleware's business
        $this->app['auth']->forgetGuards();
        $res = $this->postJson('api/summit/ascent/0', [], self::AJAX);
        $this->assertNotSame('email_not_verified', $res->json('code'));
    }

    public function test_unverified_user_can_still_browse_and_manage_the_account(): void
    {
        $user = $this->user(false);

        $this->getJson('api/email/resend', $this->as($user))->assertOk()->assertJsonPath('message', 'Verification email sent');
        Notification::assertSentTo($user, VerifyEmail::class);

        $this->getJson('api/auth_user', $this->as($user))->assertOk()->assertJsonPath('email_verified_at', null);

        foreach ([
            ['post', 'api/search/suggest', ['q' => 'kazbegi']],
            ['post', 'api/get_options/update_preferences', []],
            ['delete', 'api/set_user_follow/unfollow/1', []],
            ['delete', 'api/summit_track/1', []],
        ] as [$method, $uri, $body]) {
            $res = $this->json(strtoupper($method), $uri, $body, $this->as($user));
            $this->assertNotSame('email_not_verified', $res->json('code'), "$uri must stay open");
        }
    }

    public function test_changing_email_in_options_resets_verification_and_sends_a_new_link(): void
    {
        $user = $this->user(true);
        $new_email = 'verify-new-' . Str::random(8) . '@example.test';
        $data = ['name' => 'N', 'surname' => 'S', 'country' => null, 'city' => null, 'phone_number' => null, 'email' => $new_email];

        $this->postJson('api/get_options/user_info_update/' . $user->id, ['user_id' => $user->id, 'data' => $data], $this->as($user))
            ->assertOk()
            ->assertJsonPath('email_changed', true);

        $user->refresh();
        $this->assertSame($new_email, $user->email);
        $this->assertNull($user->email_verified_at);
        Notification::assertSentTo($user, VerifyEmail::class);

        // same e-mail again → nothing changes, no mail
        Notification::fake();
        $user->forceFill(['email_verified_at' => now()])->save();
        $this->postJson('api/get_options/user_info_update/' . $user->id, ['user_id' => $user->id, 'data' => $data], $this->as($user))
            ->assertOk()->assertJsonPath('email_changed', false);
        $this->assertNotNull($user->fresh()->email_verified_at);
        Notification::assertNothingSent();
    }

    public function test_options_refuses_an_email_that_belongs_to_someone_else(): void
    {
        $user = $this->user(true);
        $other = $this->user(true);
        $data = ['name' => 'N', 'surname' => 'S', 'country' => null, 'city' => null, 'phone_number' => null, 'email' => $other->email];

        $this->postJson('api/get_options/user_info_update/' . $user->id, ['user_id' => $user->id, 'data' => $data], $this->as($user))
            ->assertStatus(422)->assertJsonValidationErrors('email');
        $this->assertNotSame($other->email, $user->fresh()->email);
    }

    public function test_admin_can_resend_and_verify(): void
    {
        $admin = $this->user(true);
        DB::table('user_role')->insert(['user_id' => $admin->id, 'role_id' => DB::table('roles')->where('slug', 'admin')->value('id'), 'created_at' => now(), 'updated_at' => now()]);
        $user = $this->user(false);

        $this->postJson('api/set_user/resend_verification/' . $user->id, [], $this->as($admin))
            ->assertOk()->assertJsonPath('message', 'Verification email sent');
        Notification::assertSentTo($user, VerifyEmail::class);

        $this->postJson('api/set_user/verify_user/' . $user->id, [], $this->as($admin))->assertOk();
        $this->assertNotNull($user->fresh()->email_verified_at);

        // a plain user can't do either (forget the admin the guard kept from the requests above)
        $this->app['auth']->forgetGuards();
        $plain = $this->user(true);
        $this->postJson('api/set_user/resend_verification/' . $user->id, [], $this->as($plain))->assertStatus(403);
    }

    public function test_every_open_pattern_matches_a_registered_route(): void
    {
        $uris = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($r) => array_intersect($r->methods(), EnsureEmailVerifiedForActions::GATED_METHODS))
            ->map(fn ($r) => preg_replace('/\{[^}]+\}/', 'x', $r->uri()));

        foreach (array_merge(EnsureEmailVerifiedForActions::ALWAYS_OPEN, EnsureEmailVerifiedForActions::NEVER_OPEN) as $pattern) {
            $this->assertTrue($uris->contains(fn ($u) => Str::is($pattern, $u)), "No route matches $pattern — renamed? update the lists");
        }
    }
}
