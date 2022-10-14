<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Ka_article;
use App\Models\Us_article;
use App\Models\Ru_article;

class TemporaryArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $temporary_article = Article::where("url_title","=","temporary_article")->get();
        foreach ($temporary_article as $article) {
            $last_temporary_article_id = $article->id;
        }

        return(
            $data = [
                "last_temporary_article_id" => $last_temporary_article_id,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $category)
    {
        $article_us = new Us_article();
        $article_us['title']="Us temporary article";
        $article_us -> save();

        $article_ka = new Ka_article();
        $article_ka['title']="Ka temporary article";
        $article_ka -> save();

        $article_ru = new Ru_article();
        $article_ru['title']="Ru temporary article";
        $article_ru -> save();

        $us_articl = Us_article::get();
        foreach ($us_articl as $us) {
            $last_us_article_id = $us->id;
        }

        $ka_articl = Ka_article::get();
        foreach ($ka_articl as $ka) {
            $last_ka_article_id = $ka->id;
        }

        $ru_articl = Ru_article::get();
        foreach ($ru_articl as $ru) {
            $last_ru_article_id = $ru->id;
        }

        $article = new Article();
        $article['url_title'] = 'temporary_article';
        $article['category']=$request->category;
        $article['published']=0;
        $article['completed']=1; 

        $article['us_article_id']=$last_us_article_id;
        $article['ru_article_id']=$last_ru_article_id;
        $article['ka_article_id']=$last_ka_article_id;

        $article -> save();

        return 'temporary article created sucsesful';
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
}
