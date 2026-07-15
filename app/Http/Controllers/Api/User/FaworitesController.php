<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $user = $request->user();

        if (Interested_event::where('user_id', $user->id)->where('event_id', $request->event_id)->exists()) {
            return response()->json(['message' => 'Already in favorites']);
        }

        $faworit = new Interested_event;
        $faworit['user_id'] = $user->id;
        $faworit['event_id'] = $request->event_id;
        $faworit->save();

        $global_event = Event::where('id', $request->event_id)->first();
        if ($global_event && $global_event->us_event) {
            $locale_event = $global_event->us_event;
            $url = config('app.base_url_ssh').'/event/'.$global_event->url_title;
            $text = 'Less than 1 week left until your favorite event, ' . $locale_event->title . ' Visit our website for more information.';
            $subject = $locale_event->title;
            UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');
        }

        return response()->json(['message' => 'Event added to favorites']);
    }

    public function get_interested_events(Request $request)
    {
        $user = $request->user();

        $fav_items = Interested_event::where('user_id', $user->id)->get();
        $articles = [];
        foreach ($fav_items as $item) {
            $event = Event::with(['us_event', 'ka_event'])->where('id', $item->event_id)->where('published', 1)->first();
            if ($event) {
                array_push($articles, $event);
            }
        }

        return response()->json($articles);
    }

    public function del_interested_event(Request $request, $favoryte_ivent_if)
    {
        $user = $request->user();

        $fav_area = Interested_event::where('user_id', $user->id)->where('event_id', $favoryte_ivent_if)->first();
        if ($fav_area) {
            $fav_area->delete();
            return 'Event removed from favorites successfully';
        }
        return 'Event not found in favorites';
    }

    public function add_to_favorite_outdoor_area(Request $request)
    {
        $user = $request->user();

        if (Favorite_outdoor_area::where('user_id', $user->id)->where('article_id', $request->article_id)->count() > 0) {
            return 'This area olredy are in faworite';
        }

        $faworit = new Favorite_outdoor_area();
        $faworit['user_id'] = $user->id;
        $faworit['article_id'] = $request->article_id;
        $faworit->save();

        return 'Area added successfully!';
    }

    public function get_faworite_outdoor_region(Request $request)
    {
        $user = $request->user();

        $fav_area = Favorite_outdoor_area::where('user_id', $user->id)->get();
        $articles = [];
        foreach ($fav_area as $area) {
            $global_articles = Article::where('id', $area->article_id)->where('published', 1)->get();
            if ($global_articles->isEmpty()) continue;
            $outdoors = ArticlesService::get_locale_article_use_locale($global_articles);
            if (!empty($outdoors)) {
                array_push($articles, $outdoors[0]);
            }
        }

        return response()->json($articles);
    }

    public function del_favorite_outdoor_area(Request $request)
    {
        $user = $request->user();

        $fav_area = Favorite_outdoor_area::where('user_id', $user->id)
            ->where('article_id', $request->article_id)
            ->first();

        if ($fav_area) {
            $fav_area->delete();
        }

        return response()->json(['message' => 'Removed from favorites']);
    }

    public function check_favorite_status(Request $request, $article_id)
    {
        $user = $request->user();

        $is_favorite = Favorite_outdoor_area::where('user_id', $user->id)->where('article_id', $article_id)->exists();
        return response()->json(['is_favorite' => $is_favorite]);
    }

    public function check_interested_status(Request $request, $event_id)
    {
        $user = $request->user();

        $is_interested = Interested_event::where('user_id', $user->id)->where('event_id', $event_id)->exists();
        return response()->json(['is_interested' => $is_interested]);
    }
}
