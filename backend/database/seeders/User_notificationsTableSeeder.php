<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class User_notificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notificationTypes = [
            'favorite_outdoor',
            'favorite_product',
            'favorite_film',
            'interested_event',
            'news',
            'add_new_sector',
            'add_new_outdoor_spot',
            'add_new_ice_spot',
            'add_new_techtip',
            'add_new_gym',
            'add_new_product',
            'add_new_service',
        ];

        // Insert a row for each notification type for user_id 1
        foreach ($notificationTypes as $typeId => $typeName) {
            DB::table('user_notifications')->insert([
                'notification_type' => $typeId,
                'user_id' => 1,
            ]);
        }
    }
}
