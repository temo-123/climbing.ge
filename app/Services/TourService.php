<?php

namespace App\Services;

use App\Models\Shop\Locale_tour;
use App\Models\Shop\Tour;
use App\Models\Shop\Tour_image;

use Carbon\Carbon;

use App\Services\Abstract\LocaleContentService;

class TourService extends LocaleContentService
{   
    public static function get_locale_tours_use_locale($global_tour, $locale)
    {
        $respoince = array();

        $tours = (new static)->get_locale_content_use_locale($global_tour, Locale_tour::class, '_tour_id', $locale);
        
        foreach ($tours as $tour) {
            $tour_images = Tour_image::where('tour_id', '=', $tour['global_data']['id'])->first();
            $image = array();
            if ($tour_images != NULL) {
                $image = $tour_images->image;
            }
            array_push($respoince, [
                "global_data"=>$tour['global_data'],
                "locale_data"=>$tour['locale_data'],
                "image"=>$image
            ]);
        }

        return $respoince;
    }

    public static function get_locale_tour_in_page($tour, $locale)
    {
        $locale_tour = [];

        $tour = (new static)->get_locale_content_in_page($tour, Locale_tour::class, '_tour_id',$locale);

        array_push($locale_tour, [
            "locale_data"=>$tour['locale_data'], 
            "global_data"=>$tour['global_data'], 
            "tour_images"=>$tour['global_data']->tour_images
        ]);

        return $locale_tour[0];
    }

    public static function add_tour() {
        
    }

    public static function edit_tour() {
        
    }

    public static function del_tour() {
        
    }
}