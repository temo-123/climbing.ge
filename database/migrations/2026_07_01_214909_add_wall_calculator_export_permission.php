<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the 'wall_calculator' / 'export' permission (gates the new wall
 * calculator PDF export feature) and grants it to the 'admin' role.
 *
 * Deliberately NOT using PermissionTableSeeder — that seeder wipes and
 * rebuilds permissions/role_permissions/user_permissions from scratch,
 * which would erase every other role's existing permission assignments.
 * This migration only inserts the one new row it needs, additively, and
 * only touches rows it created itself on rollback.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $exists = DB::table('permissions')
            ->where('subject', 'wall_calculator')
            ->where('action', 'export')
            ->exists();

        if (!$exists) {
            DB::table('permissions')->insert([
                'subject' => 'wall_calculator',
                'action' => 'export',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $permissionId = DB::table('permissions')
            ->where('subject', 'wall_calculator')
            ->where('action', 'export')
            ->value('id');

        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');

        if ($permissionId && $adminRoleId) {
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

    public function down(): void
    {
        $permissionId = DB::table('permissions')
            ->where('subject', 'wall_calculator')
            ->where('action', 'export')
            ->value('id');

        if ($permissionId) {
            DB::table('role_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('user_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }
    }
};
