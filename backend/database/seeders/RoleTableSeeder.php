<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\User\Role;
use App\Models\User\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing data
        DB::table('role_permissions')->delete();
        DB::table('roles')->delete();

        $permissions = Permission::get();

        // =============================
        // ADMIN ROLE
        // =============================
        $admin = new Role();
        $admin->name = 'Admin';
        $admin->slug = 'admin';
        $admin->description = 'Site Administrator';
        $admin->save();

        // Admin gets all permissions except 'ban' subject
        foreach ($permissions as $permission) {
            if($permission['subject'] != 'ban'){
                $admin->permissions()->attach($permission);
            }
        }

        // =============================
        // VISUAL MANAGER
        // =============================
        $visual_menager = new Role();
        $visual_menager->name = 'Visual menager';
        $visual_menager->slug = 'visual_menader';
        $visual_menager->description = 'Visual menager';
        $visual_menager->save();

        $visual_permissions = Permission::whereIn('subject', [
            'gallery',
            'store_slides',
            'guide_slides',
            'site_data'
        ])->get();

        foreach ($visual_permissions as $permission) {
            $visual_menager->permissions()->attach($permission);
        }

        // =============================
        // MOUNTAIN ROUTES MANAGER
        // =============================
        $mount_menager = new Role();
        $mount_menager->name = 'Mountain routes menager';
        $mount_menager->slug = 'mountain_routes_menager';
        $mount_menager->description = 'Mountain routes menager';
        $mount_menager->save();

        $mount_permissions = Permission::whereIn('subject', [
            'mount_route',
            'mount_massive',
            'sector',
            'sector_local_images',
            'mtp',
            'mtp_pitch',
            'route'
        ])->get();

        foreach ($mount_permissions as $permission) {
            if($permission['subject'] != 'ban'){
                $mount_menager->permissions()->attach($permission);
            }
        }

        // =============================
        // PRIVATE PRODUCTS MANAGER
        // =============================
        $private_products_menager = new Role();
        $private_products_menager->name = 'Private products menager';
        $private_products_menager->slug = 'private_products_menager';
        $private_products_menager->description = 'Private products menager';
        $private_products_menager->save();

        $private_product_permissions = Permission::whereIn('subject', [
            'product_options',
            'sale_code',
            'product',
            'product_category',
            'product_brand'
        ])->get();

        foreach ($private_product_permissions as $permission) {
            // Skip 'show_all' on products
            if($permission['subject'] != 'ban' && 
               !($permission['subject'] == 'product' && $permission['action'] == 'show_all')){
                $private_products_menager->permissions()->attach($permission);
            }
        }

        // =============================
        // ALL PRODUCTS MANAGER
        // =============================
        $product_menager = new Role();
        $product_menager->name = 'All products menager';
        $product_menager->slug = 'all_products_menager';
        $product_menager->description = 'Product menager';
        $product_menager->save();

        foreach ($private_product_permissions as $permission) {
            // Include 'show_all' permissions for products
            if($permission['subject'] != 'ban'){
                $product_menager->permissions()->attach($permission);
            }
        }

        // =============================
        // WAREHOUSES MANAGER
        // =============================
        $warehouse_menager = new Role();
        $warehouse_menager->name = 'Warehouses menager';
        $warehouse_menager->slug = 'warehouses_menager';
        $warehouse_menager->description = 'Warehouses menager';
        $warehouse_menager->save();

        $warehouse_permissions = Permission::where('subject', '=', 'warehouse')->get();

        foreach ($warehouse_permissions as $permission) {
            $warehouse_menager->permissions()->attach($permission);
        }

        // =============================
        // FILMS MANAGER
        // =============================
        $film_menager = new Role();
        $film_menager->name = 'Films menager';
        $film_menager->slug = 'films_menager';
        $film_menager->description = 'Films menager';
        $film_menager->save();

        $film_permissions = Permission::whereIn('subject', [
            'film',
            'film_category',
            'film_teg'
        ])->get();

        foreach ($film_permissions as $permission) {
            $film_menager->permissions()->attach($permission);
        }

        // =============================
        // LOCALE BUSINESS MANAGER
        // =============================
        $locale_bisnes_menager = new Role();
        $locale_bisnes_menager->name = 'Locale bisnes menager';
        $locale_bisnes_menager->slug = 'Locale_bisnes_menager';
        $locale_bisnes_menager->description = 'Locale bisnes menager';
        $locale_bisnes_menager->save();

        $locale_bisnes_permissions = Permission::where('subject', '=', 'local_bisnes')->get();

        foreach ($locale_bisnes_permissions as $permission) {
            $locale_bisnes_menager->permissions()->attach($permission);
        }

        // =============================
        // EVENT MANAGER
        // =============================
        $event_menager = new Role();
        $event_menager->name = 'Event menager';
        $event_menager->slug = 'event_menager';
        $event_menager->description = 'Event menager';
        $event_menager->save();

        $event_permissions = Permission::whereIn('subject', [
            'event',
            'competition'
        ])->get();

        foreach ($event_permissions as $permission) {
            $event_menager->permissions()->attach($permission);
        }

        // =============================
        // SALE AND ORDER MANAGER
        // =============================
        $sale_and_order_menager = new Role();
        $sale_and_order_menager->name = 'Sale and order menager';
        $sale_and_order_menager->slug = 'sale_and_order_menager';
        $sale_and_order_menager->description = 'Sale and order menager';
        $sale_and_order_menager->save();

        $sale_and_order_permissions = Permission::whereIn('subject', [
            'product_options',
            'sale_code',
            'product',
            'order'
        ])->get();

        foreach ($sale_and_order_permissions as $permission) {
            $sale_and_order_menager->permissions()->attach($permission);
        }

        // =============================
        // ORDER MANAGER
        // =============================
        $order_menager = new Role();
        $order_menager->name = 'Order menager';
        $order_menager->slug = 'order_menager';
        $order_menager->description = 'Order menager';
        $order_menager->save();

        $order_permissions = Permission::where('subject', '=', 'order')->get();

        foreach ($order_permissions as $permission) {
            $order_menager->permissions()->attach($permission);
        }

        // =============================
        // CONTENT MANAGER
        // =============================
        $content_menager = new Role();
        $content_menager->name = 'Content menager';
        $content_menager->slug = 'content_menager';
        $content_menager->description = 'Content menager';
        $content_menager->save();

        $content_permissions = Permission::whereIn('subject', [
            'article',
            'site_data',
            'post'
        ])->get();

        foreach ($content_permissions as $permission) {
            $content_menager->permissions()->attach($permission);
        }

        // =============================
        // BLOG MANAGER
        // =============================
        $blog_menager = new Role();
        $blog_menager->name = 'Blog menager';
        $blog_menager->slug = 'blog_menager';
        $blog_menager->description = 'Blog menager';
        $blog_menager->save();

        $blog_permissions = Permission::where('subject', '=', 'blog')->get();

        foreach ($blog_permissions as $permission) {
            $blog_menager->permissions()->attach($permission);
        }

        // =============================
        // PRIVATE TOUR MANAGER
        // =============================
        $private_tour_menager = new Role();
        $private_tour_menager->name = 'Private tour menager';
        $private_tour_menager->slug = 'private_tour_menager';
        $private_tour_menager->description = 'tour menager';
        $private_tour_menager->save();

        $tour_permissions = Permission::whereIn('subject', [
            'tour_reservation',
            'tour',
            'tour_category'
        ])->get();

        foreach ($tour_permissions as $permission) {
            // Skip 'show_all' permissions
            if($permission['subject'] != 'ban' && 
               !($permission['action'] == 'show_all')){
                $private_tour_menager->permissions()->attach($permission);
            }
        }

        // =============================
        // ALL TOUR MANAGER
        // =============================
        $all_tour_menager = new Role();
        $all_tour_menager->name = 'All tour menager';
        $all_tour_menager->slug = 'all_tour_menager';
        $all_tour_menager->description = 'tour menager';
        $all_tour_menager->save();

        foreach ($tour_permissions as $permission) {
            // Include all tour permissions
            if($permission['subject'] != 'ban'){
                $all_tour_menager->permissions()->attach($permission);
            }
        }

        // =============================
        // USER ROLE
        // =============================
        $user = new Role();
        $user->name = 'User';
        $user->slug = 'user';
        $user->description = 'User';
        $user->save();

        // Basic user permissions
        $user_permissions = Permission::whereIn('action', [
            'show',
            'add'
        ])->whereIn('subject', [
            'my_comments',
            'favorite_products',
            'my_favorite_outdoor_area',
            'interested_events'
        ])->get();

        foreach ($user_permissions as $permission) {
            $user->permissions()->attach($permission);
        }

        // =============================
        // BAN ROLE
        // =============================
        $locked_user = new Role();
        $locked_user->name = 'Ban';
        $locked_user->slug = 'ban';
        $locked_user->description = 'Ban';
        $locked_user->save();

        $ban_permissions = Permission::where('subject', '=', 'ban')->get();
        foreach ($ban_permissions as $permission) {
            $locked_user->permissions()->attach($permission);
        }

        // =============================
        // WORKER ROLE (NEW)
        // =============================
        $worker = new Role();
        $worker->name = 'Worker';
        $worker->slug = 'worker';
        $worker->description = 'Site Worker';
        $worker->save();

        $worker_permissions = Permission::where('subject', '=', 'worker')
            ->orWhere('action', '=', 'show')
            ->get();

        foreach ($worker_permissions as $permission) {
            $worker->permissions()->attach($permission);
        }

        // =============================
        // TEAM MANAGER ROLE (NEW)
        // =============================
        $team_manager = new Role();
        $team_manager->name = 'Team Manager';
        $team_manager->slug = 'team_manager';
        $team_manager->description = 'Team Manager';
        $team_manager->save();

        $team_permissions = Permission::whereIn('subject', [
            'team',
            'sport_team'
        ])->get();

        foreach ($team_permissions as $permission) {
            $team_manager->permissions()->attach($permission);
        }

        // =============================
        // GUIDE MANAGER ROLE (NEW)
        // =============================
        $guide_manager = new Role();
        $guide_manager->name = 'Guide Manager';
        $guide_manager->slug = 'guide_manager';
        $guide_manager->description = 'Guide Manager';
        $guide_manager->save();

        $guide_permissions = Permission::whereIn('subject', [
            'guide_dashboard',
            'guide_task',
            'spot_region',
            'ice_sector',
            'ice_route',
            'routes_rewiew'
        ])->get();

        foreach ($guide_permissions as $permission) {
            $guide_manager->permissions()->attach($permission);
        }

        // =============================
        // SHOP MANAGER ROLE (NEW)
        // =============================
        $shop_manager = new Role();
        $shop_manager->name = 'Shop Manager';
        $shop_manager->slug = 'shop_manager';
        $shop_manager->description = 'Shop Manager';
        $shop_manager->save();

        $shop_permissions = Permission::whereIn('subject', [
            'shop_dashboard',
            'shop_task',
            'export',
            'shiping_region',
            'comments',
            'non_registered_commenter'
        ])->get();

        foreach ($shop_permissions as $permission) {
            $shop_manager->permissions()->attach($permission);
        }

        // =============================
        // LIVE CAMERA MANAGER ROLE (NEW)
        // =============================
        $live_camera_manager = new Role();
        $live_camera_manager->name = 'Live Camera Manager';
        $live_camera_manager->slug = 'live_camera_manager';
        $live_camera_manager->description = 'Live Camera Manager';
        $live_camera_manager->save();

        $live_camera_permissions = Permission::where('subject', '=', 'live_camera')->get();

        foreach ($live_camera_permissions as $permission) {
            $live_camera_manager->permissions()->attach($permission);
        }

        // =============================
        // USER MANAGEMENT ROLE (NEW)
        // =============================
        $user_management = new Role();
        $user_management->name = 'User Management';
        $user_management->slug = 'user_management';
        $user_management->description = 'User Management';
        $user_management->save();

        $user_management_permissions = Permission::where('subject', '=', 'user')
            ->orWhere('subject', '=', 'role')
            ->get();

        foreach ($user_management_permissions as $permission) {
            $user_management->permissions()->attach($permission);
        }

        // =============================
        // TASK CATEGORY MANAGER ROLE (NEW)
        // =============================
        $task_category_manager = new Role();
        $task_category_manager->name = 'Task Category Manager';
        $task_category_manager->slug = 'task_category_manager';
        $task_category_manager->description = 'Task Category Manager';
        $task_category_manager->save();

        $task_category_permissions = Permission::where('subject', '=', 'task_category')->get();

        foreach ($task_category_permissions as $permission) {
            $task_category_manager->permissions()->attach($permission);
        }

        // =============================
        // SITE FOLLOWERS MANAGER ROLE (NEW)
        // =============================
        $site_followers_manager = new Role();
        $site_followers_manager->name = 'Site Followers Manager';
        $site_followers_manager->slug = 'site_followers_manager';
        $site_followers_manager->description = 'Site Followers Manager';
        $site_followers_manager->save();

        $site_followers_permissions = Permission::where('subject', '=', 'site_folloers')->get();

        foreach ($site_followers_permissions as $permission) {
            $site_followers_manager->permissions()->attach($permission);
        }
    }
}

