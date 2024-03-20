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
    //     DB::table('roles')->insert([
    //       [
    //           'name' => 'User',
    //           'slug' => 'users',
    //           'description' => 'Users'
    //       ],

    //       [
    //           'name' => 'Admin',
    //           'slug' => 'Site Administrator',
    //           'description' => 'Site Administrator',
    //       ]
    //   ]);


        $permissions = Permission::get();

        $admin = new Role();
        $admin->name = 'Admin';
        $admin->slug = 'admin';
        $admin->description = 'Site Administrator';
        $admin->save();

        foreach ($permissions as $permission) {
            if($permission['subject'] != 'worker' || $permission['subject'] != 'ban'){
                $admin->permissions()->attach($permission);
            }
        }

        $visual_permissions = Permission:: where('subject', '=', 'gallery')->
                                        get();

        $visual_menager = new Role();
        $visual_menager->name = 'Visual menager';
        $visual_menager->slug = 'visual_menader';
        $visual_menager->description = 'Visual menager';
        $visual_menager->save();

        foreach ($visual_permissions as $permission) {
            if($permission['subject'] != 'ban'){
                $visual_menager->permissions()->attach($permission);
            }
        }

        $mount_permissions = Permission:: where('subject', '=', 'mount_route')->
                                            orWhere('subject', '=', 'mount_massive')->
                                            get();

        $mount_menager = new Role();
        $mount_menager->name = 'Mountain routes menager';
        $mount_menager->slug = 'mountain_routes_menager';
        $mount_menager->description = 'Mountain routes menager';
        $mount_menager->save();

        foreach ($mount_permissions as $permission) {
            if($permission['subject'] != 'ban'){
                $mount_menager->permissions()->attach($permission);
            }
        }

        $sale_permissions = Permission:: where('subject', '=', 'product_options')->
                                        orWhere('subject', '=', 'sale_code')->
                                        orWhere('subject', '=', 'product')->
                                        orWhere('subject', '=', 'product_category')->
                                        get();

        $sale_menager = new Role();
        $sale_menager->name = 'Private products menager';
        $sale_menager->slug = 'private_products_menager';
        $sale_menager->description = 'Sale menager';
        $sale_menager->save();

        foreach ($sale_permissions as $permission) {
            if(
                $permission['subject'] != 'ban' && 
                ($permission['subject'] != 'product' && $permission['action'] != 'show_all')
            ){
                $sale_menager->permissions()->attach($permission);
            }
        }

        $sale_menager = new Role();
        $sale_menager->name = 'All products menager';
        $sale_menager->slug = 'all_products_menager';
        $sale_menager->description = 'Sale menager';
        $sale_menager->save();

        foreach ($sale_permissions as $permission) {
            if(
                $permission['subject'] != 'ban' && 
                ($permission['subject'] == 'product' && $permission['action'] == 'show_all') &&
                ($permission['subject'] == 'sale_code')||
                ($permission['subject'] == 'product_category')
            ){
                $sale_menager->permissions()->attach($permission);
            }
        }

        $film_permissions = Permission:: where('subject', '=', 'film')->
                                        orWhere('subject', '=', 'film_categories')->
                                        orWhere('subject', '=', 'film_teg')->
                                        get();

        $film_menager = new Role();
        $film_menager->name = 'Films menager';
        $film_menager->slug = 'films_menager';
        $film_menager->description = 'Films menager';
        $film_menager->save();

        foreach ($film_permissions as $permission) {
            if($permission['subject'] != 'ban'){
                $film_menager->permissions()->attach($permission);
            }
        }

        $locale_bisnes_permissions = Permission:: where('subject', '=', 'locale_bisnes')->
                                        get();

        $locale_bisnes_menager = new Role();
        $locale_bisnes_menager->name = 'Locale bisnes menager';
        $locale_bisnes_menager->slug = 'Locale_bisnes_menager';
        $locale_bisnes_menager->description = 'Locale bisnes menager';
        $locale_bisnes_menager->save();

        foreach ($locale_bisnes_permissions as $permission) {
            if($permission['subject'] != 'ban'){
                $locale_bisnes_menager->permissions()->attach($permission);
            }
        }

        $event_permissions = Permission:: where('subject', '=', 'event')->
                                        get();

        $event_menager = new Role();
        $event_menager->name = 'Event menager';
        $event_menager->slug = 'event_menager';
        $event_menager->description = 'Event menager';
        $event_menager->save();

        foreach ($event_permissions as $permission) {
            if($permission['subject'] != 'ban'){
                $event_menager->permissions()->attach($permission);
            }
        }

        $sale_and_order_permissions = Permission::  where('subject', '=', 'product_options')->
                                                    orWhere('subject', '=', 'sale_code')->
                                                    orWhere('subject', '=', 'product')->
                                                    orWhere('subject', '=', 'order')->
                                                    get();

        $sale_and_order_menager = new Role();
        $sale_and_order_menager->name = 'Sale and order menager';
        $sale_and_order_menager->slug = 'sale_and_order_menager';
        $sale_and_order_menager->description = 'Sale and order menager';
        $sale_and_order_menager->save();

        foreach ($sale_and_order_permissions as $permission) {
            if($permission['subject'] != 'ban'){
                $sale_and_order_menager->permissions()->attach($permission);
            }
        }

        $order_permissions = Permission::   where('subject', '=', 'order')->
                                            get();

        $order_menager = new Role();
        $order_menager->name = 'Order menager';
        $order_menager->slug = 'order_menager';
        $order_menager->description = 'Order menager';
        $order_menager->save();

        foreach ($order_permissions as $permission) {
            if($permission['subject'] != 'ban'){
                $order_menager->permissions()->attach($permission);
            }
        }

        $content_permissions = Permission::
                                            where('subject', '=', 'article')->
                                            orWhere('subject', '=', 'site_data')->
                                            get();

        $content_menager = new Role();
        $content_menager->name = 'Content menager';
        $content_menager->slug = 'content_menager';
        $content_menager->description = 'Content menager';
        $content_menager->save();

        foreach ($content_permissions as $permission) {
            if($permission['subject'] != 'ban'){
                $content_menager->permissions()->attach($permission);
            }
        }

        $tour_permissions = Permission::
                                            where('subject', '=', 'tour_reservation')->
                                            orWhere('subject', '=', 'tour')->
                                            orWhere('subject', '=', 'tour_category')->
                                            get();

        $tour_menager = new Role();
        $tour_menager->name = 'Private tour menager';
        $tour_menager->slug = 'private_tour_menager';
        $tour_menager->description = 'tour menager';
        $tour_menager->save();

        foreach ($tour_permissions as $permission) {
            if(
                $permission['subject'] != 'ban' && 
                ($permission['subject'] != 'tour' && $permission['action'] != 'show_all')
            ){
                $tour_menager->permissions()->attach($permission);
            }
        }


        $tour_menager = new Role();
        $tour_menager->name = 'All tour menager';
        $tour_menager->slug = 'all_tour_menager';
        $tour_menager->description = 'tour menager';
        $tour_menager->save();

        foreach ($tour_permissions as $permission) {
            if(
                $permission['subject'] != 'ban' && 
                ($permission['subject'] == 'tour' && $permission['action'] == 'show_all') &&
                ($permission['subject'] == 'tour_category' && $permission['action'] == 'show_all') &&
                ($permission['subject'] == 'tour_reservation' && $permission['action'] == 'show_all')
            ){
                $tour_menager->permissions()->attach($permission);
            }
        }

        
        $user = new Role();
        $user->name = 'User';
        $user->slug = 'user';
        $user->description = 'User';
        $user->save();


        $locked_user = new Role();
        $locked_user->name = 'Ban';
        $locked_user->slug = 'ban';
        $locked_user->description = 'Ban';
        $locked_user->save();

        $ban_permissions = Permission::
                                            where('subject', '=', 'ban')->
                                            get();
        foreach ($ban_permissions as $permission) {
                $locked_user->permissions()->attach($permission);
        }
    }
}
