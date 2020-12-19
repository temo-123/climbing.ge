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

            $mount['title']=$request->title;
            $mount['text']=$request->text;
            $mount['text_ru']=$request->text_ru;
            $mount['text_ka']=$request->text_ka;
            $mount['description']=$request->description;
            $mount['description_ru']=$request->description_ru;
            $mount['description_ka']=$request->description_ka;
            $mount['published']=$request->published;

            $mount -> save();
        }
        
        if (view() -> exists('user.components.forms.mount_massive_form')) {

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
            return view('user.components.forms.mount_massive_form', $data);
        }
        abort(404);
    }


    public function edit(Gallery $gallery, Request $request)
    {
        $image_dir = '/images/gallery_img';

        if ($request->isMethod('post')) {
            $input = $request -> except('_token');

            $this->gallery_validate($request);

            $gallery = Gallery::where('id',strip_tags($request->id))->first();

            $gallery['image'] = ImageEditService::image_update($image_dir, $gallery, $input, "image", $request);

            $gallery->fill($input);

            if ($gallery->update()) {
                return redirect()->route('gallery_list')->with('status','gallery updated!'); //text
            }
            else{
                return redirect()->route('gallery_list')->with('error','Error! gallery not updated'); //text
            }
        }

        if (view()->exists('user.components.forms.gallery_form')) {
            $image_id = $request->id;
            $gallery = Gallery::where('id',strip_tags($request->id))->first();
            $old = $gallery -> toArray();
            $articles = Article::
                where('category','=','outdoor')->
                orWhere('category','=','indoor')->
                orWhere('category','=','ice')->
                orWhere('category','=','other')->
                orWhere('category','=','prtner')->
                orWhere('category','=','mount')->
            get();
            $data = [
                'title' => 'Edit news - '.$old['title'],
                'data' => $old,
                "articles" => $articles,
                "image_id" => $image_id,

                'edit_form'=>'galleryEdit',
                'edit_title'=>'test',
                'edit_active'=>'test 2',
                'published' => 1,
                'description' => 1,
                'text' => 1,

                'image' => $image_dir
            ];          
            return view('user.components.forms.gallery_form', $data);
        }
    }


	public function delete(gallery $gallery, Request $request)
	{
		$image_dir = '/images/shop_img';

        if ($request->isMethod('delete')) {
            $gallery = Gallery::where('id',strip_tags($request->id))->first();

            // delete article image from folder
            ImageEditService::image_delite($image_dir, $gallery, 'image');

            // delete product from db
            $gallery -> delete();

            return back()->with('status', 'gallery delited!'); //text
        }
        else{
            return back()->with('error', 'Error! gallery not deleted!'); //text
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
