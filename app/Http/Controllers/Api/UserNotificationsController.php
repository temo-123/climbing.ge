<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use App\Mail\UserNewArticleMessage;
use App\Notifications\user_notification\ArticleEventNotification;
use App\Notifications\user_notification\ProductEventNotification;
use App\Notifications\user_notification\ServiceEventNotification;
use App\Notifications\user_notification\SectorEventNotification;

use App\Models\Article;
use App\Models\Service_follower;
use App\Models\user_notification;

class UserNotificationsController extends Controller
{
    public function send_article_notification(Request $request)
    {
        $last_article = Article::latest('id')->first();

        $article = [
            'locale_article' => $last_article->us_article,
            'global_article' => $last_article,
        ];

        if($request->notification_category == 'outdoor'){
            $this->sending_loop('add_new_outdoor_spot', $article);
        }
        else if($request->notification_category == 'indoor'){
            $this->sending_loop('add_new_gym', $article);
        }
        else if($request->notification_category == 'news'){
            $this->sending_loop('news', $article);
        }
        else if($request->notification_category == 'ice'){
            $this->sending_loop('add_new_ice_spot', $article);
        }
        else if($request->notification_category == 'techtip'){
            $this->sending_loop('add_new_techtip', $article);
        }
    }

    public function sending_loop($sending_category, $article)
    {
        $notifictions = user_notification::where($sending_category, '=', 1)->get();

        foreach ($notifictions as $notifiction) {
            $user = $notifiction->user;

            $user->notify(new ArticleEventNotification($article));
        }

        foreach (Service_follower::get() as $folewer) {
            $folewer->notify(new ArticleEventNotification($article));
        }
    }


    public function send_product_notification(Request $request)
    {
        //
    }

    public function send_service_notification(Request $request)
    {
        # code...
    }

    public function send_sector_notification(Request $request)
    {
        # code...
    }
}
