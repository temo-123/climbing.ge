<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\ImageControllService;

use App\Models\Article;
use App\Models\Gallery_image;
use App\Models\Gallery_category;
use App\Models\Gallery_image_article;

use Validator;

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

        return Gallery_image::limit(8)->where('image_type', '=', 'Index gallery images')->where('published', '=', 1)->get();
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
    public function create($request)
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::where('id', '=', $id)->where('published', '=', 1)->first();
        
        return $article->gallery_images;
    }

    public function show_article_images($id)
    {
        $article = Article::where('id', '=', $id)->where('published', '=', 1)->first();
        
        return $article->gallery_images;
        // return Gallery_image::limit(8)->where('article_id', '=', $id)->where('published', '=', 1)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function gallery_image_edit(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $validation_issets;

            $data = json_decode($request->data, true );
            
            $image_validate = $this->gallery_image_validate($data, $request);
            if ($image_validate != null) {
                $validation_issets['form_data_validation'] = $image_validate;
            }
            else{
                $validation_issets['form_data_validation'] = false;
            }

            if (!$validation_issets['form_data_validation'] ) {
                $edit_gallery_image = Gallery_image::where('id', '=', $request->image_id)->first();
            
                $edit_gallery_image['title']=$data["title"];
                $edit_gallery_image['text']=$data["text"];
                $edit_gallery_image['published']=$data["published"];
                $edit_gallery_image['image_type']=$data["image_type"];
                $edit_gallery_image['category_id']=$data["category_id"];
                $edit_gallery_image['link']=$data["link"];

                if($request->hasFile('image')){
                    $edit_gallery_image['image'] =  ImageControllService::image_update('images/gallery_img/', $edit_gallery_image, $request, 'image', 'image', 1);
                }
            
                $edit_gallery_image->save();

                if($data["article_id"] && $data["image_type"] == 'Article image'){
                    $galery_image_article_count = Gallery_image_article::where('image_id', '=', $edit_gallery_image->id)->count();
                    if ($galery_image_article_count > 0) {
                        $edit_active_conaction = Gallery_image_article::where('image_id', '==', $edit_gallery_image->id)->first();
                        $edit_active_conaction['article_id'] = $data["article_id"];
                        $edit_active_conaction->save();
                    }
                    else {
                        $new_image_article = new Gallery_image_article;
                        $new_image_article['image_id']=$edit_gallery_image["id"];
                        $new_image_article['article_id']=$data["article_id"];
                        $new_image_article->save();
                    }
                }
                else if($data["image_type"] != 'Article image') {
                    if (Gallery_image_article::where('image_id', '=', $edit_gallery_image->id)->count() > 0) {
                        $deliting_conection_item = Gallery_image_article::where('image_id', '=', $edit_gallery_image->id)->first();
                        $deliting_conection_item -> delete();
                    }
                }
            }
            else{
                return response()->json([
                    $image_validate,
                ], 422);
            }
        }
    }

    public function get_editing_image(Request $request)
    {
        $image = Gallery_image::where('id', '=', $request->image_id)->first();
        $image_article = $image->article;
        return $image;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery = Gallery_image::where('id',strip_tags($id))->first();

        // delete article file
        ImageControllService::image_delete('images/gallery_img/', $gallery, 'image');

        // delete product from db
        $gallery -> delete();
    }

    public function gallery_image_add(Request $request)
    {
        $validation_issets;

        $data = json_decode($request->data, true );
        
        $image_validate = $this->gallery_image_validate($data, $request);
        if ($image_validate != null) {
            $validation_issets['form_data_validation'] = $image_validate;
        }
        else{
            $validation_issets['form_data_validation'] = false;
        }

        if (!$validation_issets['form_data_validation'] ) {
            $new_gallery_image = new Gallery_image;
        
            $new_gallery_image['title']=$data["title"];
            $new_gallery_image['text']=$data["text"];
            $new_gallery_image['published']=$data["published"];
            $new_gallery_image['image_type']=$data["image_type"];
            $new_gallery_image['category_id']=$data["category_id"];
            $new_gallery_image['link']=$data["link"];

            if($request->hasFile('image')){
                $new_gallery_image['image'] =  ImageControllService::image_upload('images/gallery_img/', $request, 'image', 1);
            }
        
            $new_gallery_image->save();

            if($data["article_id"] && $data["image_type"] == 'Article image'){
                $new_image_article = new Gallery_image_article;
                $new_image_article['image_id']=$new_gallery_image["id"];
                $new_image_article['article_id']=$data["article_id"];
                $new_image_article->save();
            }
        }
        else{
            return response()->json([
                $image_validate,
            ], 422);
        }
    }

    public function gallery_image_validate($data, $request)
    {
        $validator = Validator::make($data, [
            'published' => 'required',
            'category_id' => 'required',
            'title' => 'required|max:25',
            'text' => 'required|max:225',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
