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
    public function index()
    {
        return $global_services = Service::get();
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
        return abort(404);
    }

    public function add_service(Request $request)
    {
        $data = json_decode($request->data, true );

        $service_adding = ServicesService::add_content($data, Service::class, Locale_service::class, '_service', $request);

        if (!array_key_exists('validation', $service_adding->original)) {
            if($request->hasFile('service_images')){
                GalleryService::add_gallery_images(
                    $request['service_images'], 
                    $service_adding->original['global_service_id'],
                    Service_image::class, 
                    'image', 
                    'service_id', 
                    '/images/service_img/'
                );
            }
        }
        else {
            return $service_adding;
        }
    }

    public function get_editing_service(Request $request)
    {
        $service = Service::where('id', '=', $request->service_id)->first();
        
        $data = [
            'global_service' => $service,
            'us_service' => $service->us_service,
            'ka_service' => $service->ka_service,
            // 'ru_service' => $service->ru_service,

            'service_images' => $service->service_images,
        ];

        return $data;
    }
    
    public function get_service_images(Request $request)
    {
        $service = Service::where('id', '=', $request->service_id)->first();
        
        if($service->service_images){
            return $service->service_images;
        }
        return [];
    }

    public function edit_service(Request $request)
    {
        // $data = json_decode($request->data, true );
        // dd($data['global_service']['id']);

        $article_editing = ServicesService::edit_content(Service::class, Locale_service::class, '_service', $request, 'images/service_img/');

        if(!array_key_exists('validation', $article_editing->original)){
            if($request->hasFile('service_new_images')){
                GalleryService::add_gallery_images(
                    $request['service_new_images'], 
                    $article_editing->original['global_service_id'], 
                    Service_image::class, 
                    'image', 
                    'service_id', 
                    '/images/service_img/'
                );
            }
        }
        else{
            return $article_editing;
        } 
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

    public function del_service(Request $request)
    {
        $service = Service::where('id', '=', $request->service_id)->first();
        $service_images_count = Service_image::where('service_id', '=', $service->id)->count();

        if($service_images_count > 0){
            $service_images = Service_image::where('service_id', '=', $service->id)->get();
            // dd($service_images);
            foreach ($service_images as $image) {
                ImageControllService::image_delete('images/service_img/', $image, 'image');
                $image ->delete();
            }
        }
        $service ->delete();
    }

    public function del_service_image(Request $request)
    {
        $image = Service_image::where('id', '=', $request->image_id)->first();
        if($image){
            ImageControllService::image_delete('images/service_img/', $image, 'image');
            $image ->delete();
        }
    }

}
