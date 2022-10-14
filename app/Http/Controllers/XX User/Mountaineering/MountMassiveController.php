<?php

namespace App\Http\Controllers\User\Mountaineering;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Mount;

class MountMassiveController extends Controller
{
    public function add(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');
            $this->mount_validate($request);
            
            $mount = new Mount();

            $mount['name']=$request->name;
            $mount['name_ru']=$request->name;
            $mount['name_ka']=$request->name;

            $mount['text']=$request->text;
            $mount['text_ru']=$request->text_ru;
            $mount['text_ka']=$request->text_ka;

            $mount['short_description']=$request->short_description;
            $mount['short_description_ru']=$request->short_description_ru;
            $mount['short_description_ka']=$request->short_description_ka;

            $mount['how_get']=$request->how_get;
            $mount['how_get_ru']=$request->how_get_ru;
            $mount['how_get_ka']=$request->how_get_ka;

            $mount['best_time']=$request->best_time;
            $mount['best_time_ru']=$request->best_time_ru;
            $mount['best_time_ka']=$request->best_time_ka;

            $mount['map']=$request->map;
            $mount['weather']=$request->weather;
            $mount['published']=$request->published;

            $mount -> save();
        }
    }

    public function add_form()
    {
        if (view() -> exists('user.components.forms.mount_add_form')) {
            $data=[
                'title' => 'New mount',
                'back_btn' => 'home',
                'add_title' => 'Add mount',
                'add_active' => 'Add mount',
                
                'add_form' => 'mountRouteAdd',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'gallery_filtr'=>'1',
                
                'image' => 'mount_img',
            ];
            return view('user.components.forms.mount_add_form', $data);
        }
        abort(404);
    }


    public function edit(mount $mount, Request $request)
    {
        if ($request->isMethod('post')) {
            // $input = $request -> except('_token');
            $this->mount_validate($request);
            // $mount = Mount::find($request->id);
            $mount = Mount::where('id',strip_tags($request->id))->first();
            
            $mount['name']=$request->name;
            $mount['name_ru']=$request->name;
            $mount['name_ka']=$request->name;

            $mount['text']=$request->text;
            $mount['text_ru']=$request->text_ru;
            $mount['text_ka']=$request->text_ka;

            $mount['short_description']=$request->short_description;
            $mount['short_description_ru']=$request->short_description_ru;
            $mount['short_description_ka']=$request->short_description_ka;

            $mount['how_get']=$request->how_get;
            $mount['how_get_ru']=$request->how_get_ru;
            $mount['how_get_ka']=$request->how_get_ka;

            $mount['best_time']=$request->best_time;
            $mount['best_time_ru']=$request->best_time_ru;
            $mount['best_time_ka']=$request->best_time_ka;

            $mount['map']=$request->map;
            $mount['weather']=$request->weather;
            $mount['published']=$request->published;
            
            $mount -> update();
        }
    }

    public function get_mount_editing_data(Request $request)
    {
        $mount = mount::where('id',strip_tags($request->id))->first();
        return(
            $data = [
                "mount" => $mount,
            ]
        );
    }

    public function edit_form(Request $request)
    {
        if (view() -> exists('user.components.forms.mount_edit_form')) {
            // dd($request->id);
            $data=[
                'title' => 'New mount',
                'back_btn' => 'home',
                'add_title' => 'Add mount',
                'add_active' => 'Add mount',
                
                'add_form' => 'mountRouteAdd',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'gallery_filtr'=>'1',
                
                'image' => 'mount_img',

                "editing_mount_id" => $request->id,
            ];
            return view('user.components.forms.mount_edit_form', $data);
        }
        abort(404);
    }

	public function delete(mount $mount, Request $request)
	{
        if ($request->isMethod('post')) {
            $mount = Mount::where('id',strip_tags($request->id))->first();
            $mount -> delete();
        }
    }


    public function mount_validate($request)
    {
        $request->validate([
            'published' => 'required',
            'map' => 'required',
            'weather' => 'required',
            
            'name' => 'required',
            'name_ru' => 'required',
            'name_ka' => 'required',

            'text' => 'required',
            'text_ru' => 'required',
            'text_ka' => 'required',

            'short_description' => 'required',
            'short_description_ru' => 'required',
            'short_description_ka' => 'required',

            'how_get' => 'required',
            'how_get_ru' => 'required',
            'how_get_ka' => 'required',

            'best_time' => 'required',
            'best_time_ru' => 'required',
            'best_time_ka' => 'required',
        ]);
    }
}
