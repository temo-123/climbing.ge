<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\User\Role;

class UserTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User();
        $admin->name = 'admin';
        $admin->email = 'admin@'.config('app.base_url');
        $admin->password = bcrypt('secret');
        $admin->save();

        $role_admin = Role::where('name', 'Admin')->first();
        $admin->role()->attach($role_admin);

        $user = new User();
        $user->name = 'user';
        $user->email = 'user@'.config('app.base_url');
        $user->password = bcrypt('secret');
        $user->save();

        $role_user = Role::where('name', 'User')->first();
        $user->role()->attach($role_user);
    }
}
