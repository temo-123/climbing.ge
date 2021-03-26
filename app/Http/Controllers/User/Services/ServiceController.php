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
        // $services_count = Service::count();
        // $tags = Service::get();
        // $tags_count = Service::count();

        // $price_array = array();
        // foreach ($services as $service) {
        //     if ($service->discount != null) {
        //         $old_price = $service->price;
        //         $sale = $service->discount;
        //         $price_x_sale = $sale * $old_price;
        //         $var_1 = $price_x_sale/100;
        //         $new_price = $old_price - $var_1;
        //         array_push($price_array, ['service_id'=>$service->id, 'old_price'=>$old_price, 'sale'=>$sale, 'new_price'=>$new_price]);
        //     }
        //     else{
        //         $old_price = $service->price;
        //         array_push($price_array, ['service_id'=>$service->id, 'old_price'=>$old_price, 'sale'=>'0', 'new_price'=>$old_price]);
        //     }
        // }

        return $services;	
    }
}
