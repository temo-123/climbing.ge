<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Services\GetArticlesService;

class ArticlesSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show($query)
    {
	    if ($query != "") {
            $count_articles = Article::where('published', '=', 1)->Where('url_title', 'LIKE', '%'.$query.'%')->count();
            // dd($count_articles);
            if ($count_articles > 0) {
                $global_articles = Article::
                                            where('published', '=', 1)->
                                            where('category', '!=', 'news')->
                                            where('category', '!=', 'security')->
                                            where('category', '!=', 'event')->
                                            where('category', '!=', 'partner')->
                                            Where('url_title', 'LIKE', '%'.$query.'%')->
                                            get();
                $articles = GetArticlesService::get_locale_article($global_articles);
                return $articles;
            }
            else {
                return '0 articles is fined. Try to search again !';
            }
        }
        else {
            return 'No Details found. Try to search again !';
        }
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
        //
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
