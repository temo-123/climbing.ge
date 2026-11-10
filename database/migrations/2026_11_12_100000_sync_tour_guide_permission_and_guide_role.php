<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Tour guides (docs/FUNCTIONS_DOCS/shop/tour_guides.md#guide-role--guide-page):
 *  - permission `tour_guide › show` = "can be a tour guide": opens the Guide page
 *    (/guide_page — my tours, my reservations, my fees) and is required to be added
 *    as a guide of a tour.
 *  - role "Guide" (slug `guide`) with that permission.
 *  - granted to `admin` as well (admins can be guides and see the page).
 * Same safe, additive, idempotent pattern as the other sync_*_permissions migrations.
 */
return new class extends Migration
{
    protected function pairs(): array
    {
        return [['tour_guide', 'show']];
    }

    public function up(): void
    {
        $now = now();

        $guideRoleId = DB::table('roles')->where('slug', 'guide')->value('id');
        if (!$guideRoleId) {
            $guideRoleId = DB::table('roles')->insertGetId([
                'name' => 'Guide',
                'slug' => 'guide',
                'description' => 'Tour guide: can be added to tours, sees the Guide page with own tours, reservations and fees.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');

        foreach ($this->pairs() as [$subject, $action]) {
            $permissionId = DB::table('permissions')->where('subject', $subject)->where('action', $action)->value('id');
            if (!$permissionId) {
                $permissionId = DB::table('permissions')->insertGetId([
                    'subject' => $subject, 'action' => $action, 'created_at' => $now, 'updated_at' => $now,
                ]);
            }
            foreach (array_filter([$adminRoleId, $guideRoleId]) as $roleId) {
                $granted = DB::table('role_permissions')->where('role_id', $roleId)->where('permission_id', $permissionId)->exists();
                if (!$granted) {
                    DB::table('role_permissions')->insert([
                        'role_id' => $roleId, 'permission_id' => $permissionId, 'created_at' => $now, 'updated_at' => $now,
                    ]);
                }
            }
        }

        // everyone who already guides a tour gets the Guide role (so nothing they do today breaks)
        $guideIds = DB::table('user_tours')->distinct()->pluck('user_id');
        foreach ($guideIds as $userId) {
            $has = DB::table('user_role')->where('user_id', $userId)->where('role_id', $guideRoleId)->exists();
            if (!$has) {
                DB::table('user_role')->insert(['user_id' => $userId, 'role_id' => $guideRoleId, 'created_at' => $now, 'updated_at' => $now]);
            }
        }
    }

    /** Removes only the grants and rows this migration creates. */
    public function down(): void
    {
        $guideRoleId = DB::table('roles')->where('slug', 'guide')->value('id');
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');
        foreach ($this->pairs() as [$subject, $action]) {
            $permissionId = DB::table('permissions')->where('subject', $subject)->where('action', $action)->value('id');
            if ($permissionId) {
                DB::table('role_permissions')->where('permission_id', $permissionId)->whereIn('role_id', array_filter([$adminRoleId, $guideRoleId]))->delete();
                if (!DB::table('role_permissions')->where('permission_id', $permissionId)->exists() && !DB::table('user_permissions')->where('permission_id', $permissionId)->exists()) {
                    DB::table('permissions')->where('id', $permissionId)->delete();
                }
            }
        }
        if ($guideRoleId) {
            DB::table('user_role')->where('role_id', $guideRoleId)->delete();
            DB::table('roles')->where('id', $guideRoleId)->delete();
        }
    }
};
