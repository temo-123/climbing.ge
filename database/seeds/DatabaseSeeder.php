<?php

use Illuminate\Database\Seeder;

// use Database\Seeders\SiteTableSeeder;
// use Database\Seeders\RoleTableSeeder;
use Database\Seeders\PermissionTableSeeder;
// use Database\Seeders\UserTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seeder site info.
        $this->call(SiteTableSeeder::class);

        // Role comes before User seeder here.
        $this->call(RoleTableSeeder::class);

        // Seeder permissions
        $this->call(PermissionTableSeeder::class);

        // User seeder will use the roles above created.
        $this->call(UserTableSeeder::class);
    }
}
