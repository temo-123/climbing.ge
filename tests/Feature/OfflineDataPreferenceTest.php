<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/** use_offline_data_on_mobile — last offline-data upload time, pushed by the mobile app via get_options/update_preferences (docs/USER_PAGE.md) */
class OfflineDataPreferenceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_mobile_app_can_read_and_set_offline_data_preference(): void
    {
        $id = DB::table('users')->insertGetId([
            'name' => 'Off', 'surname' => 'T' . Str::random(4),
            'email' => 'offline-test-' . Str::random(10) . '@example.test',
            'password' => bcrypt('secret'), 'email_verified_at' => now(), 'birthday' => '1990-01-01',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $headers = [
            'Authorization' => 'Bearer ' . User::find($id)->createToken('test')->plainTextToken,
            'Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest',
        ];

        $this->getJson('api/get_options/get_preferences', $headers)->assertOk()->assertJsonPath('use_offline_data_on_mobile', null);

        // the app pushes the time of the last offline-data upload
        $at = '2026-11-12T09:30:00+00:00';
        $res = $this->postJson('api/get_options/update_preferences', ['use_offline_data_on_mobile' => $at], $headers)
            ->assertOk()->assertJsonPath('animations', true);
        $this->assertSame(strtotime($at), strtotime($res->json('use_offline_data_on_mobile')));
        $this->assertSame(strtotime($at), User::find($id)->use_offline_data_on_mobile->getTimestamp());

        // other fields leave it alone
        $res = $this->postJson('api/get_options/update_preferences', ['grade' => 'uiaa'], $headers)->assertOk();
        $this->assertSame(strtotime($at), strtotime($res->json('use_offline_data_on_mobile')));

        // null = stopped using offline data
        $this->postJson('api/get_options/update_preferences', ['use_offline_data_on_mobile' => null], $headers)
            ->assertOk()->assertJsonPath('use_offline_data_on_mobile', null);

        // not a date / far future → 422
        $this->postJson('api/get_options/update_preferences', ['use_offline_data_on_mobile' => 'nope'], $headers)->assertStatus(422);
        $this->postJson('api/get_options/update_preferences', ['use_offline_data_on_mobile' => now()->addDays(5)->toIso8601String()], $headers)->assertStatus(422);
    }
}
