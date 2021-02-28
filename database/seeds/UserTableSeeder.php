<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $role_admin = Role::where('name', 'admin')->first();

      $admin = new User();
      $admin->name = 'admin Name';
      $admin->email = 'admin@example.com';
      $admin->password = bcrypt('secret');
      $admin->save();

      $admin->roles()->attach($role_admin);
    }
}
