<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gallery_image;
use App\Services\ImageControllService;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $gallery = array();
        // $full_image = '';
        // $image = '';

        // $gallery_images = Gallery_image::limit(8)->get();
        // $image_url = config('app.url').'/images/gallery_img/';

        // foreach ($gallery_images as $gallery_img) {
        //     $image = $gallery_img->image;
        //     $image = strval($image);
        //     $full_image = $image_url . $image;
        //     array_push($gallery, $full_image);
        // }
        // return response()->json($gallery);

        return Gallery_image::limit(8)->where('image_type', '=', 1)->where('published', '=', 1)->get();
    }

    public function get_swiper_images()
    {
        $gallery = array();
        $full_image = '';
        $image = '';

        $gallery_images = Gallery_image::where('image_type', '=', 1)->where('published', '=', 1)->get();
        $image_url = config('app.url').'/images/gallery_img/';

        foreach ($gallery_images as $gallery_img) {
            $image = $gallery_img->image;
            $image = strval($image);
            $full_image = $image_url . $image;
            array_push($gallery, ['id'=> $gallery_img->id, 'image'=> $full_image]);
        }
        return response()->json($gallery);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request->hasFile('image')) {

            // $this->gallery_validate($request);
            
            $gallery = new Gallery_image();

            $gallery['category']=$request->category;
            $gallery['title']=$request->title;
            $gallery['text']=$request->text;
            $gallery['link']=$request->link;
            $gallery['filter']=$request->filter;
            $gallery['published']=$request->published;
            $gallery['article_id']=$request->article_id;
    
            $file_new_name = ImageControllService::image_upload('images/gallery_img/', $request, 'image', 1);
    
            $gallery['image'] = $file_new_name;

            $gallery -> save();

            // return redirect()->route('gallery_list');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Gallery_image::get();

        // $articles = Gallery::get();
        // $title = 'Gallery';
        // $page_name = $title;
        // $table_1_name = $title;
        // $articles_add_url = 'article_add';
        // $article_add_category = 'event';
        // $articles_edit_url = 'article_edit';
        // $article_page_utl = 'index';   

        // $data = [
        //     'title'=>$title,
        //     'table_1'=>$articles,

        //     'table_1_add_url'=>$articles_add_url,
        //     'table_1_add_category'=>$article_add_category,
        //     'table_1_edit_url'=>$articles_edit_url,
        //     'table_1_article_url'=>$article_page_utl,
        //     'table_1_title'=>'gallery',
        //     'table_1_pablic' => '',
        //     'table_1_name'=> $table_1_name,
            
        //     'page_name' => $page_name,
        //     'active' => 'galley',
        //     'page_route' => 'outdoor_page',
        // ];
        // return view('user.gallery_list', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd($id);

    //     $gallery = array();
    //     $full_image = '';
    //     $image = '';

    //     $gallery_images = Gallery_image::where('article_id',strip_tags($id))->limit(8)->get();
    //     $image_url = config('app.url').'/images/gallery_img/';

    //     foreach ($gallery_images as $gallery_img) {
    //         $image = $gallery_img->image;
    //         $image = strval($image);
    //         $full_image = $image_url . $image;
    //         array_push($gallery, $full_image);
    //     }

    //     return $gallery;


        return Gallery_image::limit(8)->where('article_id', '=', $id)->where('published', '=', 1)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        echo $request->text;
        if ($request->isMethod('post')) {
            // $input = $request -> except('_token');
            
            // $this->gallery_validate_for_editing($request);

            $gallery = Gallery_image::where('id',strip_tags($request->id))->first();

            $gallery['category']=$request->category;
            $gallery['title']=$request->title;
            $gallery['text']=$request->text;
            $gallery['link']=$request->link;
            $gallery['filter']=$request->filter;
            $gallery['published']=$request->published;
            $gallery['article_id']=$request->article_id;
            
            $file_new_name = '';
            if ($request->hasFile('image')){ 
                $file_new_name = ImageControllService::image_update('images/gallery_img/', $gallery, $request, 'image', 1, 'image');
            }
            else{
                $file_new_name = $gallery['image'];
            }
            $gallery['image']=$file_new_name;

            $gallery->update();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function get_image(Request $request)
    {
        dd('test');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery = Gallery::where('id',strip_tags($id))->first();

        // delete article file
        ImageControllService::image_delete('images/gallery_img/', $gallery, 'image');

        // delete product from db
        $gallery -> delete();
    }

    public function gallery_validate($request)
    {
        $request->validate([
            'published' => 'required',
            'category' => 'required',
            'title' => 'required|max:25',
            'text' => 'required|max:225',
            'image' => 'required',
        ]);
    }
    public function gallery_validate_for_editing($request)
    {
        $request->validate([
            'published' => 'required',
            'category' => 'required',
            'title' => 'required|max:25',
            'text' => 'required|max:225',
        ]);
    }
}
