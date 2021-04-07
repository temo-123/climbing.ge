<?php

use Illuminate\Database\Seeder;

use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $role_user = new Role();
      $role_user->name = 'user';
      $role_user->description = 'Standart User';
      $role_user->save();

      $role_manager = new Role();
      $role_manager->name = 'manager';
      $role_manager->description = 'Global content manager';
      $role_manager->save();

      $role_ka_manager = new Role();
      $role_ka_manager->name = 'ka_manager';
      $role_ka_manager->description = 'Georgian Content mnager';
      $role_ka_manager->save();

      $role_ru_manager = new Role();
      $role_ru_manager->name = 'ru_mennager';
      $role_ru_manager->description = 'Russian content mennager';
      $role_ru_manager->save();

      $role_us_manager = new Role();
      $role_us_manager->name = 'us_manager';
      $role_us_manager->description = 'English content manager';
      $role_us_manager->save();

      $role_seller = new Role();
      $role_seller->name = 'seller';
      $role_seller->description = 'Product Seller';
      $role_seller->save();

      $role_admin = new Role();
      $role_admin->name = 'admin';
      $role_admin->description = 'Site Administrator';
      $role_admin->save();
    }
}
