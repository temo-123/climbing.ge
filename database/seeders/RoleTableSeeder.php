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
              'name' => 'Manager',
              'slug' => 'Global content manager',
              'description' => 'Global content manager',
          ],
          [
              'name' => 'ka_manager',
              'slug' => 'Georgian Content mnager',
              'description' => 'Georgian Content mnager',
          ],
          [
              'name' => 'ru_mannager',
              'slug' => 'Russian content mennager',
              'description' => 'Russian content mennager',
          ],
          [
              'name' => 'us_manager',
              'slug' => 'English content manager',
              'description' => 'English content manager',
          ],
          [
              'name' => 'seller',
              'slug' => 'Product Seller',
              'description' => 'Product Seller',
          ],
          [
              'name' => 'admin',
              'slug' => 'Site Administrator',
              'description' => 'Site Administrator',
          ]
      ]);
    }
}
