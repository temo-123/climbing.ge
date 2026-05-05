<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User\Role;
use App\Models\User\Permission;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('role_permissions')->delete();
        DB::table('roles')->delete();

        // ── Core ──────────────────────────────────────────────────────
        $admin = $this->make('Admin', 'admin');
        foreach (Permission::all() as $p) {
            $admin->permissions()->attach($p);
        }

        $this->make('User', 'user');
        $this->make('Ban',  'ban');

        // ── Guide content ─────────────────────────────────────────────
        $this->makeWithSubjects('Article Manager', 'article_manager', [
            'article', 'general_info', 'head_slider',
            'event', 'local_bisnes', 'live_camera',
            'region', 'donation',
        ]);

        $this->makeWithSubjects('Climbing Sector Manager', 'climbing_sector_manager', [
            'sector', 'sector_local_image',
            'route', 'mtp', 'mtp_pitch', 'routes_rewiew',
        ]);

        $this->makeWithSubjects('Mountain Route Manager', 'mountain_route_manager', [
            'mount_massive', 'mount_route', 'mtp', 'mtp_pitch', 'region',
        ]);

        $this->makeWithSubjects('Summit Manager', 'summit_manager', ['summit']);

        $this->makeWithSubjects('Full Guide Manager', 'full_guide_manager', [
            'article', 'general_info', 'head_slider', 'event',
            'local_bisnes', 'live_camera', 'region', 'donation',
            'sector', 'sector_local_image', 'route', 'mtp', 'mtp_pitch', 'routes_rewiew',
            'mount_massive', 'mount_route',
            'summit',
            'team',
        ]);

        // ── Guide operations ──────────────────────────────────────────
        $this->makeWithSubjects('Guide Task Manager', 'guide_task_manager', [
            'guide_task', 'guide_task_category',
        ]);

        // ── Blog / films / media ──────────────────────────────────────
        $this->makeWithSubjects('Blog Manager', 'blog_manager', ['post']);

        $this->makeWithSubjects('Film Manager', 'film_manager', ['film', 'film_teg']);

        $this->makeWithSubjects('Media Manager', 'media_manager', [
            'film', 'film_teg', 'post', 'head_slider',
        ]);

        // ── Shop content ──────────────────────────────────────────────
        $this->makeWithSubjects('Product Manager', 'product_manager', [
            'product', 'product_brand', 'product_category',
            'product_subcategory', 'product_option', 'product_feedback',
            'sale_code', 'services', 'shipping_region',
        ]);

        $this->makeWithSubjects('Tour Manager', 'tour_manager', [
            'tour', 'tour_category', 'tour_reservation',
        ]);

        $this->makeWithSubjects('Full Shop Manager', 'full_shop_manager', [
            'product', 'product_brand', 'product_category',
            'product_subcategory', 'product_option', 'product_feedback',
            'order', 'warehouse', 'sale_code', 'services', 'shipping_region',
            'tour', 'tour_category', 'tour_reservation',
        ]);

        // ── Shop operations ───────────────────────────────────────────
        $this->makeWithSubjects('Shop Task Manager', 'shop_task_manager', [
            'shop_task', 'shop_task_category',
        ]);

        $this->makeWithSubjects('Order Manager', 'order_manager', ['order']);

        $this->makeWithSubjects('Warehouse Manager', 'warehouse_manager', ['warehouse']);

        // ── Moderation ────────────────────────────────────────────────
        $moderator = $this->make('Content Moderator', 'content_moderator');
        $modPerms = Permission::where(function ($q) {
            $q->whereIn('subject', ['comment', 'product_feedback', 'routes_rewiew'])
              ->whereIn('action', ['show', 'edit', 'del']);
        })->orWhere(function ($q) {
            $q->where('subject', 'commenter')
              ->whereIn('action', ['show', 'del']);
        })->get();
        foreach ($modPerms as $p) {
            $moderator->permissions()->attach($p);
        }

        $this->makeWithSubjects('Team Manager', 'team_manager', ['team']);
    }

    private function make(string $name, string $slug): Role
    {
        $role = new Role();
        $role->name = $name;
        $role->slug = $slug;
        $role->save();
        return $role;
    }

    private function makeWithSubjects(string $name, string $slug, array $subjects): Role
    {
        $role = $this->make($name, $slug);
        $permissions = Permission::whereIn('subject', $subjects)->get();
        foreach ($permissions as $p) {
            $role->permissions()->attach($p);
        }
        return $role;
    }
}
