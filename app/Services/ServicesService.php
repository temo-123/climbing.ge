<?php

namespace App\Services;

// use App\Models\Us_service;
// use App\Models\Ru_service;
use App\Models\Shop\Locale_service;
use App\Models\Shop\Service;

use App\Models\Shop\Service_image;

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

    /**
     * Data a service page shows from another system, by services.link_type.
     * docs/FUNCTIONS_DOCS/shop/gear_rental.md#shop-service-page
     */
    public static function linked_info($service, $locale)
    {
        $lang = in_array($locale, ['ka'], true) ? 'ka' : 'us';
        switch ($service->link_type ?? null) {
            case 'gear_rental':
                return GearRentalService::public_price_list($service->link_id ? (int) $service->link_id : null, $lang);
            case 'wall_calculator':
                // the calculator loads its own prices (coefficients) in the browser
                return ['type' => 'wall_calculator'];
            default:
                return null;
        }
    }

    public static function get_locale_service_in_page_use_locale($service, $locale)
    {
        $locale_service = [];

        $service = (new static)->get_locale_content_in_page($service, Locale_service::class, '_service_id',$locale);
        $service_images = Service::find($service['global_data']->id)->service_images;

        array_push($locale_service, [
            "locale_data"=>$service['locale_data'], 
            "global_data"=>$service['global_data'], 
            "service_images"=>$service_images,
            // e.g. gear rental prices (services.link_type) — null when nothing is linked
            "linked"=>self::linked_info($service['global_data'], $locale),
        ]);

        return $locale_service[0];
    }
}