<?php

namespace App\Http\Controllers\User\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ru_service;

class RuServiceController extends Controller
{
    public function add_ru_service(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');
            $this->ru_service_validate($request);

            $ru_articl = ru_service::get();
            foreach ($ru_articl as $ru) {
                $last_ru_service_id = $ru->id;
            }

            $ru_service = ru_service::find($last_ru_service_id);
            // $ru_service = new ru_service();

            $ru_service['title']=$request->ru_title;
            $ru_service['short_description']=$request->ru_short_description;
            $ru_service['text']=$request->ru_text;
            $ru_service['meta_keyword']=$request->ru_meta_keyword;

            $ru_service -> update();
        }
    }

    public function edit_ru_service(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->ru_service_validate($request);
            $ru_service = Ru_service::where('id',strip_tags($request->id))->first();
            
            $ru_service->title=$request->ru_title;
            $ru_service->short_description=$request->ru_short_description;
            $ru_service->text=$request->ru_text;
            $ru_service->meta_keyword=$request->ru_meta_keyword;
            
            $ru_service -> update();
        }
    }


    private function ru_service_validate($request)
    {
        $request->validate([
            'ru_title' => 'required',
            'ru_short_description' => 'required',
            'ru_text' => 'required',
        ]);
    }
}
