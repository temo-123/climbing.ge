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


    public function edit(Site $site, Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        $site_info = Site::where('id','=','1')->first();

        if ($request->isMethod('post')) {
            $input = $request -> except('_token');
    
            // if ($request->hasFile('image')) {
            //     $file = $request->file('image');
            //     $file -> move(public_path().'/assets/img/article_img/',$file->getClientOriginalName());
            //     $input['image'] = $file->getClientOriginalName();
            // }
            // else {
            //     $input['image'] = $input['old_image'];
            // }

            // unset($input ['old_image']);

            $site -> fill($input);

            if ($site->update()) {
                return redirect('user/article')->with('status','article updated'); //text
            }
        }

        $old = $site_info -> toArray();
        // dd($old);

        if (view()->exists('user.components.forms.site_info_form')) {
            $data = [
                'title' => 'Edit site info',
                'data' => $old,
                'back_btn' => 'home',
                'edit_title' => 'Edit article',
                'edit_active' => 'Edit article',
                
                'edit_form' => 'home',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'article_filtr'=>'1',
                
                'image' => 'article_img',
            ];
            return view('user.components.forms.site_info_form', $data);
        }
        abort(404);
    }

    private function validate_data()
    {
        // $validator = validator::make($input, [
            //     'title' => 'required|max:190',
            //     'text' => 'max:500'
            // ]);
            // if ($validator->fails()) {
            //     return back() -> withErrors($validator) -> withInput();
            // }
    }
}
