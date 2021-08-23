<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Models\Service;
use App\Models\Service_image;
use App\Services\GetServicesService;

class ServiceController extends Controller
{
    public function services_list(Request $request)
    {
        if (view()->exists('shop.services_list')) {

            $global_services = Service::where('published', '=', 1)->get();
            $services = GetServicesService::get_locale_services($global_services);
    		$data = [
    			'title'=>'Services',
    			'services'=>$services,

    			'shop'=>1,
                'num' => 1,
                
                'articles_link' => 'other_page',
                'image_dir' => 'shop_img',
    		];
    		return view('shop.services_list',$data);
    	}
    	abort(404);
    }

    public function service_page(Request $request, $name)
    {
        if (view()->exists('shop.service_page')) {
            $global_service = Service::where('url_title',strip_tags($name))->first();
            $service = GetServicesService::get_locale_service_in_page($global_service);
            $service_image = Service_image::first()->where('service_id',strip_tags($global_service->id))->first();

            $global_services = Service::where('published', '=', 1)->get();
            $other_services = GetServicesService::get_locale_services($global_services);

    		$data = [
    			'title'=>$service[0]->title,
                'service'=>$service[0],
                'services' => 'action',
                'other_services'=>$other_services,
                'service_image'=>$service_image->image,
                
                'articles_link' => 'other_page',
                'image_dir' => 'shop_img',
    		];
    		return view('shop.service_page',$data);
    	}
    	abort(404);
    }
}
