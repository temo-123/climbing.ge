<?php

namespace App\Http\Controllers\User\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ka_service;

class KaServiceController extends Controller
{
    public function add_ka_service(Request $request)
    {

        $this->parmision($request);
        $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');
            $this->ka_service_validate($request);

            $ka_articl = Ka_service::get();
            foreach ($ka_articl as $ka) {
                $last_ka_service_id = $ka->id;
            }

            $ka_service = Ka_service::find($last_ka_service_id);
            // $ka_service = new Ka_service();

            $ka_service['title']=$request->ka_title;
            $ka_service['short_description']=$request->ka_short_description;
            $ka_service['text']=$request->ka_text;
            $ka_service['meta_keyword']=$request->ka_meta_keyword;

            $ka_service -> update();
        }
    }

    public function edit_ka_service(Request $request)
    {

        $this->parmision($request);
        if ($request->isMethod('post')) {
            $this->ka_service_validate($request);

            $ka_service = Ka_service::where('id',strip_tags($request->id))->first();
            
            $ka_service->title=$request->ka_title;
            $ka_service->short_description=$request->ka_short_description;
            $ka_service->text=$request->ka_text;
            $ka_service->meta_keyword=$request->ka_meta_keyword;
            
            $ka_service -> update();
        }
    }


    private function ka_service_validate($request)
    {
        $request->validate([
            'ka_title' => 'required',
            'ka_short_description' => 'required',
            'ka_text' => 'required',
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
