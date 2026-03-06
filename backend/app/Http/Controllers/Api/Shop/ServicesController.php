<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Service;
use App\Models\Shop\Locale_service;
use App\Models\Shop\Service_image;

use App\Services\ServicesService;
use App\Services\URLTitleService;
use App\Services\GalleryService;
use App\Services\Abstract\ImageControllService;

use Validator;

class ServicesController extends Controller
{
    public function get_all_services()
    {
        return $global_services = Service::get();
    }

    public function get_services_for_index(Request $request)
    {
        $global_services = Service::where('published', '=', 1)->get();
        return $services = ServicesService::get_locale_services_use_locale($global_services, $request->lang);
    }

    public function get_local_services(Request $request)
    {
        $global_services = Service::where('published', '=', 1)->get();
        return $services = ServicesService::get_locale_services_use_locale($global_services, $request->lang);
    }

    public function get_similar_service(Request $request)
    {
        $global_services_count = Service::where('published', '=', 1)->where('id', '!=', $request->id)->count();
        if($global_services_count > 0){
            $global_services = Service::where('published', '=', 1)->where('id', '!=', $request->id)->get();
            return $services = ServicesService::get_locale_services_use_locale($global_services, $request->lang);
        }
        // return abort(404);
    }

    
    public function get_service_images(Request $request)
    {
        $service = Service::where('id', '=', $request->service_id)->first();
        
        if($service->service_images){
            return $service->service_images;
        }
        return [];
    }


    public function get_local_service_in_page(Request $request)
    { 
        $global_service = Service::where('published', '=', 1)->where('url_title',strip_tags($request->url_title))->first();
        return $service = ServicesService::get_locale_service_in_page_use_locale($global_service, $request->lang);
    }

    public function update(Request $request, $id)
    {
        $page_service = Service::latest('id')
                            ->where('published', '=', 1)
                            ->where('id', '=', $id)
                            ->first();

        $global_services = Service::latest('id')
                            ->where('published', '=', 1)
                            ->where('id', '!=', $page_service->id)
                            // ->where('url_title', '!=', $request->url_title)
                            // ->where('category_id', '=', $page_service->category_id)
                            ->get();
                            
        return $services = ServicesService::get_locale_services($global_services);
    }

}
