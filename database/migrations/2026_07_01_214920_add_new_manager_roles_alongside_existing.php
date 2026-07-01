<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the redesigned, more granular manager roles from RoleTableSeeder.php
 * (article_manager, product_manager, full_shop_manager, content_moderator,
 * etc.) onto the live database ALONGSIDE production's existing 25 roles —
 * deliberately not replacing or renaming anything production already has.
 *
 * Production's roles predate this redesign and use different slugs entirely
 * (visual_menader, warehouses_menager, sale_and_order_menager, ...), with
 * real users currently assigned to them via user_role. RoleTableSeeder.php
 * itself deletes every role and role_permission before rebuilding — running
 * it as-is against production would orphan every one of those live
 * assignments. This migration reproduces the SAME new roles + permission
 * grants that seeder would create, but purely additively: nothing existing
 * is deleted, modified, or renamed.
 *
 * 'admin', 'user', 'ban', and 'team_manager' are skipped here because
 * production already has roles under those exact slugs — creating a second
 * row with the same slug would leave two ambiguous rows for anything doing
 * roles->where('slug', ...)->first().
 */
return new class extends Migration
{
    /** @return array<int, array{name: string, slug: string, subjects?: array<int, string>}> */
    protected function roleDefs(): array
    {
        return [
            ['name' => 'Article Manager', 'slug' => 'article_manager', 'subjects' => [
                'article', 'general_info', 'head_slider', 'event', 'local_bisnes', 'live_camera', 'region', 'donation',
            ]],
            ['name' => 'Climbing Sector Manager', 'slug' => 'climbing_sector_manager', 'subjects' => [
                'sector', 'sector_local_image', 'route', 'mtp', 'mtp_pitch', 'routes_rewiew',
            ]],
            ['name' => 'Mountain Route Manager', 'slug' => 'mountain_route_manager', 'subjects' => [
                'mount_massive', 'mount_route', 'mtp', 'mtp_pitch', 'region',
            ]],
            ['name' => 'Summit Manager', 'slug' => 'summit_manager', 'subjects' => ['summit']],
            ['name' => 'Full Guide Manager', 'slug' => 'full_guide_manager', 'subjects' => [
                'article', 'general_info', 'head_slider', 'event', 'local_bisnes', 'live_camera', 'region', 'donation',
                'sector', 'sector_local_image', 'route', 'mtp', 'mtp_pitch', 'routes_rewiew',
                'mount_massive', 'mount_route', 'summit', 'team',
            ]],
            ['name' => 'Guide Task Manager', 'slug' => 'guide_task_manager', 'subjects' => [
                'guide_task', 'guide_task_category',
            ]],
            ['name' => 'Blog Manager', 'slug' => 'blog_manager', 'subjects' => ['post']],
            ['name' => 'Film Manager', 'slug' => 'film_manager', 'subjects' => ['film', 'film_teg']],
            ['name' => 'Media Manager', 'slug' => 'media_manager', 'subjects' => [
                'film', 'film_teg', 'post', 'head_slider',
            ]],
            ['name' => 'Product Manager', 'slug' => 'product_manager', 'subjects' => [
                'product', 'product_brand', 'product_category', 'product_subcategory',
                'product_option', 'product_feedback', 'sale_code', 'services', 'shipping_region',
            ]],
            ['name' => 'Tour Manager', 'slug' => 'tour_manager', 'subjects' => [
                'tour', 'tour_category', 'tour_reservation',
            ]],
            ['name' => 'Full Shop Manager', 'slug' => 'full_shop_manager', 'subjects' => [
                'product', 'product_brand', 'product_category', 'product_subcategory',
                'product_option', 'product_feedback', 'order', 'warehouse', 'sale_code', 'services',
                'shipping_region', 'tour', 'tour_category', 'tour_reservation',
            ]],
            ['name' => 'Shop Task Manager', 'slug' => 'shop_task_manager', 'subjects' => [
                'shop_task', 'shop_task_category',
            ]],
            ['name' => 'Order Manager', 'slug' => 'order_manager', 'subjects' => ['order']],
            ['name' => 'Warehouse Manager', 'slug' => 'warehouse_manager', 'subjects' => ['warehouse']],
        ];
    }

    public function up(): void
    {
        $now = now();

        foreach ($this->roleDefs() as $def) {
            $roleId = DB::table('roles')->where('slug', $def['slug'])->value('id');
            if (!$roleId) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => $def['name'],
                    'slug' => $def['slug'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $permissionIds = DB::table('permissions')->whereIn('subject', $def['subjects'])->pluck('id');
            $this->grant($roleId, $permissionIds, $now);
        }

        // Content Moderator: a subject+action combination, not whole
        // subjects, matching RoleTableSeeder's own Permission::where(...)
        // query exactly (comment/product_feedback/routes_rewiew show+edit+del,
        // plus commenter show+del).
        $moderatorRoleId = DB::table('roles')->where('slug', 'content_moderator')->value('id');
        if (!$moderatorRoleId) {
            $moderatorRoleId = DB::table('roles')->insertGetId([
                'name' => 'Content Moderator',
                'slug' => 'content_moderator',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $modPermissionIds = DB::table('permissions')
            ->where(function ($q) {
                $q->whereIn('subject', ['comment', 'product_feedback', 'routes_rewiew'])
                    ->whereIn('action', ['show', 'edit', 'del']);
            })
            ->orWhere(function ($q) {
                $q->where('subject', 'commenter')->whereIn('action', ['show', 'del']);
            })
            ->pluck('id');
        $this->grant($moderatorRoleId, $modPermissionIds, $now);
    }

    protected function grant($roleId, $permissionIds, $now): void
    {
        foreach ($permissionIds as $permissionId) {
            $exists = DB::table('role_permissions')
                ->where('role_id', $roleId)
                ->where('permission_id', $permissionId)
                ->exists();
            if (!$exists) {
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
     * Removes exactly the roles this migration creates (and their grants) —
     * never touches production's pre-existing roles.
     */
    public function down(): void
    {
        $slugs = array_column($this->roleDefs(), 'slug');
        $slugs[] = 'content_moderator';

        $roleIds = DB::table('roles')->whereIn('slug', $slugs)->pluck('id');
        DB::table('role_permissions')->whereIn('role_id', $roleIds)->delete();
        DB::table('user_role')->whereIn('role_id', $roleIds)->delete();
        DB::table('roles')->whereIn('id', $roleIds)->delete();
    }
};
