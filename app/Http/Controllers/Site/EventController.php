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

            $event_countdown = $this->event_countdown($global_event);

            $data  = [
                'title'=>$event[0][0]['title'],
                'article'=>$event[0],
                'event_countdown'=>$event_countdown,
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

    public function event_countdown($global_event)
    {
        $month = '';
        $countdown = '';

        if ($global_event != NULL) {
            if($global_event['start_data_month'] == 'January'){
                $month = '01';
            }
            elseif ($global_event['start_data_month'] == 'February') {
                $month = '02';
            }
            elseif ($global_event['start_data_month'] == 'March') {
                $month = '03';
            }
            elseif ($global_event['start_data_month'] == 'April') {
                $month = '04';
            }
            elseif ($global_event['start_data_month'] == 'May') {
                $month = '05';
            }
            elseif ($global_event['start_data_month'] == 'June') {
                $month = '06';
            }
            elseif ($global_event['start_data_month'] == 'July') {
                $month = '07';
            }
            elseif ($global_event['start_data_month'] == 'August') {
                $month = '08';
            }
            elseif ($global_event['start_data_month'] == 'September') {
                $month = '09';
            }
            elseif ($global_event['start_data_month'] == 'October') {
                $month = '10';
            }
            elseif ($global_event['start_data_month'] == 'November') {
                $month = '11';
            }
            elseif ($global_event['start_data_month'] == 'December') {
                $month = '12';
            }
        }

        $event_time = mktime(0,0,0,$month,$global_event['start_data_day'],date('Y',strtotime('now')));
        $countdown = round(($event_time - time())/86400);

        if($countdown > 0){
            $event_countdown = $countdown.' - days until event!';
        }
        else{
            $event_countdown = 'The event has already passed!';
        }

        return $event_countdown;
    }
}
