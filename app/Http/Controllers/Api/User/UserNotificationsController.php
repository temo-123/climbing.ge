<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use App\Mail\UserNewArticleMessage;
use App\Notifications\user_notification\ArticleEventNotification;
use App\Notifications\user_notification\ProductEventNotification;
use App\Notifications\user_notification\ServiceEventNotification;
use App\Notifications\user_notification\SectorEventNotification;

use App\Models\Article;
use App\Models\Product;
use App\Models\Service;
use App\Models\Sector;
use App\Models\Event;
use App\Models\Service_follower;
use App\Models\user_notification;

use App\Jobs\UserNotifications;

class UserNotificationsController extends Controller
{
    public function send_user_favorites_notification(Request $request)
    {
        $url = '';
        $text = '';
        $subject = '';

        $user_notifictions = user_notification::where($request->action, '=', 1)->get();
        if($user_notifictions){
            foreach ($user_notifictions as $notifiction) {
                $user = $notifiction->user;

                if($request->action == 'interested_event' && $user->interested_events){
                    if ($request->event_notification_type == 'select_event') {
                        $interested_event = $user->interested_events->where('event_id', '=', $request->id)->first();
                        if($interested_event){
                            $global_event = $interested_event->event;
                            $locale_event;
                            if($user->lang == 'ka'){
                                $locale_event = $global_event->ka_event;
                                $url = config('app.base_url_ssh').'/ka/event/'.$global_event->url_title;
                                $text = 'შეამოწმეთ თქვენი ართერთი საყვარელი საცოცი რეგიონი საქართველოში, (' . $locale_event->title . ') რათა გაიგოთ მეტი სიახლე მის შესახებ.';
                                $subject = $locale_event->title;
                            }
                            else if($user->lang == 'ru'){
                                $locale_event = $global_event->ru_event;
                                $url = config('app.base_url_ssh').'/ru/event/'.$global_event->url_title;
                                $text = 'Посетите одно из ваших любимых мест для скалолазания в Грузии, (' . $locale_event->title . ') чтобы узнать, что нового.';
                                $subject = $locale_event->title;
                            }
                            else{
                                $locale_event = $global_event->us_event;
                                $url = config('app.base_url_ssh').'/event/'.$global_event->url_title;
                                $text = 'Check one of your, favorite event climbing area in Georgia, (' . $locale_event->title . ') for check what is new.';
                                $subject = $locale_event->title;
                            }

                            UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');

                            return 'Send message socsesfuly';
                        }
                    }
                    elseif ($request->event_notification_type == 'next') {
                        $global_event = Event::orderBy('start_data')->first();
                        if($global_event){
                            $locale_event;
                            if($user->lang == 'ka'){
                                $locale_event = $global_event->ka_event;
                                $url = config('app.base_url_ssh').'/ka/event/'.$global_event->url_title;
                                $text = 'შეამოწმეთ თქვენი ართერთი საყვარელი საცოცი რეგიონი საქართველოში, (' . $locale_event->title . ') რათა გაიგოთ მეტი სიახლე მის შესახებ.';
                                $subject = $locale_event->title;
                            }
                            else if($user->lang == 'ru'){
                                $locale_event = $global_event->ru_event;
                                $url = config('app.base_url_ssh').'/ru/event/'.$global_event->url_title;
                                $text = 'Посетите одно из ваших любимых мест для скалолазания в Грузии, (' . $locale_event->title . ') чтобы узнать, что нового.';
                                $subject = $locale_event->title;
                            }
                            else{
                                $locale_event = $global_event->us_event;
                                $url = config('app.base_url_ssh').'/event/'.$global_event->url_title;
                                $text = 'Check one of your, favorite event climbing area in Georgia, (' . $locale_event->title . ') for check what is new.';
                                $subject = $locale_event->title;
                            }

                            UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');

                            return 'Send message socsesfuly';
                        }
                    }
                }
                else if($request->action == 'favorite_product' && $user->favorite_products){
                    $favorite_products = $user->favorite_products->where('id', '=', $request->id)->first();
                    if($favorite_products){
                        $global_product = $favorite_products->product;
                        $locale_product;
                        if($user->lang == 'ka'){
                            $locale_product = $global_product->ka_article;
                            $url = config('app.base_url_ssh').'/ka/product/'.$global_product->url_title;
                            $text = 'შეამოწმეთ თქვენი ართერთი საყვარელი საცოცი რეგიონი საქართველოში, (' . $locale_product->title . ') რათა გაიგოთ მეტი სიახლე მის შესახებ.';
                            $subject = $locale_product->title;
                        }
                        else if($user->lang == 'ru'){
                            $locale_product = $global_product->ru_article;
                            $url = config('app.base_url_ssh').'/ru/product/'.$global_product->url_title;
                            $text = 'Посетите одно из ваших любимых мест для скалолазания в Грузии, (' . $locale_product->title . ') чтобы узнать, что нового.';
                            $subject = $locale_product->title;
                        }
                        else{
                            $locale_product = $global_product->us_article;
                            $url = config('app.base_url_ssh').'/product/'.$global_product->url_title;
                            $text = 'Check one of your, favorite product climbing area in Georgia, (' . $locale_product->title . ') for check what is new.';
                            $subject = $locale_product->title;
                        }

                        UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');

                        return 'Send message socsesfuly';
                    }
                }
                else if($request->action == 'favorite_outdoor' && $user->favorite_outdoors){
                    $faworite_outdoor = $user->favorite_outdoors->where('article_id', '=', $request->id)->first();
                    if($faworite_outdoor){
                        $global_outdoor = $faworite_outdoor->outdoor_area;
                        $locale_outdoor;
                        if($user->lang == 'ka'){
                            $locale_outdoor = $global_outdoor->ka_article;
                            $url = config('app.base_url_ssh').'/ka/outdoor/'.$global_outdoor->url_title;
                            $text = 'შეამოწმეთ თქვენი ართერთი საყვარელი საცოცი რეგიონი საქართველოში, (' . $locale_outdoor->title . ') რათა გაიგოთ მეტი სიახლე მის შესახებ.';
                            $subject = $locale_outdoor->title;
                        }
                        else if($user->lang == 'ru'){
                            $locale_outdoor = $global_outdoor->ru_article;
                            $url = config('app.base_url_ssh').'/ru/outdoor/'.$global_outdoor->url_title;
                            $text = 'Посетите одно из ваших любимых мест для скалолазания в Грузии, (' . $locale_outdoor->title . ') чтобы узнать, что нового.';
                            $subject = $locale_outdoor->title;
                        }
                        else{
                            $locale_outdoor = $global_outdoor->us_article;
                            $url = config('app.base_url_ssh').'/outdoor/'.$global_outdoor->url_title;
                            $text = 'Check one of your, favorite outdoor climbing area in Georgia, (' . $locale_outdoor->title . ') for check what is new.';
                            $subject = $locale_outdoor->title;
                        }

                        UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');

                        return 'Send message socsesfuly';
                    }
                }
                else if($request->action == 'favorite_film' && $user->favorite_films){
                    return 'Film notification is not functionable!';
                }
            }
        }
        return 'Nathing for senging!';
    }

