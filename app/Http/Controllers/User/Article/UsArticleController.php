<?php

namespace App\Http\Controllers\User\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Ka_article;
use App\Models\Us_article;
use App\Models\Ru_article;
use App\Models\Comment;

use App\Services\ImageEditService;

use File;

class UsArticleController extends Controller
{
    public function add_us_article(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

            // parse_str($request->getContent(),$data); 

            $article = new Us_article();

            $article['title']=$request->us_title;
            $article['short_description']=$request->us_short_description;
            $article['text']=$request->us_text;
            $article['route']=$request->us_route;
            $article['how_get']=$request->us_how_gety;
            $article['best_time']=$request->us_best_time;
            $article['what_need']=$request->us_what_need;
            $article['info']=$request->us_info;
            $article['time']=$request->us_time;
            $article['price_from']=$request->us_price_from;
            $article['meta_keyword']=$request->us_meta_keyword;

            $article -> save();
        }
    }
    public function edit_us_article(Type $var = null)
    {
        # code...
    }
}
