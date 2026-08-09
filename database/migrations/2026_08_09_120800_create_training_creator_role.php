<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds a 'Training Creator' role (slug 'training_creator') so training
 * content (workouts + plans for the companion mobile app) can be managed by
 * trusted non-admin users without granting full admin access. Gets show/
 * add/edit on training + training_plan, but not del — deleting stays
 * admin-only. Additive only, mirrors
 * 2026_07_01_214920_add_new_manager_roles_alongside_existing.php.
 */
return new class extends Migration
{
    protected function subjects(): array
    {
        return ['training', 'training_plan'];
    }

    protected function actions(): array
    {
        return ['show', 'add', 'edit'];
    }

    public function up(): void
    {
        $now = now();

        $roleId = DB::table('roles')->where('slug', 'training_creator')->value('id');
        if (!$roleId) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => 'Training Creator',
                'slug' => 'training_creator',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $permissionIds = DB::table('permissions')
            ->whereIn('subject', $this->subjects())
            ->whereIn('action', $this->actions())
            ->pluck('id');

        foreach ($permissionIds as $permissionId) {
            $alreadyGranted = DB::table('role_permissions')
                ->where('role_id', $roleId)
                ->where('permission_id', $permissionId)
                ->exists();

            if (!$alreadyGranted) {
                DB::table('role_permissions')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    /**
     * Removes exactly the role this migration creates (and its grants) —
     * never touches any other role.
     */
    public function down(): void
    {
        $roleId = DB::table('roles')->where('slug', 'training_creator')->value('id');
        if ($roleId) {
            DB::table('role_permissions')->where('role_id', $roleId)->delete();
            DB::table('user_role')->where('role_id', $roleId)->delete();
            DB::table('roles')->where('id', $roleId)->delete();
        }
    }
};
