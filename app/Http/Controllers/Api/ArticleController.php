<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Services\GetArticlesService;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Article::get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->addGlobamArticle();
        $this->add_ka_article();
        $this->add_ru_article();
        $this->add_us_article();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Article::latest('id')->where('category', '=', $request->category)->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($url_title)
    {
        // dd($url_title);
        $global_news = Article::where('url_title',strip_tags($url_title))->first();
        $news = GetArticlesService::get_locale_article_in_page($global_news);

        return $news[0];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $activ_article = Article::where('id', '=', $id)->first();
        $global_other_articles = Article::limit(6)->
                                        latest('id')->
                                        where('category', '=', $activ_article->category)->
                                        where('id', '!=', $activ_article->id)->
                                        where('published', '=', 1)->
                                        get();
        $other_articles = GetArticlesService::get_locale_article($global_other_articles);

        return $other_articles;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }








    public function addGlobamArticle()
    {
        // $request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');
            $this->global_article_validate($request);

            $global_article = Article::get();
            foreach ($global_article as $global) {
                $last_globale_id = $global->id;
            }

            // make url_title from us_title value
            $url_title = URLTitleService::get_url_title($request->us_title_for_url_title);

            $article = Article::find($last_globale_id);
            
            $article['url_title'] = $url_title;

            $article['category']=$request->category;
            $article['mount_id']=$request->mount_id;
            $article['published']=$request->published;
            $article['completed']=$request->completed; 
            $article['map']=$request->map;
            $article['weather']=$request->weather; 

            $article['start_data_day']=$request->start_data_day;
            $article['end_data_day']=$request->end_data_day;
            $article['end_data_month']=$request->end_data_month;
            $article['start_data_month']=$request->start_data_month;

            $article['working_time'] = $request->working_time;
            $article['price_from'] = $request->price_from;

            $article['fb_link']=$request->fb_link;
            $article['twit_link']=$request->twit_link; 
            $article['google_link']=$request->google_link;
            $article['inst_link']=$request->inst_link;
            $article['web_link']=$request->web_link;
            
            $article -> update();
        }
    }
    public function add_ka_article(Request $request)
    {
        // $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            $this->ka_sector_validate($request);

            $ka_articl = Ka_article::get();
            foreach ($ka_articl as $ka) {
                $last_ka_article_id = $ka->id;
            }

            $article = Ka_article::find($last_ka_article_id);
            
            $article['title']=$request->ka_title;
            $article['short_description']=$request->ka_short_description;
            $article['text']=$request->ka_text;
            $article['route']=$request->ka_route;
            $article['how_get']=$request->ka_how_get;
            $article['best_time']=$request->ka_best_time;
            $article['what_need']=$request->ka_what_need;
            $article['info']=$request->ka_info;
            $article['meta_keyword']=$request->ka_meta_keyword;

            $article -> update();
        }
    }
    public function add_ru_article(Request $request)
    {
        // $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            $this->ru_sector_validate($request);

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
    public function add_us_article(Request $request)
    {
        // $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            $this->us_sector_validate($request);

            $us_articl = Us_article::get();
            foreach ($us_articl as $us) {
                $last_us_article_id = $us->id;
            }

            $article = Us_article::find($last_us_article_id);
            // $article = new Us_article();

            $article['title']=$request->us_title;
            $article['short_description']=$request->us_short_description;
            $article['text']=$request->us_text;
            $article['route']=$request->us_route;
            $article['how_get']=$request->us_how_get;
            $article['best_time']=$request->us_best_time;
            $article['what_need']=$request->us_what_need;
            $article['info']=$request->us_info;
            $article['meta_keyword']=$request->us_meta_keyword;

            $article -> update();
        }
    }
}
