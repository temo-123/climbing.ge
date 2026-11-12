<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Repairs accounts left half-created by the old social sign-up bugs (fixed Nov 2026,
     * docs/AUTH.md#mobile-social-login): SocialController crashed (500) before giving a new
     * Google / Facebook account its role + notification settings, and email_verified_at was
     * dropped by User::create(), so those accounts were stored unverified.
     * Idempotent — only touches social_accounts users that are still missing something.
     */
    public function up(): void
    {
        $role_id = DB::table('roles')->where('slug', 'user')->value('id');
        $now = now();

        // 1. default "user" role
        if ($role_id) {
            $missing_role = DB::table('social_accounts as sa')
                ->join('users as u', 'u.id', '=', 'sa.user_id')
                ->leftJoin('user_role as ur', 'ur.user_id', '=', 'sa.user_id')
                ->whereNull('ur.user_id')
                ->distinct()
                ->pluck('sa.user_id');

            foreach ($missing_role as $user_id) {
                DB::table('user_role')->insert([
                    'user_id' => $user_id, 'role_id' => $role_id, 'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }

        // 2. notification settings (same defaults as SocialController::createUserPermissionsAndNotifications)
        $defaults = json_encode([
            'add_new_gym' => true, 'news' => true, 'add_new_ice_spot' => true, 'add_new_outdoor_spot' => true,
            'add_new_product' => true, 'add_new_sector' => true, 'add_new_service' => true, 'add_new_techtip' => true,
            'favorite_film' => true, 'favorite_outdoor' => true, 'favorite_product' => true, 'interested_event' => true,
        ]);
        $missing_notif = DB::table('social_accounts as sa')
            ->join('users as u', 'u.id', '=', 'sa.user_id')
            ->leftJoin('user_notifications as n', 'n.user_id', '=', 'sa.user_id')
            ->whereNull('n.user_id')
            ->distinct()
            ->pluck('sa.user_id');

        foreach ($missing_notif as $user_id) {
            DB::table('user_notifications')->insert([
                'user_id' => $user_id, 'notification_type' => $defaults, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // 3. the provider verified the address
        DB::table('users')
            ->whereNull('email_verified_at')
            ->whereIn('id', DB::table('social_accounts')->select('user_id'))
            ->update(['email_verified_at' => $now]);
    }

    public function down(): void
    {
        // data repair — nothing to undo
    }
};
