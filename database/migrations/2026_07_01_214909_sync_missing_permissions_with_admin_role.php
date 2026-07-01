<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Reconciles the live `permissions` table against what current controller
 * code actually checks via PermissionService::authorize(). Diffed the
 * production DB dump (storage/app/climbing_db_prod_dump.sql) against every
 * literal authorize('subject','action') call in app/Http/Controllers/ —
 * production's permissions table predates a lot of shipped code (Summit,
 * database stats, wall calculator export, corrected subject spellings like
 * shipping_region/product_option/sector_local_image replacing older
 * shiping_region/product_options/sector_local_images, generic task/
 * task_category used by TaskController/TaskCategoryController, plus many
 * plain 'show' permissions) — so on production today these actions are
 * unreachable for every role, admin included, because the permission rows
 * they check don't exist yet.
 *
 * Deliberately NOT using PermissionTableSeeder — it wipes and rebuilds
 * permissions/role_permissions/user_permissions from scratch, which would
 * erase every role's real, already-working grants. This migration only
 * inserts rows that don't already exist and only grants them to 'admin',
 * so it's safe to run against production as-is.
 */
return new class extends Migration
{
    protected function pairs(): array
    {
        return [
            ['comment', 'del'],
            ['comment', 'edit'],
            ['commenter', 'del'],
            ['commenter', 'show'],
            ['comment', 'show'],
            ['database', 'edit'],
            ['database', 'show'],
            ['donation', 'del'],
            ['donation', 'edit'],
            ['donation', 'show'],
            ['event', 'show'],
            ['film', 'show'],
            ['film_teg', 'show'],
            ['general_info', 'show'],
            ['head_slider', 'show'],
            ['live_camera', 'show'],
            ['local_bisnes', 'show'],
            ['mount_massive', 'show'],
            ['mount_route', 'show'],
            ['mtp_pitch', 'show'],
            ['mtp', 'show'],
            ['order', 'add'],
            ['order', 'edit'],
            ['order', 'show'],
            ['permission', 'edit'],
            ['permission', 'show'],
            ['post', 'show'],
            ['product_brand', 'show'],
            ['product_category', 'show'],
            ['product_feedback', 'show'],
            ['product_option', 'add'],
            ['product_option', 'del'],
            ['product_option', 'edit'],
            ['product_option', 'show'],
            ['product', 'show'],
            ['product_subcategory', 'add'],
            ['product_subcategory', 'del'],
            ['product_subcategory', 'edit'],
            ['product_subcategory', 'show'],
            ['region', 'add'],
            ['region', 'del'],
            ['region', 'edit'],
            ['region', 'show'],
            ['role', 'show'],
            ['route', 'show'],
            ['routes_rewiew', 'add'],
            ['routes_rewiew', 'edit'],
            ['sale_code', 'show'],
            ['sector_local_image', 'add'],
            ['sector_local_image', 'del'],
            ['sector_local_image', 'edit'],
            ['sector_local_image', 'show'],
            ['sector', 'show'],
            ['services', 'show'],
            ['shipping_region', 'add'],
            ['shipping_region', 'del'],
            ['shipping_region', 'edit'],
            ['shipping_region', 'show'],
            ['summit', 'add'],
            ['summit', 'del'],
            ['summit', 'edit'],
            ['summit', 'show'],
            ['task', 'add'],
            ['task_category', 'show'],
            ['task', 'del'],
            ['task', 'edit'],
            ['tour_category', 'show'],
            ['tour_reservation', 'add'],
            ['tour_reservation', 'edit'],
            ['tour', 'show'],
            ['user_notification', 'add'],
            ['user', 'show'],
            ['wall_calculator', 'export'],
            ['warehouse', 'show'],
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
