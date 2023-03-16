<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class User_notificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_notifications')->insert([
            'favorite_outdoor' => 1,
            'favorite_product' => 1,
            'favorite_film' => 1,
            'interested_event' => 1,
            'news' => 1,
            'add_new_sector' => 1,
            'add_new_outdoor_spot' => 1,
            'add_new_ice_spot' => 1,
            'add_new_techtip' => 1,
            'add_new_gym' => 1,
            'add_new_product' => 1,
            '1add_new_service' => 1,
            
            'user_id' => 1,
        ]);
    }
}
