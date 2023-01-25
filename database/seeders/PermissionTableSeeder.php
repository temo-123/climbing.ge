<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'name' => 'User',
                'slug' => 'manage-users'
            ],
        ]);

        // $manageUser = new Permission();
        // $manageUser->name = 'User';
        // $manageUser->slug = 'manage-users';
        // $manageUser->save();

        // $createTasks = new Permission();
        // $createTasks->name = 'Admin';
        // $createTasks->slug = 'create-tasks';
        // $createTasks->save();
    }
}
