<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * users.use_offline_data_on_mobile — last offline-guidebook download time from the mobile app,
 * set / cleared by POST / DELETE get_options/offline_download (docs/USER_PAGE.md).
 */
class OfflineDataPreferenceTest extends TestCase
{
    use DatabaseTransactions;

    private const AJAX = ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest'];

    private function headers(?User &$user = null): array
    {
        $id = DB::table('users')->insertGetId([
            'name' => 'Off', 'surname' => 'T' . Str::random(4),
            'email' => 'offline-test-' . Str::random(10) . '@example.test',
            'password' => bcrypt('secret'), 'email_verified_at' => now(), 'birthday' => '1990-01-01',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $user = User::find($id);
        return array_merge(self::AJAX, ['Authorization' => 'Bearer ' . $user->createToken('test')->plainTextToken]);
    }

    public function test_post_sets_server_time_and_delete_clears_it(): void
    {
        $headers = $this->headers($user);

        $this->getJson('api/get_options/get_preferences', $headers)->assertOk()->assertJsonPath('use_offline_data_on_mobile', null);
        $this->getJson('api/auth_user', $headers)->assertOk()->assertJsonPath('use_offline_data_on_mobile', null);

        // a time in the body is ignored — the server uses now()
        $res = $this->postJson('api/get_options/offline_download', ['use_offline_data_on_mobile' => '2000-01-01T00:00:00Z'], $headers)
            ->assertOk();
        $at = strtotime($res->json('use_offline_data_on_mobile'));
        $this->assertLessThanOrEqual(5, abs(time() - $at), 'recent server timestamp');
        $this->assertSame($at, $user->fresh()->use_offline_data_on_mobile->getTimestamp());

        $this->assertSame($at, strtotime($this->getJson('api/get_options/get_preferences', $headers)->json('use_offline_data_on_mobile')));
        $this->assertSame($at, strtotime($this->getJson('api/auth_user', $headers)->json('use_offline_data_on_mobile')));

        // update_preferences no longer touches it
        $this->postJson('api/get_options/update_preferences', ['use_offline_data_on_mobile' => null, 'grade' => 'uiaa'], $headers)
            ->assertOk();
        $this->assertSame($at, $user->fresh()->use_offline_data_on_mobile->getTimestamp());

        $this->deleteJson('api/get_options/offline_download', [], $headers)
            ->assertOk()->assertExactJson(['use_offline_data_on_mobile' => null]);
        $this->assertNull($user->fresh()->use_offline_data_on_mobile);
        $this->getJson('api/get_options/get_preferences', $headers)->assertJsonPath('use_offline_data_on_mobile', null);
    }

    public function test_guest_gets_401(): void
    {
        $this->postJson('api/get_options/offline_download', [], self::AJAX)->assertStatus(401);
        $this->deleteJson('api/get_options/offline_download', [], self::AJAX)->assertStatus(401);
    }
}
