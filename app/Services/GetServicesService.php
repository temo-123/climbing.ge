<?php

namespace App\Services;

// use App\Models\Us_service;
// use App\Models\Ru_service;
use App\Models\Locale_service;
use App\Models\Service;

use App\Models\Service_image;

use Carbon\Carbon;

class GetservicesService
{
    // public static function get_locale_services($global_service)
    // {
    //     $locale = request()->segment(1, '');

    //     $services = array();

    //     if($locale == "ru"){
    //         foreach ($global_service as $service) {
    //             $service_images = service_image::where('service_id', '=', $service->id)->first();
    //             $image = array();
    //             if ($service_images != NULL) {
    //                 $image = $service_images->image;
    //             }

    //             $ru_services = Locale_service::where('id', '=', $service->ru_service_id,)->get();
    //             foreach ($ru_services as $ru_service) {
    //                 if ($ru_service->id == $service->ru_service_id) {
    //                     array_push($services, [$ru_services,    
    //                                                             "id"=>$service->id,
    //                                                             "url_title"=>$service->url_title,  
    //                                                             "user_id"=>$service->user_id,
    //                                                             "image"=>$image
    //                                                             ]);
                                                                
    //                 }
    //             }
    //             $image = array();
    //         }
    //     }
    //     elseif ($locale == "ka") {
    //         foreach ($global_service as $service) {
    //             $service_images = service_image::where('service_id', '=', $service->id)->first();
    //             $image = array();
    //             if ($service_images != NULL) {
    //                 $image = $service_images->image;
    //             }

    //             $ka_services = Locale_service::where('id', '=', $service->ka_service_id,)->get();
    //             foreach ($ka_services as $ka_service) {
    //                 if ($ka_service->id == $service->ka_service_id) {
    //                     array_push($services, [$ka_services,    
    //                                                             "id"=>$service->id,
    //                                                             "url_title"=>$service->url_title,  
    //                                                             "user_id"=>$service->user_id,
    //                                                             "image"=>$image
    //                                                             ]);
                                                                
    //                 }
    //             }
    //             $image = array();
    //         }
    //     } 
    //     else {
    //         foreach ($global_service as $service) {
    //             $service_images = service_image::where('service_id', '=', $service->id)->first();
    //             $image = array();
    //             if ($service_images != NULL) {
    //                 $image = $service_images->image;
    //             }

    //             $us_services = Locale_service::where('id', '=', $service->us_service_id,)->get();
    //             foreach ($us_services as $us_service) {
    //                 if ($us_service->id == $service->us_service_id) {
    //                     array_push($services, [$us_services,    
    //                                                             "id"=>$service->id,
    //                                                             "url_title"=>$service->url_title,  
    //                                                             "user_id"=>$service->user_id,
    //                                                             "image"=>$image
    //                                                             ]);
                                                                
    //                 }
    //             }
    //             $image = array();
    //         }
    //     }

    //     return $services;
    // }


    
    public static function get_locale_services_use_locale($global_service, $locale)
    {
        // $locale = request()->segment(1, '');

        $services = array();

        if($locale == "ru"){
            foreach ($global_service as $service) {
                $service_images = service_image::where('service_id', '=', $service->id)->first();
                $image = array();
                if ($service_images != NULL) {
                    $image = $service_images->image;
                }

                $ru_services = Locale_service::where('id', '=', $service->ru_service_id,)->get();
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

                $ka_services = Locale_service::where('id', '=', $service->ka_service_id,)->get();
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

// dd($service);
                $us_services = Locale_service::where('id', '=', $service->us_service_id,)->get();
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

    // public static function get_locale_service_in_page($service)
    // {
    //     $locale = request()->segment(1, '');

    //     if($locale == "ru"){
    //         $ru_service_id = $service->ru_service_id;
    //         $locale_service = Locale_service::where('id', '=', $ru_service_id)->get();
                
    //     }
    //     elseif ($locale == "ka") {
    //         $ka_service_id = $service->ka_service_id;
    //         $locale_service = Locale_service::where('id', '=', $ka_service_id)->get();
                
    //     } else {
    //         $us_service_id = $service->us_service_id;
    //         $locale_service = Locale_service::where('id', '=', $us_service_id)->get();       
    //     }

    //     return $locale_service;
    // }

    public static function get_locale_service_in_page_use_locale($service, $locale)
    {
        // $locale = request()->segment(1, '');

        $locale_service = [];

        if($locale == "ru"){
            $Local_service = Locale_service::where('id', '=', $service->us_service_id)->get();   
            $global_service = Service::where('url_title', '=', $service->url_title)->get();   
            $service_images = Service::find($service->id)->service_images;   
            array_push($locale_service, ["local_service"=>$Local_service[0], "global_service"=>$global_service[0], "service_images"=>$service_images]);    
                
        }
        elseif ($locale == "ka") {
            $Local_service = Locale_service::where('id', '=', $service->us_service_id)->get();   
            $global_service = Service::where('url_title', '=', $service->url_title)->get();   
            $service_images = Service::find($service->id)->service_images;   
            array_push($locale_service, ["local_service"=>$Local_service[0], "global_service"=>$global_service[0], "service_images"=>$service_images]);    
                
        } else {
            $Local_service = Locale_service::where('id', '=', $service->us_service_id)->get();   
            $global_service = Service::where('url_title', '=', $service->url_title)->get();   
            $service_images = Service::find($service->id)->service_images;   
            array_push($locale_service, ["local_service"=>$Local_service[0], "global_service"=>$global_service[0], "service_images"=>$service_images]);    
        }

        return $locale_service;
    }
}