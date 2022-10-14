<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Models\Interested_Event;
use App\Models\Article;
use App\Services\GetArticlesService;

class InteestedEventCntroller extends Controller
{
    public function interested(Request $request)
    {
        if (view()->exists('user.inteested_event')) {
            $interested_events = Interested_Event::latest('id')->where("user_id","=",Auth::user()->id)->get();
            $interested_events_count = Interested_Event::latest('id')->where("user_id","=",Auth::user()->id)->count();

            $events = array();

            if ($interested_events_count > 0) {
                foreach ($interested_events as $interested_event) {
                    $global_events = Article::latest('id')->where('id', '=', $interested_event->article_id)->where('category', '=', 'event')->where('published', '=', 1)->where('completed', '=', 0)->limit(3)->get();
                    $locale_article = GetArticlesService::get_locale_article($global_events);
                    array_push($events, $locale_article);
                }
            }

    		$data = [
    			'title'=>'events',
    			'events'=>$events,
                'page_name'=>'interested_events',
                'active' => 'inteested event',

    			'user'=>1,
                'num' => 1,
                
                'articles_link' => 'other_page',
                'image_dir' => 'user_img',
    		];
    		return view('user.inteested_event',$data);
    	}
    	abort(404);
    }
}
