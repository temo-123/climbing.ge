<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the 'user_notification'/'show' permission introduced by
 * NotificationAnalyticsController and grants it to the 'admin' role.
 * Follows the same safe, additive, idempotent pattern as
 * 2026_07_01_214909_sync_missing_permissions_with_admin_role.php and
 * 2026_07_07_140000_sync_missing_dashboard_permissions_with_admin_role.php.
 */
return new class extends Migration
{
    protected function pairs(): array
    {
        return [
            ['user_notification', 'show'],
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
