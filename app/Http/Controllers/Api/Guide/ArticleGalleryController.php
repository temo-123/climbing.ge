<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Abstract\ImageControllService;

use App\Models\Guide\Article_image;
use App\Models\Guide\Article;

use Validator;

class ArticleGalleryController extends Controller
{
    public function get_index_gallery() {
        $articles = Article::where("published", "=", 1)->get();
        $images = [];
        foreach ($articles as $article) {
            foreach ($article->gallery_images as $img) {
                array_push($images, $img);
            }
        }
        
        $items = array_rand($images, 16);
        $imgs = [];
        foreach ($items as $item) {
            array_push($imgs, $images[$item]);
        }

        $returned_images = [];
        foreach ($imgs as $image) {
            $img = Article_image::where('id', '=', $image->id)->first();

            $img_art = $img->article;
            array_push($returned_images, $img);
        }
        
        return $returned_images;
    }

    public function get_editing_images(Request $request)
    {
        return Article_image::where('article_id', '=', $request->article_id)->get();
    }

    public function del_image($id)
    {
        $gallery = Article_image::where('id',strip_tags($id))->first();

        // delete article file
        ImageControllService::image_delete('images/article_gallery_img/', $gallery, 'image');

        // delete product from db
        $gallery -> delete();
    }
}
