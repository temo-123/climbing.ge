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
        
        if(count($images) > 0) {
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
    }
}
