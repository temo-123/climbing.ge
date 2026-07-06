<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteDataPermissionSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        foreach (['show', 'edit'] as $action) {
            $exists = DB::table('permissions')
                ->where('subject', 'site_data')
                ->where('action', $action)
                ->exists();

            if (!$exists) {
                DB::table('permissions')->insert([
                    'subject' => 'site_data',
                    'action' => $action,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');

        if ($adminRoleId) {
            $permissionIds = DB::table('permissions')
                ->where('subject', 'site_data')
                ->whereIn('action', ['show', 'edit'])
                ->pluck('id');

            foreach ($permissionIds as $permissionId) {
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
