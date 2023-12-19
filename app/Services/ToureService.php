<?php

namespace App\Services;

use App\Models\Locale_toure;
use App\Models\Toure;
use App\Models\Toure_image;

use App\Models\Service_image;

use Carbon\Carbon;

use App\Services\Abstract\LocaleContentService;

class ToureService extends LocaleContentService
{   
    public static function get_locale_tours_use_locale($global_toure, $locale)
    {
        $respoince = array();

        $tours = (new static)->get_locale_content_use_locale($global_toure, Locale_toure::class, '_toure_id', $locale);
        
        foreach ($tours as $toure) {
            $toure_images = Toure_image::where('toure_id', '=', $toure['global_data']['id'])->first();
            $image = array();
            if ($toure_images != NULL) {
                $image = $toure_images->image;
            }
            array_push($respoince, [
                "global_data"=>$toure['global_data'],
                "locale_data"=>$toure['locale_data'],
                "image"=>$image
            ]);
        }

        return $respoince;
    }

    public static function get_locale_toure_in_page($toure, $locale)
    {
        $locale_toure = [];

        $toure = (new static)->get_locale_content_in_page($toure, Locale_toure::class, '_toure_id',$locale);

        array_push($locale_toure, [
            "locale_data"=>$toure['locale_data'], 
            "global_data"=>$toure['global_data'], 
            "toure_images"=>$toure['global_data']->toure_images
        ]);

        return $locale_toure[0];
    }

    public static function add_toure() {
        
    }

    public static function edit_toure() {
        
    }

    public static function del_toure() {
        
    }
}