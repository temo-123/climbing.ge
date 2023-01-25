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
            Locale_sitesSeeder::class,
            SiteTableSeeder::class,
            
            RoleTableSeeder::class,
            PermissionTableSeeder::class,

            UserTableSeeder::class
        ]);
    }
}