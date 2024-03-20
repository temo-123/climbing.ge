<?php

namespace App\Services;

use App\Models\Guide\Mount;
use App\Models\Guide\Locale_mount;

use Carbon\Carbon;

use App\Services\Abstract\LocaleContentService;

class MountSystemService extends LocaleContentService
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
                
        } 
        else {
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


    public static function get_local_mounts_use_locale($global_mount, $locale)
    {
        $mounts = array();

        $mounts = (new static)->get_locale_content_use_locale($global_mount, Locale_mount::class, '_mount_id', $locale);

        // if($locale == "ru"){
        //     foreach ($global_mount as $mount) {
        //         array_push($mounts, [
        //             "global_mount"=>$mount,
        //             "locale_mount"=>mount::find($mount->id)->ru_mount,
        //         ]);
        //     }
        // }
        // elseif ($locale == "ka") {
        //     foreach ($global_mount as $mount) {
        //         array_push($mounts, [
        //             "global_mount"=>$mount,
        //             "locale_mount"=>mount::find($mount->id)->ka_mount,
        //         ]);
        //     }
        // } 
        // else {
        //     foreach ($global_mount as $mount) {
        //         array_push($mounts, [
        //             "global_mount"=>$mount,
        //             "locale_mount"=>mount::find($mount->id)->us_mount,
        //         ]);
        //     }
        // }
        // dd($mount);
        return $mounts;
    }


    public static function get_local_mount_use_locale($mount_id, $locale)
    {
        // dd($mount_id);
        // $mounts = [];
        $global_mount = mount::where('id', '=', $mount_id,)->get();

        $mounts = (new static)->get_locale_content_use_locale($global_mount, Locale_mount::class, '_mount_id', $locale);

        // if($locale == "ru"){
        //     array_push($mounts, [
        //         "global_mount"=>mount::where('id', '=', $mount_id,)->get(),
        //         "locale_mount"=>mount::find($mount_id)->ru_mount
        //     ]);
        // }
        // elseif ($locale == "ka") {
        //     array_push($mounts, [
        //         "global_mount"=>mount::where('id', '=', $mount_id,)->get(),
        //         "locale_mount"=>mount::find($mount_id)->ka_mount
        //     ]);
        // } 
        // else {
        //     array_push($mounts, [
        //         "global_mount"=>mount::where('id', '=', $mount_id,)->get(),
        //         "locale_mount"=>mount::find($mount_id)->us_mount
        //     ]);
        // }
        // dd($mount);

        return $mounts;
    }
}