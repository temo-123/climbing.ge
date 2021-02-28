<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Interested_Event;

use App\Services\GetArticlesService;

class EventController extends Controller
{
    public function events_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('site.event_page')) {
            $global_event = Article::where('category', '=', 'event')->where('url_title',strip_tags($name))->first();
            // dd($global_event->us_article_id);
            $event = array();
            $event = GetArticlesService::get_locale_article_in_page($global_event);

            // array_push($event, ["global_event"]);
            // dd($event[0]);
            
            $event_id = $global_event->id;
            $interested = Interested_Event::where('article_id', '=', $event_id)->get();
            $comments = Comment::where('article_id','=',$event_id)->get();
            
            $data  = [
                'title'=>$event[0][0]['title'],
                'article'=>$event[0],
                'interested'=>$interested,
                
                'image' => 'event_img/'.$event[0]['image'],

                'comments'=>$comments,
            ];
            return view('site.event_page',$data);
        }
        else{
            abort(404);
        }
    }
}