    public function send_article_adding_notification(Request $request)
    {
        $last_article = Article::latest('id')->first();

        if($request->notification_category == 'outdoor'){
            $this->sending_loop('outdoor', $last_article);
        }
        else if($request->notification_category == 'indoor'){
            $this->sending_loop('indoor', $last_article);
        }
        else if($request->notification_category == 'news'){
            $this->sending_loop('news', $last_article);
        }
        else if($request->notification_category == 'ice'){
            $this->sending_loop('ice', $last_article);
        }
        else if($request->notification_category == 'techtip'){
            $this->sending_loop('techtip', $last_article);
        }
    }

    public function sending_loop($article_category, $global_article)
    {
        $notifictions = user_notification::where($article_category, '=', 1)->get();

        foreach ($notifictions as $notifiction) {
            $user = $notifiction->user;

            if($user->lang == 'ka'){
                $locale_article = $global_article->ka_article;
                $url = config('app.base_url_ssh').'/ka/' . $article_category . '/'.$global_article->url_title;
                $text = 'შეამოწმეთ თქვენი ართერთი საყვარელი საცოცი რეგიონი საქართველოში, (' . $locale_article->title . ') რათა გაიგოთ მეტი სიახლე მის შესახებ.';
                $subject = $locale_article->title;
            }
            else if($user->lang == 'ru'){
                $locale_article = $global_article->ru_article;
                $url = config('app.base_url_ssh').'/ru/' . $article_category . '/'.$global_article->url_title;
                $text = 'Посетите одно из ваших любимых мест для скалолазания в Грузии, (' . $locale_article->title . ') чтобы узнать, что нового.';
                $subject = $locale_article->title;
            }
            else{
                $locale_article = $global_article->us_article;
                $url = config('app.base_url_ssh').'/' . $article_category . '/'.$global_article->url_title;
                $text = 'Check new '. $article_category .' article, (' . $locale_article->title . ') for check what is new.';
                $subject = $locale_article->title;
            }

            UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');
        }

        foreach (Service_follower::where('service', '=', 'guid')->get() as $folewer) {

            $locale_article = $article->us_article;
            $url = config('app.base_url_ssh') . '/' . $article_category . '/' . $article->url_title;
            $text = 'Check one of your, favorite article climbing area in Georgia, (' . $locale_article->title . ') for check what is new.';
            $subject = $locale_article->title;

            UserNotifications::dispatch($url, $text, $subject, $folewer->email)->onQueue('emails');
        }
    }

