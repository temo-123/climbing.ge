<?php

namespace App\Services;

// use App\Models\Us_service;
// use App\Models\Ru_service;
use App\Models\Locale_service;
use App\Models\Service;

use App\Models\Service_image;

use Carbon\Carbon;

use App\Services\Abstract\LocaleContentService;

class ServicesService extends LocaleContentService
{   
    public static function get_locale_services_use_locale($global_service, $locale)
    {
        $respoince = array();

        $services = (new static)->get_locale_content_use_locale($global_service, Locale_service::class, '_service_id', $locale);

        foreach ($services as $service) {
            $service_images = service_image::where('service_id', '=', $service['global_data']['id'])->first();
            $image = array();
            if ($service_images != NULL) {
                $image = $service_images->image;
            }
            array_push($respoince, [
                "global_data"=>$service['global_data'],
                "locale_data"=>$service['locale_data'],
                "image"=>$image
            ]);
        }

        return $respoince;
    }

    public static function get_locale_service_in_page_use_locale($service, $locale)
    {
        $locale_service = [];

        $service = (new static)->get_locale_content_in_page($service, Locale_service::class, '_service_id',$locale);
        $service_images = Service::find($service['global_data']->id)->service_images;

        array_push($locale_service, [
            "locale_data"=>$service['locale_data'], 
            "global_data"=>$service['global_data'], 
            "service_images"=>$service_images
        ]);

        return $locale_service[0];
    }
}