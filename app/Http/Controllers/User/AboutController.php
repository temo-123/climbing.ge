<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Site;

class AboutController extends Controller
{
    public function index()
    {
        $about_us = site::get();
        $data = [
            'title'=>'site info',

            'table_1'=>$about_us,

            'table_1_name'=>'site info',
            
            'page_name' => 'site info',
            'active' => 'galley',
            'page_route' => 'outdoor_page',
        ];
        return view('user.about_us', $data);
    }

    public function site_info_edit_form(Request $request)
    {
        if (view()->exists('user.components.forms.site_info_form')) {
            $data = [
                'title' => 'Edit site info',
                "editing_info_id" => $request->id,
            ];
            return view('user.components.forms.site_info_form', $data);
        }
        abort(404);
    }

    public function site_info_edit(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request->isMethod('post')) {
            $input = $request -> except('_token');

            $site_info = site::where('id','=',$request->id)->first();
            $site_info -> fill($input);
            $site_info -> update();

            // $site = site::where('id','=',$request->id)->first();
            // $site['email'] = $request->email;
            // $site -> update();
        }
    }

    public function get_site_editing_data(Request $request)
    {
        $site_info = site::where('id',strip_tags($request->id))->first();
        return(
            $data = [
                "site_info" => $site_info,
            ]
        );
    }
}
