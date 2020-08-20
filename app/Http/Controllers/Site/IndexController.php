<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use App\Models\Article;
use App\Models\Gallery;

class IndexController extends Controller
{
    public function index()
    {
        $news = Article::latest('id')->where('category', '=', 'news')->limit(6)->where('published', '=', 1)->get();
        $big_news = Article::latest('id')->where('category', '=', 'news')->limit(1)->where('published', '=', 1)->get();
        // $news_count = Article::where('category', '=', 'news')->count();
 
        $security = Article::latest('id')->where('category', '=', 'security')->where('published', '=', 1)->get();
        $others = Article::where('category', '=', 'other')->limit(4)->where('published', '=', 1)->get();
        $events = Article::latest('id')->where('category', '=', 'event')->where('published', '=', 1)->where('completed', '=', 0)->limit(3)->get();

        $head_slider = Gallery::where('category','=','index_header_image')->latest('id')->where('published', '=', 1)->limit(5)->get();
        $gallery_images = Gallery::where('published', '=', 1)->inRandomOrder()->limit(12)->get();
        
        $tags = Gallery::distinct()->where('published', '=', 1)->get(['filter', 'id']);
        
        return view('site.index', array(
            'news' => $news,
            'big_news' => $big_news,
            'events' => $events,
            'index_others' => $others,

            'head_slider' => $head_slider,
            'index_gallery' => $gallery_images,

            'tags' => $tags,
            'securities' => $security,

            'head_slider_num' => 0,
        )); 
    }
}
