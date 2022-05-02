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
      $admin->name = 'admin';
      $admin->email = 'admin@'.config('app.url');
      $admin->password = bcrypt('secret'); //https://bcrypt-generator.com/
      $admin->save();

      $admin->roles()->attach($role_admin);
    }
}
