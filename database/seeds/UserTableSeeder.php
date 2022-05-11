<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Models\Role;
use App\Models\Permission;

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
      $role_developer = Role::where('slug','web-developer')->first();
      $role_manager = Role::where('slug', 'project-manager')->first();

      $perm_createTasks = Permission::where('slug','create-tasks')->first();
      $perm_manageUsers = Permission::where('slug','manage-users')->first();

      $user1 = new User();
      $user1->name = 'user 1';
      $user1->email = 'test_user_1@'.env('BASE_URL');
      $user1->lang = 'us';
      $user1->grade = 'uk';
      $user1->password = bcrypt('secret');
      $user1->save();
      $user1->roles()->attach($role_developer);
      $user1->permissions()->attach($perm_createTasks);

      $user2 = new User();
      $user2->name = 'user 2';
      $user2->email = 'test_user_2@'.env('BASE_URL');
      $user2->lang = 'ka';
      $user2->grade = 'uiaa';
      $user2->password = bcrypt('secret');
      $user2->save();
      $user2->roles()->attach($role_manager);
      $user2->permissions()->attach($perm_manageUsers);

      $admin = new User();
      $admin->name = 'admin';
      $admin->email = 'admin@'.env('BASE_URL');
      $admin->lang = 'us';
      $admin->grade = 'yds';
      $admin->password = bcrypt('secret'); //https://bcrypt-generator.com/
      $admin->save();
      $admin->roles()->attach($role_admin);
    }
}
