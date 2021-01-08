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

class KaArticleController extends Controller
{
    public function add_ka_article(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

            $article = new Ka_article();

            $article['title']=$request->ka_title;
            $article['short_description']=$request->ka_short_description;
            $article['text']=$request->ka_text;
            $article['route']=$request->ka_route;
            $article['how_get']=$request->ka_how_get;
            $article['best_time']=$request->ka_best_time;
            $article['what_need']=$request->ka_what_need;
            $article['info']=$request->ka_info;
            $article['time']=$request->ka_time;
            $article['meta_keyword']=$request->ka_meta_keyword;


            $article -> save();
        }
    }
    public function edit_ka_article(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request->isMethod('post')) {
            $input = $request -> except('_token');
            // dd($request->id);
            $ka_article = Ka_article::find($request->id);

            $ka_article->title = $request->ka_title;
            $ka_article->short_description = $request->ka_short_description;
            $ka_article->text = $request->ka_text;
            $ka_article->route = $request->ka_route;
            $ka_article->how_get = $request->ka_how_get;
            $ka_article->best_time = $request->ka_best_time;
            $ka_article->what_need = $request->ka_what_need;
            $ka_article->info = $request->ka_info;
            $ka_article->time = $request->ka_time;
            $ka_article->meta_keyword = $request->ka_meta_keyword;
            
            $ka_article->update();
        }
    }
}
