<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gallery;
use App\Models\Article;
use App\Services\ImageEditService;

class GalleryController extends Controller
{
    public function index()
    {
        $articles = Gallery::get();
        $title = 'Gallery';
        $page_name = $title;
        $table_1_name = $title;
        $articles_add_url = 'article_add';
        $article_add_category = 'event';
        $articles_edit_url = 'article_edit';
        $article_page_utl = 'index';   

        $data = [
            'title'=>$title,
            'table_1'=>$articles,

            'table_1_add_url'=>$articles_add_url,
            'table_1_add_category'=>$article_add_category,
            'table_1_edit_url'=>$articles_edit_url,
            'table_1_article_url'=>$article_page_utl,
            'table_1_title'=>'gallery',
            'table_1_pablic' => '',
            'table_1_name'=> $table_1_name,
            
            'page_name' => $page_name,
            'active' => 'galley',
            'page_route' => 'outdoor_page',
        ];
        return view('user.gallery_list', $data);
    }


    public function add(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

            $this->gallery_validate($request);
            $request->validate([
                'image' => 'required',
            ]);
            
            $gallery = new gallery();

            $gallery['category']=$request->category;
            $gallery['title']=$request->title;
            $gallery['text']=$request->text;
            $gallery['link']=$request->link;
            $gallery['filter']=$request->filtr;
            $gallery['index_gallery_image']=$request->index_gallery_image;
            $gallery['published']=$request->published;
            $gallery['article_id']=$request->article_id;

            $image_dir = "/images/gallery_img";
            $gallery['image']=ImageEditService::image_upload($image_dir, "image", $request);

            $gallery -> save();
        }
        
        if (view() -> exists('user.components.forms.gallery_form')) {
            $articles = Article::
                where('category','=','outdoor')->
                orWhere('category','=','indoor')->
                orWhere('category','=','ice')->
                orWhere('category','=','other')->
                orWhere('category','=','prtner')->
                orWhere('category','=','mount')->
            get();

            $category = $request->category;
            $data=[
                'category' => $category,
                'title' => 'New '.$category,
                "articles" => $articles,
                'back_btn' => 'home',
                'add_title' => 'Add '.$category,
                'add_active' => 'Add '.$category,
                
                'add_form' => 'galleryAdd',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'gallery_filtr'=>'1',
                
                'image' => 'gallery_img',
            ];
            return view('user.components.forms.gallery_form', $data);
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
