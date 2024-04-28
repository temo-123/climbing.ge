<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use App\Mail\UserNewArticleMessage;
use App\Notifications\user_notification\ArticleEventNotification;
use App\Notifications\user_notification\ProductEventNotification;
use App\Notifications\user_notification\ServiceEventNotification;
use App\Notifications\user_notification\SectorEventNotification;

use App\Models\Guide\Article;
use App\Models\Guide\Sector;
use App\Models\Guide\Event;
use App\Models\Shop\Service;
use App\Models\Shop\Product;
use App\Models\User\Service_follower;
use App\Models\User\user_notification;

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
}
