<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Site;
use App\Models\Article;

class AboutController extends Controller
{
    public function index(Request $request)
    {
    	if (view()->exists('site.about_us_page')) {
        	$pages = Site::all();
    
            $partners = Article::latest('id')->where('category', '=', 'partner')->where('published','=','1')->get();
            $partners_count = Article::latest('id')->where('category', '=', 'partner')->where('published','=','1')->count();
    
            return view('site.about_us_page', array(
                'pages'=>$pages,
                'partners' => $partners,
                'partners_count' => $partners_count,
                'thurs_num' => 0,
                
                'article_edit_link'=>'aboutEdit',
            )); 
        }
        abort(404);
    }
}
