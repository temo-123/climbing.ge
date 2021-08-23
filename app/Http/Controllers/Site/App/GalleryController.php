<?php

namespace App\Http\Controllers\Site\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gallery;

class GalleryController extends Controller
{
    public function get_article_image(Request $request)
    {
        $gallery = array();
        $full_image = '';
        $image = '';

        $gallery_images = Gallery::where('article_id',strip_tags($request->id))->limit(8)->get();
        $image_url = 'http://climbing.loc/images/gallery_img/';

        foreach ($gallery_images as $gallery_img) {
            $image = $gallery_img->image;
            $image = strval($image);
            $full_image = $image_url . $image;
            array_push($gallery, $full_image);
        }

        return $gallery;
    }
}
