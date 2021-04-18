<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Models\Service;
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

    public function service_page(Request $request)
    {
        if (view()->exists('shop.service_page')) {
    		$data = [
    			'title'=>'Services',
    			'services'=>'$services',

    			'shop'=>1,
                'num' => 1,
                
                'articles_link' => 'other_page',
                'image_dir' => 'shop_img',
    		];
    		return view('shop.service_page',$data);
    	}
    	abort(404);
    }
}
