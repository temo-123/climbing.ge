<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class SiteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sites')->insert([
            'us_info_id' => 1,
            'ka_info_id' => 2,
            // 'ru_info_id' => 3,

            'email' => 'info@'.env('BASE_URL'),
            'number' => '(+995) 598 45 70 48',

            'map' => '<iframe src="https://www.google.com/maps/d/embed?mid=1vp_Rgh8UmlN7nZGFGHChh_rRy1t5GdOv" width="100%" height="480"></iframe>',
        ]);
    }
}
