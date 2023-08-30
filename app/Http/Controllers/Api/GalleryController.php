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
    public function index()
    {
        $index_gallery_count = Gallery_image::where('image_type', '=', 'Index gallery image')->where('published', '=', 1)->count();
        $gallery_images = [];

        if($index_gallery_count < 13){
            $gallery_images = Gallery_image::limit(12)->where('image_type', '=', 'Index gallery image')->where('published', '=', 1)->get();
        }
        else if($index_gallery_count > 13){
            $gallery_images = Gallery_image::limit(12)->where('image_type', '=', 'Index gallery image')->where('published', '=', 1)->inRandomOrder()->get();
        }

        return $gallery_images;
    }

    public function get_swiper_images()
    {
        return Gallery_image::limit(5)->latest('id')->where('image_type', '=', 'Index head slider image')->where('published', '=', 1)->get();
    }

    public function store(Request $request)
    {
        return Gallery_image::latest('id')->get();
    }

    public function show($id)
    {
        $article = Article::where('id', '=', $id)->where('published', '=', 1)->first();
        // return $article;
        
        return $article->gallery_images;
    }

    public function show_article_images($id)
    {
        $article = Article::where('id', '=', $id)->where('published', '=', 1)->first();
        
        return $article->gallery_images;
        // return Gallery_image::limit(8)->where('article_id', '=', $id)->where('published', '=', 1)->get();
    }

    public function gallery_image_edit(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $validation_issets;

            $data = json_decode($request->data, true );
            
            $image_validate = $this->gallery_image_edit_validate($data, $request);
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

                if (isset($data["article_id"])) {
                    if($data["article_id"] && $data["image_type"] == 'Article image'){
                        $galery_image_article = Gallery_image_article::where('image_id', '=', $edit_gallery_image->id)->first();
                        if (isset($galery_image_article) && $galery_image_article->article_id != $data["article_id"]) {
                            $edit_active_conaction = Gallery_image_article::where('image_id', '=', $edit_gallery_image->id)->first();
                            $edit_active_conaction['article_id'] = $data["article_id"];
                            $edit_active_conaction->save();
                        }
                        else if(!isset($galery_image_article)){
                            $new_relatione = new Gallery_image_article;
                            $new_relatione['article_id'] = $data["article_id"];
                            $new_relatione['image_id'] = $edit_gallery_image->id;
                            $new_relatione->save();
                        }
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

    public function destroy($id)
    {
        $gallery = Gallery_image::where('id',strip_tags($id))->first();
        
        if(Gallery_image_article::where("image_id", '=', $gallery->id)->first()){
            $gallery_article_relatione = Gallery_image_article::where("image_id", '=', $gallery->id)->first();
            $gallery_article_relatione -> delete();
        }

        // delete article file
        ImageControllService::image_delete('images/gallery_img/', $gallery, 'image');

        // delete product from db
        $gallery -> delete();
    }

    public function gallery_image_add(Request $request)
    {
        $validation_issets;

        $data = json_decode($request->data, true );
        
        $image_validate = $this->gallery_image_add_validate($data, $request);
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

            if (isset($data["article_id"])) {
                if($data["article_id"] && $data["image_type"] == 'Article image'){
                    $new_image_article = new Gallery_image_article;
                    $new_image_article['image_id']=$new_gallery_image["id"];
                    $new_image_article['article_id']=$data["article_id"];
                    $new_image_article->save();
                }
            }
        }
        else{
            return response()->json([
                $image_validate,
            ], 422);
        }
    }

    public function gallery_image_edit_validate($data, $request)
    {
        $validator = Validator::make($data, [
            'published' => 'required',
            'category_id' => 'required',
            'title' => 'required|max:35',
            'text' => 'required|max:225',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }

        $request->validate(
            [
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ],
            [
                'image' => 'Your image is uncorect',
                'image.max' => 'Your image is wery big. (Max size = 2048Kb)',
                'image.mimes' => 'Your image need by in jpg, png, jpeg, gif or svg format',
                'image.image' => 'Your file is not a image'
            ]
        );
    }
    public function gallery_image_add_validate($data, $request)
    {
        $validator = Validator::make($data, [
            'published' => 'required',
            'category_id' => 'required',
            'title' => 'required|max:35',
            'text' => 'required|max:225',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }

        $request->validate(
            [
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ],
            [
                'image' => 'Your image is uncorect',
                'image.max' => 'Your image is wery big. (Max size = 2048Kb)',
                'image.mimes' => 'Your image need by in jpg, png, jpeg, gif or svg format',
                'image.image' => 'Your file is not a image'
            ]
        );
    }
}
