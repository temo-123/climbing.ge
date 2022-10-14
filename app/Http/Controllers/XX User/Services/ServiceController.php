<?php

namespace App\Http\Controllers\User\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Service;
use App\Models\Ka_service;
use App\Models\Ru_service;
use App\Models\Us_service;

use App\Models\Service_image;

use App\Services\ImageControllService;

class ServiceController extends Controller
{
    public function Service_list(Request $request)
    {
        $this->parmision($request);
        if (view()->exists('user.data_table')) {
            $data = [
                'table_1'=>'services',
    		    'table_1_name' => 'Services',
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
        $this->parmision($request);

        $services = Service::latest('id')->get();

        return $services;	
    }

    public function add_service_page(Request $request)
    {
        $this->parmision($request);
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
        $this->parmision($request);
        if (view()->exists('user.components.forms.services.service_edit_form')) {
            $services_id = $request->id;
            $data = [
                'editing_article_id' => $request->id
            ];
            return view('user.components.forms.services.service_edit_form', $data);
        }
        abort(404);
    }

    public function create_temporary_service(Request $request)
    {
        $this->parmision($request);
        $service_ka = new Ka_service();
        $service_ka['title']="Ka temporary service";
        $service_ka -> save();

        $service_ru = new Ru_service();
        $service_ru['title']="Ru temporary service";
        $service_ru -> save();

        $service_us = new Us_service();
        $service_us['title']="Us temporary service";
        $service_us -> save();


        $services_us = Us_service::where('title','=','Us temporary service')->get();
        $services_ka = Ka_service::where('title','=','Ka temporary service')->get();
        $services_ru = Ru_service::where('title','=','Ru temporary service')->get();

        $last_us_service_id = 0;
        $last_ka_service_id = 0;
        $last_ru_service_id = 0;
        foreach ($services_us as $service_us) {
            $last_us_service_id = $service_us->id;
        }
        foreach ($services_ka as $service_ka) {
            $last_ka_service_id = $service_ka->id;
        }
        foreach ($services_ru as $service_ru) {
            $last_ru_service_id = $service_ru->id;
        }

        $service = new service();
        $service['url_title'] = 'temporary_service';
        $service['published']=0;
        $service['us_service_id']=$last_us_service_id;
        $service['ru_service_id']=$last_ru_service_id;
        $service['ka_service_id']=$last_ka_service_id;
        $service -> save();
    }

    public function del_temporary_service(Request $request)
    {
        $this->parmision($request);
        if ($request->isMethod('post')) {
            $service_id=$request->id;
            $deleting_service_images = service_image::where('service_id','=',$service_id)->get();
            $count_deleting_service_images = service_image::where('service_id','=',$service_id)->count();
            
            if ($count_deleting_service_images > 0) {
                foreach ($deleting_service_images as $service_image) {
                    $deliting_service_image = service_image::where('id',strip_tags($service_image->id))->first();
                    $deliting_service_image ->delete();
                }
            }

            $service = service::where('id','=', $service_id)->first();
            $service_us = US_service::where('id','=',$service->us_service_id)->first();
            $service_ka = Ka_service::where('id','=',$service->ka_service_id)->first();
            $service_ru = Ru_service::where('id','=',$service->ru_service_id)->first();

            $service ->delete();
            $service_us ->delete();
            $service_ka ->delete();
            $service_ru ->delete();
        }
    }

    public function del_service(Request $request)
    {
        $this->parmision($request);
        if ($request->isMethod('post')) {
            $global_id=$request->id;

            $global_service = service::where('id',strip_tags($global_id))->first();
            $us_service = Us_service::where('id',strip_tags($global_service->us_service_id))->first();
            $ru_service = Ru_service::where('id',strip_tags($global_service->ru_service_id))->first();
            $ka_service = Ka_service::where('id',strip_tags($global_service->ka_service_id))->first();

            // delete service images
            $service_images = service_image::where('service_id',strip_tags($global_id))->get();
            $service_images_count = service_image::where('service_id',strip_tags($global_id))->count();
            if ($service_images_count > 0) {
                foreach ($service_images as $service_image) {
                    // dd($service_image);
                    imageControllService::image_delete('images/service_img/', $service_image, $request);
                    $service_image ->delete();
                }
            }

            // delete service from db
            $global_service ->delete();
            $us_service ->delete();
            $ru_service ->delete();
            $ka_service ->delete();
        }
    }
    public function get_temporary_service_editing_data(Request $request)
    {
        $this->parmision($request);
        $temporary_service = service::where("url_title","=","temporary_service")->get();
        foreach ($temporary_service as $service) {
            $last_temporary_service_id = $service->id;
        }

        return(
            $data = [
                "last_temporary_service_id" => $last_temporary_service_id,
            ]
        );
    }
    public function get_service_image(Request $request)
    {
        $this->parmision($request);
        $service_images = service_image::where('service_id',"=", $request->service_id)->get();
        return(
            $data = [
                "service_images" => $service_images,
            ]
        );
    }

    public function get_service_editing_data(Request $request)
    {
        $this->parmision($request);
        $service = Service::where('id',strip_tags($request->editing_service_id))->first();
        
        $us_service = Us_service::where('id',strip_tags($service->us_service_id))->first();
        $ru_service = Ru_service::where('id',strip_tags($service->ru_service_id))->first();
        $ka_service = Ka_service::where('id',strip_tags($service->ka_service_id))->first();

        return(
            $data = [
                "editing_service" => $service,
                "editing_service_us" => $us_service,
                "editing_service_ru" => $ru_service,
                "editing_service_ka" => $ka_service,
            ]
        );
    }

    public function upload_service_image(Request $request)
    {
        $this->parmision($request);
        
        $service_image = new service_image();

        $file_new_name = ImageControllService::image_upload('images/service_img/', $request, 'profile_pic', 1, 'image');

        $service_image['image'] = $file_new_name;
        $service_image['service_id'] = $request->service_id;
        $service_image -> save();
    }

    public function del_service_image(Request $request)
    {
        $this->parmision($request);

        if ($request->isMethod('post')) {
            $service_image = service_image::where('id',strip_tags($request->image_id))->first();

            ImageControllService::image_delete('images/service_img/', $service_image, 'image');

            $service_image -> delete();
        }
    }
    public function parmision($request)
    {
        $request->user()->authorizeRoles(
            [
                'admin', 
                'manager', 
                'ka_manager', 
                'ru_manager', 
                'us_manager', 
                'seller', 
            ]);
    }
}
