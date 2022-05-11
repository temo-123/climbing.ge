<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Gallery_image;

use App\Services\GetArticlesService;

class IndexController extends Controller
{
    public function index()
    {
        $global_news = Article::latest('id')->where('category', '=', 'news')->limit(6)->where('published', '=', 1)->get();
        $news = GetArticlesService::get_locale_article($global_news,);

        $global_big_news = Article::latest('id')->where('category', '=', 'news')->limit(1)->where('published', '=', 1)->get();
        $big_news = GetArticlesService::get_locale_article($global_big_news,);
        
        $global_security = Article::latest('id')->where('category', '=', 'security')->where('published', '=', 1)->get();
        $security = GetArticlesService::get_locale_article($global_security,);

        $global_others = Article::where('category', '=', 'other')->limit(4)->where('published', '=', 1)->get();
        $others = GetArticlesService::get_locale_article($global_others,);

        $global_events = Article::latest('id')->where('category', '=', 'event')->where('published', '=', 1)->where('completed', '=', 0)->limit(3)->get();
        $events = GetArticlesService::get_locale_article($global_events,);

        $head_slider = Gallery_image::where('image_type','=','header_image')->latest('id')->where('published', '=', 1)->limit(5)->get();
        $gallery_images = Gallery_image::where('image_type', '=', 'index_gallery_image')->where('published', '=', 1)->inRandomOrder()->limit(12)->get();
        
        return view('site.index', array(
            'news' => $news,
            'big_news' => $big_news,
            'events' => $events,
            'index_others' => $others,

            'head_slider' => $head_slider,
            'index_gallery' => $gallery_images,

            'securities' => $security,

            'head_slider_num' => 0,
        )); 
    }

    // public function index()
    // {
    //     echo Ka_article::find(1)->global_article->url_title;
    //     // $glob_articles = Article::all();
    //     // foreach ($glob_articles as $glob_article) {
    //     //     echo $glob_article['url_title'].'<br>';
    //     //     echo $glob_article->us_article.'<br>';
    //     // }
    // }

    // // public function get_index_gallery_image(Request $request)
    // // {
    //     // $gallery = array();
    //     // $full_image = '';
    //     // $image = '';

    //     // $gallery_images = Gallery::limit(8)->get();
    //     // $image_url = config('app.url').'/images/gallery_img/';

    //     // foreach ($gallery_images as $gallery_img) {
    //     //     $image = $gallery_img->image;
    //     //     $image = strval($image);
    //     //     $full_image = $image_url . $image;
    //     //     array_push($gallery, $full_image);
    //     // }
    //     // dd($gallery);
    //     // // return $gallery;
    //     // return response()->json($gallery);
    // // }
}
