<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Interested_Event;

class EventController extends Controller
{
    public function events_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('site.event_page')) {
            $events = Article::where('category', '=', 'event')->where('url_title',strip_tags($name))->first();
            $event_id = $events->id;
            $interested = Interested_Event::where('article_id', '=', $event_id)->get();
            $comments = Comment::where('article_id','=',$event_id)->get();
            
            $data  = [
                'title'=>$events->title,
                'events'=>$events,
                'interested'=>$interested,

                'comments'=>$comments,
            ];
            return view('site.event_page',$data);
        }
        else{
            abort(404);
        }
    }
}