    public function send_product_adding_notification(Request $request)
    {
        $product = Product::latest('id')->first();

        $notifictions = user_notification::where('add_new_product', '=', 1)->get();

        foreach ($notifictions as $notifiction) {
            $user = $notifiction->user;

            if($user->lang == 'ka'){
                $locale_product = $product->ka_product;
                $url = config('app.base_url_ssh').'/ka/product/'.$product->url_title;
                $text = 'ჩვენი ახალი პროდუქტი სპეციალურად თქვენთვის. (' . $locale_product->title . ') ეწვიეთ ჩვენ მაღაზიაში რომ იხილოთ მეტი.';
                $subject = $locale_product->title;
            }
            else if($user->lang == 'ru'){
                $locale_product = $product->ru_product;
                $url = config('app.base_url_ssh').'/ru/product/'.$product->url_title;
                $text = 'Наш новый продукт специально для вас. (' . $locale_product->title . ') Посетите наш магазин, чтобы увидеть больше.';
                $subject = $locale_product->title;
            }
            else{
                $locale_product = $product->us_product;
                $url = config('app.base_url_ssh').'/product/'.$product->url_title;
                $text = 'Our new product is specially for you. (' . $locale_product->title . ') Visit our store to see more.';
                $subject = $locale_product->title;
            }

            UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');
        }

        foreach (Service_follower::where('service', '=', 'shop')->get() as $folewer) {

            $locale_product = $product->us_product;
            $url = config('app.base_url_ssh') . '/product/' . $product->url_title;
            $text = 'Our new product is specially for you. (' . $locale_product->title . ') Visit our store to see more.';
            $subject = $locale_product->title;

            UserNotifications::dispatch($url, $text, $subject, $folewer->email)->onQueue('emails');
        }
    }

    public function send_service_adding_notification(Request $request)
    {
        $service = Service::latest('id')->first();

        $notifictions = user_notification::where('add_new_service', '=', 1)->get();

        foreach ($notifictions as $notifiction) {
            $user = $notifiction->user;

            if($user->lang == 'ka'){
                $locale_service = $service->ka_service;
                $url = config('app.app_ssh') . config('app.shop_url').'/ka/service/'.$service->url_title;
                $text = 'შეამოწმეთ თქვენი ართერთი საყვარელი საცოცი რეგიონი საქართველოში, (' . $locale_service->title . ') რათა გაიგოთ მეტი სიახლე მის შესახებ.';
                $subject = $locale_service->title;
            }
            else if($user->lang == 'ru'){
                $locale_service = $service->ru_service;
                $url = config('app.app_ssh') . config('app.shop_url').'/ru/service/'.$service->url_title;
                $text = 'Посетите одно из ваших любимых мест для скалолазания в Грузии, (' . $locale_service->title . ') чтобы узнать, что нового.';
                $subject = $locale_service->title;
            }
            else{
                $locale_service = $service->us_service;
                $url = config('app.app_ssh') . config('app.shop_url').'/service/'.$service->url_title;
                $text = 'Check new service, (' . $locale_service->title . ') for check what is new.';
                $subject = $locale_service->title;
            }

            UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');
        }

        foreach (Service_follower::where('service', '=', 'shop')->get() as $folewer) {

            $locale_article = $article->us_article;
            $url = config('app.app_ssh') . config('app.shop_url').'/service/'.$article->url_title;
            $text = 'Check one of your, favorite article climbing area in Georgia, (' . $locale_article->title . ') for check what is new.';
            $subject = $locale_article->title;

            UserNotifications::dispatch($url, $text, $subject, $folewer->email)->onQueue('emails');
        }
    }

