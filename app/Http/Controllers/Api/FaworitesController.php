<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

use App\Services\GetArticlesService;

use App\Models\Interested_event;
use App\Models\Favorite_outdoor_area;
use App\Models\Article;

class FaworitesController extends Controller
{
    public function add_to_interested_events(Request $request)
    {
        if (Auth::user()) {
            
            if(Interested_event::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $request->event_id)->count() > 0){
                return 'this event are in faworite';
            }
            else{
                $faworit = new Interested_event();
            
                $faworit['user_id'] = Auth::user()->id;
                $faworit['article_id'] = $request->event_id;
                
                $faworit -> save();

                return 'event eded socsesful';
            }
        }
        else{
            return 'ples login';
        }
    }

    public function get_interested_events(Request $request)
    {
        if (Auth::user()) {
            $fav_area = Interested_event::where('user_id', '=', Auth::user()->id)->get();
            $articles = [];
            foreach ($fav_area as $area) {
                $global_articles = Article::where('id', '=', $area->article_id)->get();
                $outdoors = GetArticlesService::get_locale_article_use_locale($global_articles, $request->lang);
                array_push($articles, $outdoors[0]);
            }
            
            return $articles;
        }
        else{
            return 'Plees login!';
        }
    }

    public function del_interested_event(Request $request)
    {
        if (Auth::user()) {
            $fav_area = Interested_event::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $request->article_id)->first();
            $fav_area ->delete();
        }
        else{
            return 'Plees login!';
        }
    }



    public function add_to_favorite_outdoor_area(Request $request)
    {
        if (Auth::user()) {
            
            if(Favorite_outdoor_area::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $request->article_id)->count() > 0){
                return 'this area olredy are in faworite';
            }
            else{
                $faworit = new Favorite_outdoor_area();
            
                $faworit['user_id'] = Auth::user()->id;
                $faworit['article_id'] = $request->article_id;
                
                $faworit -> save();

                return 'area eded socsesful';
            }
        }
        else{
            return 'ples login';
        }
    }


    public function get_faworite_outdoor_region(Request $request)
    {
        if (Auth::user()) {
            $fav_area = Favorite_outdoor_area::where('user_id', '=', Auth::user()->id)->get();
            $articles = [];
            foreach ($fav_area as $area) {
                $global_articles = Article::where('id', '=', $area->article_id)->get();
                $outdoors = GetArticlesService::get_locale_article_use_locale($global_articles, $request->lang);
                array_push($articles, $outdoors[0]);
            }
            
            return $articles;
        }
        else{
            return 'Plees login!';
        }
    }

    public function del_faworite_outdoor_region(Request $request)
    {
        if (Auth::user()) {
            $fav_area = Favorite_outdoor_area::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $request->article_id)->first();
            $fav_area ->delete();
        }
        else{
            return 'Plees login!';
        }
    }
}
