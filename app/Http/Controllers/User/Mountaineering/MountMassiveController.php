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
            $input = $request -> except('_token');
            
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
                
                'image' => 'gallery_img',
            ];
            return view('user.components.forms.mount_add_form', $data);
        }
        abort(404);
    }


    public function edit(mount $mount, Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request -> except('_token');

            $mount = Mount::find($request->id);

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
// dd($mount);
            $mount -> update();
        }

        // if (view()->exists('user.components.forms.gallery_form')) {
        //     $image_id = $request->id;
        //     $gallery = Gallery::where('id',strip_tags($request->id))->first();
        //     $old = $gallery -> toArray();
        //     $articles = Article::
        //         where('category','=','outdoor')->
        //         orWhere('category','=','indoor')->
        //         orWhere('category','=','ice')->
        //         orWhere('category','=','other')->
        //         orWhere('category','=','prtner')->
        //         orWhere('category','=','mount')->
        //     get();
        //     $data = [
        //         'title' => 'Edit news - '.$old['title'],
        //         'data' => $old,
        //         "articles" => $articles,
        //         "image_id" => $image_id,

        //         'edit_form'=>'galleryEdit',
        //         'edit_title'=>'test',
        //         'edit_active'=>'test 2',
        //         'published' => 1,
        //         'description' => 1,
        //         'text' => 1,

        //         'image' => $image_dir
        //     ];          
        //     return view('user.components.forms.gallery_form', $data);
        // }
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
                
                'image' => 'gallery_img',

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


    public function gallery_validate($request)
    {
        $request->validate([
            'published' => 'required',
            'category' => 'required',
            'title' => 'required|max:25',
            'text' => 'required|max:225',
            'filter' => 'required',
        ]);
    }
}
