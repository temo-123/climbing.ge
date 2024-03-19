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

            // Locale_sitesSeeder::class,
            // SiteTableSeeder::class,

            // UserTableSeeder::class,
            // User_notificationsTableSeeder::class,
        ]);
    }
}
