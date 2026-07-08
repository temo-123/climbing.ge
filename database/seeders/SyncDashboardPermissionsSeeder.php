<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Guide/Shop admin dashboards (GuideDashboardPageComponent.vue /
 * ShopDashboardPageComponent.vue) and the Team Members page are gated purely on
 * the FRONTEND — router meta.permissions in resources/js/routes/UserRoutes.js and
 * $can() checks in the nav menu — via team/show, guide_task/*, shop_task/*, and
 * their *_category counterparts. No backend controller ever calls
 * PermissionService::authorize() with those subjects, so they're easy to miss when
 * reconciling permissions against backend code (see
 * 2026_07_01_214909_sync_missing_permissions_with_admin_role.php, which missed
 * exactly these). PermissionTableSeeder already seeds them for fresh/local
 * installs — this seeder exists to patch an existing production database that
 * predates them, without the destructive wipe-and-rebuild PermissionTableSeeder
 * does to every other role's grants.
 *
 * Safe to run repeatedly: only inserts rows that don't already exist and only
 * grants them to 'admin'.
 */
class SyncDashboardPermissionsSeeder extends Seeder
{
    protected function pairs(): array
    {
        return [
            ['team', 'show'],
            ['team', 'edit'],
            ['guide_task', 'show'],
            ['guide_task', 'add'],
            ['guide_task', 'edit'],
            ['guide_task', 'del'],
            ['guide_task_category', 'show'],
            ['guide_task_category', 'add'],
            ['guide_task_category', 'edit'],
            ['guide_task_category', 'del'],
            ['shop_task', 'show'],
            ['shop_task', 'add'],
            ['shop_task', 'edit'],
            ['shop_task', 'del'],
            ['shop_task_category', 'show'],
            ['shop_task_category', 'add'],
            ['shop_task_category', 'edit'],
            ['shop_task_category', 'del'],
        ];
    }

    public function run()
    {
        $now = now();
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');

        foreach ($this->pairs() as [$subject, $action]) {
            $permissionId = DB::table('permissions')
                ->where('subject', $subject)
                ->where('action', $action)
                ->value('id');

            if (!$permissionId) {
                $permissionId = DB::table('permissions')->insertGetId([
                    'subject' => $subject,
                    'action' => $action,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            if ($adminRoleId) {
                $alreadyGranted = DB::table('role_permissions')
                    ->where('role_id', $adminRoleId)
                    ->where('permission_id', $permissionId)
                    ->exists();

                if (!$alreadyGranted) {
                    DB::table('role_permissions')->insert([
                        'role_id' => $adminRoleId,
                        'permission_id' => $permissionId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}
