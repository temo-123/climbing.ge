<?php

namespace App\Services;

use App\Models\Mount;

use Carbon\Carbon;

class GetMountSystemService
{
    public static function get_locale_mount_system_in_page($mount)
    {
        $locale = request()->segment(1, '');

        if($locale == "ru"){
            $ru_mount_id = $mount->id;
            $loc_mount = Mount::where('id', '=', $ru_mount_id,)->get();
            $locale_mount = array();
            array_push($locale_mount, [ 
                                        "id"=>$loc_mount[0]->id, 
                                        "name"=>$loc_mount[0]->name_ru, 
                                        "short_descriptio"=>$loc_mount[0]->short_description_ru, 
                                        "text"=>$loc_mount[0]->text_ru,
                                        "how_get"=>$loc_mount[0]->how_get_ru,
                                        "best_time"=>$loc_mount[0]->best_time_ru, 
                                        "weather"=>$loc_mount[0]->weather, 
                                        "map"=>$loc_mount[0]->map, 
                                        ]);
        }
        elseif ($locale == "ka") {
            $ka_mount_id = $mount->id;
            $loc_mount = Mount::where('id', '=', $ka_mount_id,)->get();
            $locale_mount = array();
            array_push($locale_mount, [
                                        "id"=>$loc_mount[0]->id, 
                                        "name"=>$loc_mount[0]->name_ka, 
                                        "short_descriptio"=>$loc_mount[0]->short_description_ka, 
                                        "text"=>$loc_mount[0]->text_ka,
                                        "how_get"=>$loc_mount[0]->how_get_ka,
                                        "best_time"=>$loc_mount[0]->best_time_ka, 
                                        "weather"=>$loc_mount[0]->weather, 
                                        "map"=>$loc_mount[0]->map, 
                                        ]);
                
        } else {
            $us_mount_id = $mount->id;
            $loc_mount = Mount::where('id', '=', $us_mount_id,)->get();
            $locale_mount = array();
            array_push($locale_mount, [ 
                                        "id"=>$loc_mount[0]->id, 
                                        "name"=>$loc_mount[0]->name, 
                                        "short_descriptio"=>$loc_mount[0]->short_description, 
                                        "text"=>$loc_mount[0]->text,
                                        "how_get"=>$loc_mount[0]->how_get,
                                        "best_time"=>$loc_mount[0]->best_time, 
                                        "weather"=>$loc_mount[0]->weather, 
                                        "map"=>$loc_mount[0]->map, 
                                        ]);
        }

        return $locale_mount[0];
    }


    public static function get_new_mount_pin($global_mount)
    {
        $time_array = array();

        foreach ($global_mount as $mount) {
            if ($mount[0][0]->created_at->lt(Carbon::now()->subDays(30))){
                $time = 0;
                array_push($time_array, ['id'=>$mount[0][0]->id, 'name'=>$mount['url_title'], 'time'=>$time]);
            } else {
                $time = 1;
                array_push($time_array, ['id'=>$mount[0][0]->id, 'name'=>$mount['url_title'], 'time'=>$time]);
            }
        }
        
        return $time_array;
    }
}