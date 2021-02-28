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

            $ru_articl = Ru_article::get();
            foreach ($ru_articl as $ru) {
                $last_ru_article_id = $ru->id;
            }


            $article = Ru_article::find($last_ru_article_id);
            // $article = new Ru_article();

            $article['title']=$request->ru_title;
            $article['short_description']=$request->ru_short_description;
            $article['text']=$request->ru_text;
            $article['route']=$request->ru_route;
            $article['how_get']=$request->ru_how_get;
            $article['best_time']=$request->ru_best_time;
            $article['what_need']=$request->ru_what_need;
            $article['info']=$request->ru_info;
            $article['meta_keyword']=$request->ru_meta_keyword;
            
            $article -> update();
        }
    }
    public function edit_ru_article(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request->isMethod('post')) {
            $input = $request -> except('_token');
            
            $ru_article = ru_article::find($request->id);

            $ru_article->title = $request->ru_title;
            $ru_article->short_description = $request->ru_short_description;
            $ru_article->text = $request->ru_text;
            $ru_article->route = $request->ru_route;
            $ru_article->how_get = $request->ru_how_get;
            $ru_article->best_time = $request->ru_best_time;
            $ru_article->what_need = $request->ru_what_need;
            $ru_article->info = $request->ru_info;
            $ru_article->meta_keyword = $request->ru_meta_keyword;
            
            $ru_article->update();
        }
    }
}
