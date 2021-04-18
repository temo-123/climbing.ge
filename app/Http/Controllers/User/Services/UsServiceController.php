<?php

namespace App\Http\Controllers\User\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Us_service;

class UsServiceController extends Controller
{
    public function add_us_service(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');
            $this->us_service_validate($request);

            $us_articl = us_service::get();
            foreach ($us_articl as $us) {
                $last_us_service_id = $us->id;
            }

            $us_service = us_service::find($last_us_service_id);
            // $us_service = new us_service();

            $us_service['title']=$request->us_title;
            $us_service['short_description']=$request->us_short_description;
            $us_service['text']=$request->us_text;
            $us_service['meta_keyword']=$request->us_meta_keyword;

            $us_service -> update();
        }
    }

    public function edit_us_service(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->us_service_validate($request);
            
            $us_service = Us_service::where('id',strip_tags($request->id))->first();
            
            $us_service->title=$request->us_title;
            $us_service->short_description=$request->us_short_description;
            $us_service->text=$request->us_text;
            $us_service->meta_keyword=$request->us_meta_keyword;
            
            $us_service -> update();
        }
    }


    private function us_service_validate($request)
    {
        $request->validate([
            'us_title' => 'required',
            'us_short_description' => 'required',
            'us_text' => 'required',
        ]);
    }
}
