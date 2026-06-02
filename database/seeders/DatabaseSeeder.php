<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionTableSeeder::class,
            RoleTableSeeder::class,
            AssignRolesByUserIdSeeder::class,

            // UserRoleSeeder::class,          // creates users by email — use for fresh installs
            // LocaleSiteSeeder::class,
            // SiteTableSeeder::class,
            // UserTableSeeder::class,
            // User_notificationsTableSeeder::class,
        ]);
    }
}

