<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Site;
use App\Models\Article;

use App\Services\GetArticlesService;

class AboutController extends Controller
{
    public function index(Request $request)
    {
    	if (view()->exists('site.about_us_page')) {
            $pages = Site::all();
    
            $global_partners = Article::latest('id')->where('category', '=', 'partner')->where('published','=','1')->get();
            $partners = GetArticlesService::get_locale_article($global_partners);
            $partners_count = Article::latest('id')->where('category', '=', 'partner')->where('published','=','1')->count();
            
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

                "page_locale" => $page_locale,
                
                'article_edit_link'=>'aboutEdit',
            )); 
        }
        abort(404);
    }
}
