<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureMinimumAgeForActions;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Under 13 → account created, but every function closed (EnsureMinimumAgeForActions),
 * no CASL rules, auth_user flags is_underage.
 * docs/FUNCTIONS_DOCS/global/age_restriction.md
 */
class AgeRestrictionGateTest extends TestCase
{
    use DatabaseTransactions;

    private function user(?string $birthday): User
    {
        $id = DB::table('users')->insertGetId([
            'name' => 'Age', 'surname' => 'T' . Str::random(4),
            'email' => 'age-test-' . Str::random(10) . '@example.test',
            'password' => bcrypt('secret'),
            'email_verified_at' => now(),
            'birthday' => $birthday,
            'created_at' => now(), 'updated_at' => now(),
        ]);
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

    public function test_is_underage_by_birthday(): void
    {
        $this->assertTrue($this->user(now()->subYears(12)->toDateString())->isUnderage());
        $this->assertTrue($this->user(now()->subYears(13)->addDay()->toDateString())->isUnderage());
        $this->assertFalse($this->user(now()->subYears(13)->toDateString())->isUnderage());
        $this->assertFalse($this->user(null)->isUnderage());
    }

    public function test_underage_user_is_refused_on_every_write(): void
    {
        $user = $this->user(now()->subYears(10)->toDateString());
        $other = $this->user('1990-01-01');

        foreach ([
            ['post', 'api/set_user_follow/follow/' . $other->id],
            ['post', 'api/set_route_ascent/create_route_ascent/1'],
            ['post', 'api/summit/ascent/1'],
            ['post', 'api/set_order/create_order'],
            ['post', 'api/get_options/update_preferences'],
            ['delete', 'api/set_user_follow/unfollow/1'],
        ] as [$method, $uri]) {
            $this->json(strtoupper($method), $uri, [], $this->as($user))
                ->assertStatus(403)
                ->assertJsonPath('code', 'age_restricted')
                ->assertJsonPath('min_age', User::MIN_AGE);
        }

        $this->assertSame(0, DB::table('user_follows')->where('follower_id', $user->id)->count());
    }

    public function test_underage_auth_user_has_no_permissions(): void
    {
        $user = $this->user(now()->subYears(10)->toDateString());

        $this->getJson('api/auth_user', $this->as($user))
            ->assertOk()
            ->assertJsonPath('is_underage', true)
            ->assertJsonPath('casl_permissions', []);

        $this->getJson('api/get_user/get_auth_user_permissions', $this->as($user))
            ->assertOk()->assertExactJson([]);
    }

    public function test_adult_and_underage_open_endpoints_pass(): void
    {
        $adult = $this->user('1990-01-01');
        $other = $this->user('1990-01-01');
        $res = $this->postJson('api/set_user_follow/follow/' . $other->id, [], $this->as($adult));
        $this->assertNotSame('age_restricted', $res->json('code'));
        $this->getJson('api/auth_user', $this->as($adult))->assertJsonPath('is_underage', false);

        $kid = $this->user(now()->subYears(10)->toDateString());
        $res = $this->postJson('api/search/suggest', ['q' => 'kazbegi'], $this->as($kid));
        $this->assertNotSame('age_restricted', $res->json('code'));
    }

    public function test_always_open_entries_match_real_routes(): void
    {
        $uris = collect(Route::getRoutes()->getRoutes())->map(fn ($r) => $r->uri())->all();
        foreach (EnsureMinimumAgeForActions::ALWAYS_OPEN as $pattern) {
            $this->assertTrue(
                collect($uris)->contains(fn ($u) => Str::is($pattern, $u)),
                "ALWAYS_OPEN entry '$pattern' matches no route"
            );
        }
    }
}
