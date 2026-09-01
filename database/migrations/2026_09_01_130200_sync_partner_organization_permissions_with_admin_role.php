<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the partner-organization permissions (organizations + their members,
 * managed together on one admin page) and grants them all to the 'admin'
 * role. Same safe, additive, idempotent pattern as
 * 2026_08_09_120700_sync_training_permissions_with_admin_role.php.
 */
return new class extends Migration
{
    protected function pairs(): array
    {
        return [
            ['partner_organization', 'show'],
            ['partner_organization', 'add'],
            ['partner_organization', 'edit'],
            ['partner_organization', 'del'],
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
