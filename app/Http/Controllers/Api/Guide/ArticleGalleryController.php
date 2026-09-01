<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Abstract\ImageControllService;

use App\Models\Guide\Article;

use Validator;

class ArticleGalleryController extends Controller
{
    public function get_index_gallery() {
        $articles = Article::where("published", "=", 1)->with('gallery_images')->get();
        $images = [];
        foreach ($articles as $article) {
            foreach ($article->gallery_images as $img) {
                // Attach just enough of the source article for the frontend to
                // link back to it ("show region"/"show article" from the lightbox).
                $img->article = $article->only(['id', 'category', 'url_title']);
                array_push($images, $img);
            }
        }

        if(count($images) > 0) {
            $count = min(16, count($images));
            $items = array_rand($images, $count);
            if (!is_array($items)) $items = [$items];

            $returned_images = [];
            foreach ($items as $item) {
                array_push($returned_images, $images[$item]);
            }

            return $returned_images;
        }

        return [];
    }
}
