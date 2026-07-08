<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Follow-up to 2026_07_01_214909_sync_missing_permissions_with_admin_role.php —
 * that migration's pairs() list was diffed only against literal
 * PermissionService::authorize('subject','action') calls in app/Http/Controllers/,
 * but the Guide/Shop admin dashboards (GuideDashboardPageComponent.vue /
 * ShopDashboardPageComponent.vue) and the Team Members page are gated purely on
 * the FRONTEND — router meta.permissions in resources/js/routes/UserRoutes.js and
 * $can() checks in the nav menu — via team/show, guide_task/*, shop_task/*, and
 * their *_category counterparts. No backend controller ever calls authorize() with
 * those subjects (TaskController/TaskCategoryController use the generic 'task'/
 * 'task_category' subject instead), so the diff missed them entirely. Production's
 * permissions table never got these 18 rows, so the dashboards are invisible to
 * every role there, admin included — even though PermissionTableSeeder (dev-only,
 * commented out in DatabaseSeeder for production) already seeds them locally.
 *
 * Same safe, additive, idempotent approach as the migration above: only inserts
 * permission rows that don't already exist and only grants them to 'admin'.
 */
return new class extends Migration
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

    public function up(): void
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

    /**
     * Only removes the admin grants and permission rows this migration
     * itself would have created — doesn't touch anything that already
     * existed before it ran.
     */
    public function down(): void
    {
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');

        foreach ($this->pairs() as [$subject, $action]) {
            $permissionId = DB::table('permissions')
                ->where('subject', $subject)
                ->where('action', $action)
                ->value('id');

            if ($permissionId) {
                if ($adminRoleId) {
                    DB::table('role_permissions')
                        ->where('role_id', $adminRoleId)
                        ->where('permission_id', $permissionId)
                        ->delete();
                }
                DB::table('user_permissions')->where('permission_id', $permissionId)->delete();
                DB::table('permissions')->where('id', $permissionId)->delete();
            }
        }
    }
};
