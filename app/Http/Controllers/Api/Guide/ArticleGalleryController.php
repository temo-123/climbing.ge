<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Abstract\ImageControllService;

use App\Models\Guide\Article_image;

use Validator;

class ArticleGalleryController extends Controller
{
    public function get_index_gallery() {
        $images = Article_image::inRandomOrder()->limit(16)->get();
        $returned_images = [];
        foreach ($images as $image) {
            $img = $image->article;
            array_push($returned_images, $image);
        }
        return $images;
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
