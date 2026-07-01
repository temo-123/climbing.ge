<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('role_permissions')->delete();
        DB::table('user_permissions')->delete();
        DB::table('permissions')->delete();

        $now = now();

        DB::table('permissions')->insert(array_map(fn($p) => array_merge($p, [
            'created_at' => $now,
            'updated_at' => $now,
        ]), [
            // GUIDE: Articles
            ['subject' => 'article',             'action' => 'show'],
            ['subject' => 'article',             'action' => 'add'],
            ['subject' => 'article',             'action' => 'edit'],
            ['subject' => 'article',             'action' => 'del'],

            // GUIDE: Comments
            ['subject' => 'comment',             'action' => 'show'],
            ['subject' => 'comment',             'action' => 'edit'],
            ['subject' => 'comment',             'action' => 'del'],
            ['subject' => 'commenter',           'action' => 'show'],
            ['subject' => 'commenter',           'action' => 'del'],

            // GUIDE: Donations
            ['subject' => 'donation',            'action' => 'show'],
            ['subject' => 'donation',            'action' => 'edit'],
            ['subject' => 'donation',            'action' => 'del'],

            // GUIDE: Events
            ['subject' => 'event',               'action' => 'show'],
            ['subject' => 'event',               'action' => 'add'],
            ['subject' => 'event',               'action' => 'edit'],
            ['subject' => 'event',               'action' => 'del'],

            // GUIDE: General Info
            ['subject' => 'general_info',        'action' => 'show'],
            ['subject' => 'general_info',        'action' => 'add'],
            ['subject' => 'general_info',        'action' => 'edit'],
            ['subject' => 'general_info',        'action' => 'del'],

            // GUIDE: Head Slider
            ['subject' => 'head_slider',         'action' => 'show'],
            ['subject' => 'head_slider',         'action' => 'add'],
            ['subject' => 'head_slider',         'action' => 'edit'],
            ['subject' => 'head_slider',         'action' => 'del'],

            // GUIDE: Live Cameras
            ['subject' => 'live_camera',         'action' => 'show'],
            ['subject' => 'live_camera',         'action' => 'add'],
            ['subject' => 'live_camera',         'action' => 'edit'],
            ['subject' => 'live_camera',         'action' => 'del'],

            // GUIDE: Local Business
            ['subject' => 'local_bisnes',        'action' => 'show'],
            ['subject' => 'local_bisnes',        'action' => 'add'],
            ['subject' => 'local_bisnes',        'action' => 'edit'],
            ['subject' => 'local_bisnes',        'action' => 'del'],

            // GUIDE: Mountain Massifs
            ['subject' => 'mount_massive',       'action' => 'show'],
            ['subject' => 'mount_massive',       'action' => 'add'],
            ['subject' => 'mount_massive',       'action' => 'edit'],
            ['subject' => 'mount_massive',       'action' => 'del'],

            // GUIDE: Mountain Routes
            ['subject' => 'mount_route',         'action' => 'show'],
            ['subject' => 'mount_route',         'action' => 'add'],
            ['subject' => 'mount_route',         'action' => 'edit'],
            ['subject' => 'mount_route',         'action' => 'del'],

            // GUIDE: Multi-Pitch (MTP)
            ['subject' => 'mtp',                 'action' => 'show'],
            ['subject' => 'mtp',                 'action' => 'add'],
            ['subject' => 'mtp',                 'action' => 'edit'],
            ['subject' => 'mtp',                 'action' => 'del'],

            // GUIDE: MTP Pitches
            ['subject' => 'mtp_pitch',           'action' => 'show'],
            ['subject' => 'mtp_pitch',           'action' => 'add'],
            ['subject' => 'mtp_pitch',           'action' => 'edit'],
            ['subject' => 'mtp_pitch',           'action' => 'del'],

            // GUIDE: Regions
            ['subject' => 'region',              'action' => 'show'],
            ['subject' => 'region',              'action' => 'add'],
            ['subject' => 'region',              'action' => 'edit'],
            ['subject' => 'region',              'action' => 'del'],

            // GUIDE: Climbing Routes
            ['subject' => 'route',               'action' => 'show'],
            ['subject' => 'route',               'action' => 'add'],
            ['subject' => 'route',               'action' => 'edit'],
            ['subject' => 'route',               'action' => 'del'],

            // GUIDE: Route Reviews/Ratings
            ['subject' => 'routes_rewiew',       'action' => 'show'],
            ['subject' => 'routes_rewiew',       'action' => 'add'],
            ['subject' => 'routes_rewiew',       'action' => 'edit'],
            ['subject' => 'routes_rewiew',       'action' => 'del'],

            // GUIDE: Climbing Sectors
            ['subject' => 'sector',              'action' => 'show'],
            ['subject' => 'sector',              'action' => 'add'],
            ['subject' => 'sector',              'action' => 'edit'],
            ['subject' => 'sector',              'action' => 'del'],

            // GUIDE: Sector Local Images (canvas)
            ['subject' => 'sector_local_image',  'action' => 'show'],
            ['subject' => 'sector_local_image',  'action' => 'add'],
            ['subject' => 'sector_local_image',  'action' => 'edit'],
            ['subject' => 'sector_local_image',  'action' => 'del'],

            // GUIDE: Team Members
            ['subject' => 'team',                'action' => 'show'],
            ['subject' => 'team',                'action' => 'edit'],

            // GUIDE: Tasks
            ['subject' => 'guide_task',          'action' => 'show'],
            ['subject' => 'guide_task',          'action' => 'add'],
            ['subject' => 'guide_task',          'action' => 'edit'],
            ['subject' => 'guide_task',          'action' => 'del'],
            ['subject' => 'guide_task_category', 'action' => 'show'],
            ['subject' => 'guide_task_category', 'action' => 'add'],
            ['subject' => 'guide_task_category', 'action' => 'edit'],
            ['subject' => 'guide_task_category', 'action' => 'del'],

            // BLOG
            ['subject' => 'post',                'action' => 'show'],
            ['subject' => 'post',                'action' => 'add'],
            ['subject' => 'post',                'action' => 'edit'],
            ['subject' => 'post',                'action' => 'del'],

            // FILMS
            ['subject' => 'film',                'action' => 'show'],
            ['subject' => 'film',                'action' => 'add'],
            ['subject' => 'film',                'action' => 'edit'],
            ['subject' => 'film',                'action' => 'del'],
            ['subject' => 'film_teg',            'action' => 'show'],
            ['subject' => 'film_teg',            'action' => 'add'],
            ['subject' => 'film_teg',            'action' => 'edit'],
            ['subject' => 'film_teg',            'action' => 'del'],

            // SUMMIT
            ['subject' => 'summit',              'action' => 'show'],
            ['subject' => 'summit',              'action' => 'add'],
            ['subject' => 'summit',              'action' => 'edit'],
            ['subject' => 'summit',              'action' => 'del'],

            // SHOP: Orders
            ['subject' => 'order',               'action' => 'show'],
            ['subject' => 'order',               'action' => 'add'],
            ['subject' => 'order',               'action' => 'edit'],
            ['subject' => 'order',               'action' => 'del'],

            // SHOP: Products
            ['subject' => 'product',             'action' => 'show'],
            ['subject' => 'product',             'action' => 'add'],
            ['subject' => 'product',             'action' => 'edit'],
            ['subject' => 'product',             'action' => 'del'],
            ['subject' => 'product_brand',       'action' => 'show'],
            ['subject' => 'product_brand',       'action' => 'add'],
            ['subject' => 'product_brand',       'action' => 'edit'],
            ['subject' => 'product_brand',       'action' => 'del'],
            ['subject' => 'product_category',    'action' => 'show'],
            ['subject' => 'product_category',    'action' => 'add'],
            ['subject' => 'product_category',    'action' => 'edit'],
            ['subject' => 'product_category',    'action' => 'del'],
            ['subject' => 'product_subcategory', 'action' => 'show'],
            ['subject' => 'product_subcategory', 'action' => 'add'],
            ['subject' => 'product_subcategory', 'action' => 'edit'],
            ['subject' => 'product_subcategory', 'action' => 'del'],
            ['subject' => 'product_option',      'action' => 'show'],
            ['subject' => 'product_option',      'action' => 'add'],
            ['subject' => 'product_option',      'action' => 'edit'],
            ['subject' => 'product_option',      'action' => 'del'],
            ['subject' => 'product_feedback',    'action' => 'show'],
            ['subject' => 'product_feedback',    'action' => 'edit'],
            ['subject' => 'product_feedback',    'action' => 'del'],

            // SHOP: Sales & Promotions
            ['subject' => 'sale_code',           'action' => 'show'],
            ['subject' => 'sale_code',           'action' => 'add'],
            ['subject' => 'sale_code',           'action' => 'edit'],
            ['subject' => 'sale_code',           'action' => 'del'],

            // SHOP: Services
            ['subject' => 'services',            'action' => 'show'],
            ['subject' => 'services',            'action' => 'add'],
            ['subject' => 'services',            'action' => 'edit'],
            ['subject' => 'services',            'action' => 'del'],

            // SHOP: Shipping
            ['subject' => 'shipping_region',     'action' => 'show'],
            ['subject' => 'shipping_region',     'action' => 'add'],
            ['subject' => 'shipping_region',     'action' => 'edit'],
            ['subject' => 'shipping_region',     'action' => 'del'],

            // SHOP: Tours
            ['subject' => 'tour',                'action' => 'show'],
            ['subject' => 'tour',                'action' => 'add'],
            ['subject' => 'tour',                'action' => 'edit'],
            ['subject' => 'tour',                'action' => 'del'],
            ['subject' => 'tour_category',       'action' => 'show'],
            ['subject' => 'tour_category',       'action' => 'add'],
            ['subject' => 'tour_category',       'action' => 'edit'],
            ['subject' => 'tour_category',       'action' => 'del'],
            ['subject' => 'tour_reservation',    'action' => 'show'],
            ['subject' => 'tour_reservation',    'action' => 'add'],
            ['subject' => 'tour_reservation',    'action' => 'edit'],
            ['subject' => 'tour_reservation',    'action' => 'del'],

            // SHOP: Warehouse
            ['subject' => 'warehouse',           'action' => 'show'],
            ['subject' => 'warehouse',           'action' => 'add'],
            ['subject' => 'warehouse',           'action' => 'edit'],
            ['subject' => 'warehouse',           'action' => 'del'],

            // SHOP: Tasks
            ['subject' => 'shop_task',           'action' => 'show'],
            ['subject' => 'shop_task',           'action' => 'add'],
            ['subject' => 'shop_task',           'action' => 'edit'],
            ['subject' => 'shop_task',           'action' => 'del'],
            ['subject' => 'shop_task_category',  'action' => 'show'],
            ['subject' => 'shop_task_category',  'action' => 'add'],
            ['subject' => 'shop_task_category',  'action' => 'edit'],
            ['subject' => 'shop_task_category',  'action' => 'del'],

            // ADMIN: Users & Access Control
            ['subject' => 'user',                'action' => 'show'],
            ['subject' => 'user',                'action' => 'add'],
            ['subject' => 'user',                'action' => 'edit'],
            ['subject' => 'user',                'action' => 'del'],
            ['subject' => 'user',                'action' => 'create_ban'],
            ['subject' => 'role',                'action' => 'show'],
            ['subject' => 'role',                'action' => 'add'],
            ['subject' => 'role',                'action' => 'edit'],
            ['subject' => 'role',                'action' => 'del'],
            ['subject' => 'permission',          'action' => 'show'],
            ['subject' => 'permission',          'action' => 'add'],
            ['subject' => 'permission',          'action' => 'edit'],
            ['subject' => 'permission',          'action' => 'del'],

            // ADMIN: Special tools
            ['subject' => 'user_notification',   'action' => 'add'],
            ['subject' => 'export',              'action' => 'show'],
            ['subject' => 'database',            'action' => 'show'],
            ['subject' => 'database',            'action' => 'edit'],
            ['subject' => 'wall_calculator',     'action' => 'export'],
        ]));
    }
}
