<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Site;
use App\Models\Article;
use App\Models\Gallery;

use App\Services\GetArticlesService;

class AboutController extends Controller
{
    public function index(Request $request)
    {
    	if (view()->exists('site.about_us_page')) {
            $pages = Site::all();
    
            $global_partners = Article::latest('id')->limit(5)->where('category', '=', 'partner')->where('published','=','1')->get();
            $partners = GetArticlesService::get_locale_article($global_partners);
            $partners_count = Article::latest('id')->where('category', '=', 'partner')->where('published','=','1')->limit(4)->count();

            // $gallery_images = Gallery::where('published', '=', 1)->where('category', '=', 1)->inRandomOrder()->limit(1)->get();
            
            $locale = request()->segment(1, '');
            if($locale == "ru"){
                $page_locale = "ru";
            }
            elseif ($locale == "ka") {
                $page_locale = "ka";
            } else {
                $page_locale = "us";
            }

            return view('site.about_us_page', array(
                'pages'=>$pages,
                'partners' => $partners,
                'partners_count' => $partners_count,
                'thurs_num' => 0,

                'image_dir' => 'partner_img',

                "page_locale" => $page_locale,
                
                'article_edit_link'=>'aboutEdit',


                // 'gallery_images' => $gallery_images,
            )); 
        }
        abort(404);
    }
}
