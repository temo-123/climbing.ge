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

class RuArticleController extends Controller
{
    public function add_ru_article(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

            // parse_str($request->getContent(),$data); 

            $article = new Ru_article();

            $article['title']=$request->ru_title;
            $article['short_description']=$request->ru_short_description;
            $article['text']=$request->ru_text;
            $article['route']=$request->ru_route;
            $article['how_get']=$request->ru_how_get;
            $article['best_time']=$request->ru_best_time;
            $article['what_need']=$request->ru_what_need;
            $article['info']=$request->ru_info;
            $article['time']=$request->ru_time;
            $article['price_from']=$request->ru_price_from;
            $article['meta_keyword']=$request->ru_meta_keyword;
            
            $article -> save();
        }
    }
    public function edit_ru_article(Type $var = null)
    {
        # code...
    }
}
