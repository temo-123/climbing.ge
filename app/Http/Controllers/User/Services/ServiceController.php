<?php

namespace App\Http\Controllers\User\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Service;

class ServiceController extends Controller
{
    public function Service_list(Request $request)
    {
        if (view()->exists('user.data_table')) {
            $data = [
                'table_1'=>'services',
    		    'table_1_name' => 'services',
    		    'table_1_add_url'=>'serviceAddPage',
				'table_1_edit_url'=>'services/edit_service/',
				'table_1_get_route'=>'services/get_service_data',
                'table_1_del'=>"/services/del/",
    		    
    		    'page_name' => 'Services',
    		    'active' => 'Outdoor',
                'page_route' => 'outdoor_page',

                'active' => 'service list',
            ];
            return view('user.data_table',$data);
    	}
    	abort(404);
    }

    public function get_service_data(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        $services = Service::latest('id')->get();

        return $services;	
    }

    public function add_service_page(Request $request)
    {
        if (view() -> exists('user.components.forms.services.service_add_form')) {
            $service = $request->service;
            $data=[
                'service' => $service,
                'title' => 'New '.$service,
                'back_btn' => 'home',
                'add_title' => 'Add '.$service,
                'add_active' => 'Add '.$service,
                
                'add_form' => 'articleAdd',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'article_filtr'=>'1',
                
                'image' => 'article_img',
            ];
            return view('user.components.forms.services.service_add_form', $data);
        }
        abort(404);
    }

    public function edit_service_page(Request $request)
    {
        # code...
    }
}
