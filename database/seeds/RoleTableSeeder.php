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
      $role_manager->description = 'Content Manager';
      $role_manager->save();

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