    public function send_sector_adding_notification(Request $request)
    {
        $last_sector = Sector::latest('id')->first();

        $notifictions = user_notification::where('add_new_service', '=', 1)->get();

        foreach ($notifictions as $notifiction) {
            $user = $notifiction->user;
            
            if($user->lang == 'ka'){
                $global_article = $last_sector->article;
                $locale_article = $global_article->ka_article;
                $url = config('app.base_url_ssh').'/ka/' . $global_article->category . '/'.$global_article->url_title;
                $text = 'საცოც რეგიონში "' . $locale_article->title . '", დაემატა ახალი სექტორი ' . $last_sector->name . '. ეწვიეთ ჩვენ საიტს რომ გაიგოთ მეტი ინფორმაცია';
                $subject = $locale_article->title;
            }
            else if($user->lang == 'ru'){
                $global_article = $last_sector->article;
                $locale_article = $global_article->ka_article;
                $url = config('app.base_url_ssh').'/ru/' . $global_article->category . '/'.$global_article->url_title;
                $text = 'В районе скалолазания "' . $locale_article->title . '", добавлен новый сектор ' . $last_sector->name . '. Посетите наш сайт для получения дополнительной информации';
                $subject = $locale_article->title;
            }
            else{
                $global_article = $last_sector->article;
                $locale_article = $global_article->ka_article;
                $url = config('app.base_url_ssh').'/' . $global_article->category . '/'.$global_article->url_title;
                $text = 'In the climbing region "' . $locale_article->title . '", new sector has been added ' . $last_sector->name . '. Visit our website for more information';
                $subject = $locale_article->title;
            }

            UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');
        }

        foreach (Service_follower::where('service', '=', 'guid')->get() as $folewer) {
            $global_article = $last_sector->article;
            $locale_article = $global_article->ka_article;
            $url = config('app.base_url_ssh').'/' . $global_article->category . '/'.$global_article->url_title;
            $text = 'In the climbing region "' . $locale_article->title . '", new sector has been added ' . $last_sector->name . '. Visit our website for more information';
            $subject = $locale_article->title;

            UserNotifications::dispatch($url, $text, $subject, $folewer->email)->onQueue('emails');
        }
    }
    

    public function send_event_adding_notification(Request $request)
    {
        $product = Event::latest('id')->first();

        $notifictions = user_notification::where('add_new_product', '=', 1)->get();

        foreach ($notifictions as $notifiction) {
            $user = $notifiction->user;

            if($user->lang == 'ka'){
                $locale_product = $product->ka_product;
                $url = config('app.base_url_ssh').'/ka/product/'.$product->url_title;
                $text = 'ჩვენი ახალი პროდუქტი სპეციალურად თქვენთვის. (' . $locale_product->title . ') ეწვიეთ ჩვენ მაღაზიაში რომ იხილოთ მეტი.';
                $subject = $locale_product->title;
            }
            else if($user->lang == 'ru'){
                $locale_product = $product->ru_product;
                $url = config('app.base_url_ssh').'/ru/product/'.$product->url_title;
                $text = 'Наш новый продукт специально для вас. (' . $locale_product->title . ') Посетите наш магазин, чтобы увидеть больше.';
                $subject = $locale_product->title;
            }
            else{
                $locale_product = $product->us_product;
                $url = config('app.base_url_ssh').'/product/'.$product->url_title;
                $text = 'Our new product is specially for you. (' . $locale_product->title . ') Visit our store to see more.';
                $subject = $locale_product->title;
            }

            UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');
        }

        foreach (Service_follower::where('service', '=', 'shop')->get() as $folewer) {

            $locale_product = $product->us_product;
            $url = config('app.base_url_ssh') . '/product/' . $product->url_title;
            $text = 'Our new product is specially for you. (' . $locale_product->title . ') Visit our store to see more.';
            $subject = $locale_product->title;

            UserNotifications::dispatch($url, $text, $subject, $folewer->email)->onQueue('emails');
        }
    }
}
