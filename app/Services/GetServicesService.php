<?php

namespace App\Services;

use App\Models\Us_service;
use App\Models\Ru_service;
use App\Models\Ka_service;

use App\Models\Service_image;

use Carbon\Carbon;

class GetservicesService
{
    public static function get_locale_services($global_service)
    {
        $locale = request()->segment(1, '');

        $services = array();

        if($locale == "ru"){
            foreach ($global_service as $service) {
                $service_images = service_image::where('service_id', '=', $service->id)->first();
                $image = array();
                if ($service_images != NULL) {
                    $image = $service_images->image;
                }

                $ru_services = Ru_service::where('id', '=', $service->ru_service_id,)->get();
                foreach ($ru_services as $ru_service) {
                    if ($ru_service->id == $service->ru_service_id) {
                        array_push($services, [$ru_services,    
                                                                "id"=>$service->id,
                                                                "url_title"=>$service->url_title,  
                                                                "user_id"=>$service->user_id,
                                                                "image"=>$image
                                                                ]);
                                                                
                    }
                }
                $image = array();
            }
        }
        elseif ($locale == "ka") {
            foreach ($global_service as $service) {
                $service_images = service_image::where('service_id', '=', $service->id)->first();
                $image = array();
                if ($service_images != NULL) {
                    $image = $service_images->image;
                }

                $ka_services = Ka_service::where('id', '=', $service->ka_service_id,)->get();
                foreach ($ka_services as $ka_service) {
                    if ($ka_service->id == $service->ka_service_id) {
                        array_push($services, [$ka_services,    
                                                                "id"=>$service->id,
                                                                "url_title"=>$service->url_title,  
                                                                "user_id"=>$service->user_id,
                                                                "image"=>$image
                                                                ]);
                                                                
                    }
                }
                $image = array();
            }
        } 
        else {
            foreach ($global_service as $service) {
                $service_images = service_image::where('service_id', '=', $service->id)->first();
                $image = array();
                if ($service_images != NULL) {
                    $image = $service_images->image;
                }

                $us_services = Us_service::where('id', '=', $service->us_service_id,)->get();
                foreach ($us_services as $us_service) {
                    if ($us_service->id == $service->us_service_id) {
                        array_push($services, [$us_services,    
                                                                "id"=>$service->id,
                                                                "url_title"=>$service->url_title,  
                                                                "user_id"=>$service->user_id,
                                                                "image"=>$image
                                                                ]);
                                                                
                    }
                }
                $image = array();
            }
        }

        return $services;
    }


    public static function get_locale_service_in_page($service)
    {
        $locale = request()->segment(1, '');

        if($locale == "ru"){
            $ru_service_id = $service->ru_service_id;
            $locale_service = Ru_service::where('id', '=', $ru_service_id)->get();
                
        }
        elseif ($locale == "ka") {
            $ka_service_id = $service->ka_service_id;
            $locale_service = Ka_service::where('id', '=', $ka_service_id)->get();
                
        } else {
            $us_service_id = $service->us_service_id;
            $locale_service = Us_service::where('id', '=', $us_service_id)->get();       
        }

        return $locale_service;
    }
}