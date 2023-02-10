<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('roles')->insert([
          [
              'name' => 'User',
              'slug' => 'Users',
              'description' => 'Users'
          ],
          [
              'name' => 'admin',
              'slug' => 'Site Administrator',
              'description' => 'Site Administrator',
          ]
      ]);
    }
}
