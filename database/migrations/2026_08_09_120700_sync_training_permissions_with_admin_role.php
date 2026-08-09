<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the training-content-management permissions (trainings + training
 * plans, used by the new mobile-app training API and its admin pages) and
 * grants them all to the 'admin' role. Same safe, additive, idempotent
 * pattern as 2026_07_20_120000_sync_mail_permission_with_admin_role.php.
 */
return new class extends Migration
{
    protected function pairs(): array
    {
        return [
            ['training', 'show'],
            ['training', 'add'],
            ['training', 'edit'],
            ['training', 'del'],
            ['training_plan', 'show'],
            ['training_plan', 'add'],
            ['training_plan', 'edit'],
            ['training_plan', 'del'],
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
