<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;

class SiteInfoController extends Controller
{
    public function get_site_data()
    {
        $site_info = array();

        $locale = request()->segment(1, '');

        if($locale == "ru"){
            $info = Site::select()->first();
            array_push($site_info, [
                    'title'=>$info->title_ru,
                    'text'=>$info->text_ru,
                    'short_description'=>$info->short_description_ru,
                    'ice_description'=>$info->ice_description_ru,
                    'mount_description'=>$info->mount_description_ru,
                    'outdoor_description'=>$info->outdoor_description_ru,
                    'indoor_description'=>$info->indoor_description_ru,
                    'topo_description'=>$info->topo_description_ru,
                    'what_we_do_description'=>$info->what_we_do_description_ru,
                    'shop_description'=>$info->shop_description_ru,
                    'services_description'=>$info->services_description_ru,

                    'email'=>$info->email,
                    'fb_link'=>$info->fb_link,
                    'google_link'=>$info->google_link,
                    'twit_link'=>$info->twit_link,
                    'inst_link'=>$info->inst_link,
                    'map'=>$info->map,
                    'number'=>$info->number,
                ]);
        }
        elseif ($locale == "ka") {
            $info = Site::select()->first();
            array_push($site_info, [
                    'title'=>$info->title_ka,
                    'text'=>$info->text_ka,
                    'short_description'=>$info->short_description_ka,
                    'ice_description'=>$info->ice_description_ka,
                    'mount_description'=>$info->mount_description_ka,
                    'outdoor_description'=>$info->outdoor_description_ka,
                    'indoor_description'=>$info->indoor_description_ka,
                    'topo_description'=>$info->topo_description_ka,
                    'what_we_do_description'=>$info->what_we_do_description_ka,
                    'shop_description'=>$info->shop_description_ka,
                    'services_description'=>$info->services_description_ka,

                    'email'=>$info->email,
                    'fb_link'=>$info->fb_link,
                    'google_link'=>$info->google_link,
                    'twit_link'=>$info->twit_link,
                    'inst_link'=>$info->inst_link,
                    'map'=>$info->map,
                    'number'=>$info->number,
                ]);
        } 
        else {
            $info = Site::select()->first();
            array_push($site_info, [
                    'title'=>$info->title,
                    'text'=>$info->text,
                    'short_description'=>$info->short_description,
                    'ice_description'=>$info->ice_description,
                    'mount_description'=>$info->mount_description,
                    'outdoor_description'=>$info->outdoor_description,
                    'indoor_description'=>$info->indoor_description,
                    'topo_description'=>$info->topo_description,
                    'what_we_do_description'=>$info->what_we_do_description,
                    'shop_description'=>$info->shop_description,
                    'services_description'=>$info->services_description,

                    'email'=>$info->email,
                    'fb_link'=>$info->fb_link,
                    'google_link'=>$info->google_link,
                    'twit_link'=>$info->twit_link,
                    'inst_link'=>$info->inst_link,
                    'map'=>$info->map,
                    'number'=>$info->number,
                ]);
        }
        return $site_info;
    }
}
