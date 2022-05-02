<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Services\GetArticlesService;

class OtherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $others_count = Article::where('category', '=', 'other')->where('published', '=', 1)->count();
        $others = [];
        if ($others_count > 0) {
            $global_others = Article::where('category', '=', 'other')->where('published', '=', 1)->get();
            $others = GetArticlesService::get_locale_article($global_others);
        }

        return $others;
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
}
