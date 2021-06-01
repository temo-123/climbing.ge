<?php

namespace App\Http\Controllers\User\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Service;
use App\Models\Us_service;
use App\Models\Ka_service;
use App\Models\Ru_service;
use Auth;

use App\Services\URLTitleService;

class GlobalServiceController extends Controller
{
    // private $image_dir = "/images/shop_img";
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function add_global_service(Request $request)
    {
        $this->parmision($request);

        if ($request -> isMethod('post')) {
            $this->global_service_validate($request);

            $last_globale_id = 0;

            // $global_service = service::where('url_title', '=', 'temporary_service')->get();
            $global_service = service::get();
            foreach ($global_service as $global) {
                $last_globale_id = $global->id;
            }

            $url_title = URLTitleService::get_url_title($request->us_title_for_url_title);

            $service = Service::find($last_globale_id);

            $service['user_id'] = Auth::user()->id;
            $service['url_title'] = $url_title;

            // $service['discount']=$request->discount;
            $service['published'] = $request->published;
            
            $service -> update();
        }
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function edit_global_service(service $service, Request $request)
    {
        $this->parmision($request);
        if ($request->isMethod('post')) {
            $this->global_service_validate($request);

            $service = Service::where('id',strip_tags($request->id))->first();
            
            $service['published'] = $request->published;
            
            $service -> update();
        }
    }

    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function delete(Service $service, Request $request)
    {
        $this->parmision($request);
        if ($request->isMethod('delete')) {

            $image_dir = '/images/shop_img';
            imageControllService::image_delete($image_dir, $sector_image, $request);

            // delete service from db
            $service -> delete();

            return back()->with('good_status', 'service delited!'); //text
        }
        else{
            return back()->with('bed_status', 'Error! service not deleted!'); //text
        }
    }

    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function favorite()
    {
        $this->parmision($request);
        if (view()->exists('user.favorite_services')) {

            $favorite_services = Favorite_service::where('user_id', '=', Auth::user()->id)->get();
    		$services = Service::get();
            
            $my_favorite_services = array();

            foreach ($favorite_services as $favorite_service) {
                foreach ($services as $service) {
                    // dd($favorite_service->id);
                    if ($service->id == $favorite_service->service_id) {
                        // print_r($service->id);
                        array_push($my_favorite_services, [service::where('id', '=', $service->id)->get()]);
                    }
                }
            }

    		$data = [
    			'title'=>'My favorite services',
                'page_name'=>'favorite_services',
    			'my_favorite_services' => $my_favorite_services
    		];
    		return view('user.favorite_services',$data);
    	}
    	abort(404);
    }


    private function global_service_validate($request)
    {
        $request->validate([
            'published' => 'required',
        ]);
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
