<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Film;
use App\Models\Film_category;
use App\Models\Favorite_film;

use App\Services\GetFilmService;
use Auth;

class FilmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Film::get();
    }

    public function get_films(Request $request)
    {
        $films = Film::where('public', '=', 1)->get();
        return GetFilmService::get_films_list_use_locale($films, $request->locale);
    }

    public function get_same_films(Request $request){
        $films = Film::where('public', '=', 1)->where('category_id', '=', $request->category_id)->where('id', '!=', $request->film_id)->limit(4)->get();
        return GetFilmService::get_films_list_use_locale($films, $request->locale);
    }

    public function get_film(Request $request){
        $film = Film::where('public', '=', 1)->where('url_title', '=', $request->url_title)->first();
        return GetFilmService::get_film_on_page_use_locale($film, $request->locale);
    }

    public function get_films_categories(Request $request){
        return Film_category::get();
    }

    public function get_films_top(Request $request)
    {
        if($request->top_film_type == 'get_most_liked_films'){
            $films_likes = [];
            
            foreach (Film::where('public', '=', 1)->get() as $film) {
                $likes_count = Favorite_film::where('film_id', '=', $film->id)->count();
                array_push(
                    $films_likes,
                    [
                        'film_lokes_count' => $likes_count,
                        'film_id' => $film->id,
                    ]
                );
            }
            // dd($films_likes);

            // $mostly_likes = array_search(max($films_likes), $films_likes);
            $mostly_likes = max($films_likes);
            // dd($mostly_likes);

            $bigest_film_id = 1;

            foreach ($films_likes as $l_film) {
                if($l_film['film_lokes_count'] == $mostly_likes){
                    $bigest_film_id = $l_film['film_id'];
                }
            }

            // return Film::where('public', '=', 1)->where('id', '=', $bigest_film_id)->first();

            $film = Film::where('public', '=', 1)->where('id', '=', $bigest_film_id)->get();
            return GetFilmService::get_films_list_use_locale($film, $request->locale);

        }
    }

    public function add_to_faworite(Request $request)
    {
        // dd(Auth::user());
        if (Auth::user()) {
            
            if(Favorite_film::where('user_id', '=', Auth::user()->id)->where('film_id', '=', $request->film_id)->count() > 0){
                // $editing_faworit = Favorite_film::where('user_id', '=', Auth::user()->id)->where('film_id', '=', $request->film_id)->first();

                // $editing_faworit['user_id'] = Auth::user()->id;
                // $editing_faworit['film_id'] = $request->film_id;
                
                // $editing_faworit -> save();

                return 'this film are in faworite';
            }
            else{
                $faworit = new Favorite_film();
            
                $faworit['user_id'] = Auth::user()->id;
                $faworit['film_id'] = $request->film_id;
                
                $faworit -> save();

                return 'film eded socsesful';
            }
        }
        else{
            return 'ples login';
        }
    }

    public function films_search(Request $request)
    {
        if ($query != "") {
            $count_articles = 0;
            // $count_articles = Film::where('published', '=', 1)->Where('url_title', 'LIKE', '%'.$query.'%')->count();
            // dd($count_articles);
            if ($count_articles > 0) {
                // $global_articles = Article::
                //                             where('published', '=', 1)->
                //                             where('category', '!=', 'news')->
                //                             where('category', '!=', 'security')->
                //                             where('category', '!=', 'event')->
                //                             where('category', '!=', 'partner')->
                //                             Where('url_title', 'LIKE', '%'.$query.'%')->
                //                             get();
                // $articles = GetArticlesService::get_locale_article($global_articles);
                // return $articles;
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
