<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Services\ArticlesService;
use App\Models\Guide\Interested_event;
use App\Models\Guide\Favorite_outdoor_area;
use App\Models\Guide\Article;
use App\Models\Guide\Event;
use App\Jobs\UserNotifications;

class FaworitesController extends Controller
{
    public function add_to_interested_events(Request $request)
    {
        if (Auth::user()) {
            if(Interested_event::where('user_id', '=', Auth::user()->id)->where('event_id', '=', $request->event_id)->count() > 0){
                return 'this event are in faworite';
            }
            else{
                $faworit = new Interested_event;
            
                $faworit['user_id'] = Auth::user()->id;
                $faworit['event_id'] = $request->event_id;

                $faworit -> save();

                $global_event = Event::where('id', '=', $request->event_id)->first();
                if($global_event){
                    $locale_event = $global_event->us_event;
                    $url = config('app.base_url_ssh').'/event/'.$global_event->url_title;
                    $text = 'Less than 1 week left until your favorite event, ' . $locale_event->title . ' Visit our website for more information.';
                    $subject = $locale_event->title;
                }

                UserNotifications::dispatch($url, $text, $subject, Auth::user()->email)->onQueue('emails');

                return 'Event added in your favorite event list successfully';
            }
        }
        else{
            return 'Plees login';
        }
    }

    public function get_interested_events(Request $request)
    {
        if (Auth::user()) {
            $fav_area = Interested_event::where('user_id', '=', Auth::user()->id)->get();
            $articles = [];
            foreach ($fav_area as $area) {
                $global_events = Event::where('id', '=', $area->event_id)->get();
                // $outdoors = ArticlesService::get_locale_article_use_locale($global_events, $request->lang);

                // dd($global_events);
                array_push($articles, $global_events[0]);
            }
            
            return $articles;
        }
        else{
            return 'Plees login!';
        }
    }

    public function del_interested_event(Request $request, $favoryte_ivent_if)
    {
        if (Auth::user()) {
            $fav_area = Interested_event::where('user_id', '=', Auth::user()->id)->where('event_id', '=', $favoryte_ivent_if)->first();
            if ($fav_area) {
                $fav_area->delete();
                return 'Event removed from favorites successfully';
            }
            return 'Event not found in favorites';
        }
        else{
            return 'Please login';
        }
    }



    public function add_to_favorite_outdoor_area(Request $request)
    {
        if (Auth::user()) {
            if(Favorite_outdoor_area::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $request->article_id)->count() > 0){
                return 'This area olredy are in faworite';
            }
            else{
                $faworit = new Favorite_outdoor_area();
            
                $faworit['user_id'] = Auth::user()->id;
                $faworit['article_id'] = $request->article_id;
                
                $faworit -> save();

                return 'Area added successfully!';
            }
        }
        else{
            // return 'Ples login';
            return response()->json('Plees login', 401);
        }
    }


    public function get_faworite_outdoor_region(Request $request)
    {
        if (Auth::user()) {
            $fav_area = Favorite_outdoor_area::where('user_id', '=', Auth::user()->id)->get();
            $articles = [];
            foreach ($fav_area as $area) {
                $global_articles = Article::where('id', '=', $area->article_id)->get();
                $outdoors = ArticlesService::get_locale_article_use_locale($global_articles);
                array_push($articles, $outdoors[0]);
            }

            return $articles;
        }
        else{
            return 'Plees login!';
        }
    }


    public function del_favorite_outdoor_area(Request $request)
    {
        if (Auth::user()) {
            $fav_area = Favorite_outdoor_area::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $request->favorite_outdoor_id)->first();
            $fav_area ->delete();
            return 'Outdoor area removed from favorites successfully';
        }
        else{
            return 'Plees login!';
        }
    }

    public function check_favorite_status(Request $request, $article_id)
    {
        if (Auth::user()) {
            $is_favorite = Favorite_outdoor_area::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $article_id)->exists();
            return response()->json(['is_favorite' => $is_favorite]);
        }
        else{
            return response()->json(['is_favorite' => false], 401);
        }
    }

    public function check_interested_status(Request $request, $event_id)
    {
        if (Auth::user()) {
            $is_interested = Interested_event::where('user_id', '=', Auth::user()->id)->where('event_id', '=', $event_id)->exists();
            return response()->json(['is_interested' => $is_interested]);
        }
        else{
            return response()->json(['is_interested' => false], 401);
        }
    }
}
