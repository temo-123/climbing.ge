<?php

namespace App\Http\Controllers\Api\User\Admin\User;

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
use App\Models\User;

use App\Services\PermissionService;
use App\Services\NotificationDispatchService;

class UserNotificationsController extends Controller
{
    /**
     * Manual, admin-triggered "notify subscribers now" action. Routed through
     * NotificationDispatchService::sendOnce() (the same dispatch path used by
     * the automatic draft->published / content-updated triggers) so every
     * send here is logged to ContentNotificationLog and shows up in the
     * Notification Analytics page instead of being invisible to it.
     * $bypassDedup=true because this is a deliberate one-off admin action —
     * it should always go out, even to users already notified once before.
     */
    public function send_user_favorites_notification(Request $request)
    {
        $auth = PermissionService::authorize('user_notification', 'add');
        if ($auth) return $auth;

        $sent = 0;
        $subject = 'Notification from climbing.ge';

        if ($request->action === 'special_articles') {
            $global_article = Article::where('category', '=', 'special')->first();
            if (!$global_article) {
                return response()->json(['sent' => 0, 'message' => 'No special article found.']);
            }

            $locale_article = $global_article->global_article_us;
            $url = config('app.base_url_ssh') . '/special_article/' . $global_article->url_title;
            $text = 'Important information from climbing.ge! ' . $locale_article->title . ' Check this article as soon as possible.';

            foreach (User::get() as $user) {
                if (NotificationDispatchService::sendOnce($user, $global_article, 'new_content', $url, 'Important notification from climbing.ge', $text, true)) {
                    $sent++;
                }
            }

            return response()->json(['sent' => $sent]);
        }

        $users_with_preference = NotificationDispatchService::usersWithPreference($request->action);

        foreach ($users_with_preference as $user) {
            if ($request->action === 'interested_event') {
                $global_event = null;

                if ($request->event_notification_type === 'select_event' && $user->interested_events) {
                    $interested_event = $user->interested_events->where('event_id', '=', $request->id)->first();
                    $global_event = $interested_event->event ?? null;
                } elseif ($request->event_notification_type === 'next') {
                    $global_event = Event::orderBy('start_data')->first();
                }

                if ($global_event) {
                    $locale_event = $global_event->us_event;
                    $url = config('app.base_url_ssh') . '/event/' . $global_event->url_title;
                    $text = 'Check one of your favorite event climbing areas in Georgia, (' . $locale_event->title . ') to see what is new.';

                    if (NotificationDispatchService::sendOnce($user, $global_event, 'favorite_publish', $url, $subject, $text, true)) {
                        $sent++;
                    }
                }
            } elseif ($request->action === 'favorite_product' && $user->favorite_products) {
                $favorite_product = $user->favorite_products->where('product_id', '=', $request->id)->first();
                if ($favorite_product) {
                    $global_product = $favorite_product->product;
                    $locale_product = $global_product->us_product;
                    $url = 'https://' . config('app.shop_url') . '/product/' . $global_product->url_title;
                    $text = 'Check one of your favorite products on climbing.ge, (' . $locale_product->title . ') to see what is new.';

                    if (NotificationDispatchService::sendOnce($user, $global_product, 'favorite_publish', $url, $subject, $text, true)) {
                        $sent++;
                    }
                }
            } elseif ($request->action === 'favorite_outdoor' && $user->favorite_outdoors) {
                $favorite_outdoor = $user->favorite_outdoors->where('article_id', '=', $request->id)->first();
                if ($favorite_outdoor) {
                    $global_outdoor = $favorite_outdoor->outdoor_area;
                    $locale_outdoor = $global_outdoor->global_article_us;
                    $url = config('app.base_url_ssh') . '/outdoor/' . $global_outdoor->url_title;
                    $text = 'Check one of your favorite outdoor climbing areas in Georgia, (' . $locale_outdoor->title . ') to see what is new.';

                    if (NotificationDispatchService::sendOnce($user, $global_outdoor, 'favorite_publish', $url, $subject, $text, true)) {
                        $sent++;
                    }
                }
            } elseif ($request->action === 'news') {
                $last_news = Article::where('category', '=', 'news')->latest('id')->first();
                if ($last_news) {
                    $locale_news = $last_news->global_article_us;
                    $url = config('app.base_url_ssh') . '/news/' . $last_news->url_title;
                    $text = 'News from climbing.ge: ' . $locale_news->title . '. Check what is new.';

                    if (NotificationDispatchService::sendOnce($user, $last_news, 'new_content', $url, $subject, $text, true)) {
                        $sent++;
                    }
                }
            } elseif ($request->action === 'favorite_film' && $user->favorite_films) {
                $favorite_film = $user->favorite_films->where('film_id', '=', $request->id)->first();
                if ($favorite_film) {
                    $global_film = $favorite_film->film;
                    $locale_film = $global_film->us_film;
                    $url = 'https://' . config('app.films_url') . '/film/' . $global_film->url_title;
                    $text = 'Check one of your favorite films on climbing.ge, (' . $locale_film->title . ') to see what is new.';

                    if (NotificationDispatchService::sendOnce($user, $global_film, 'favorite_publish', $url, $subject, $text, true)) {
                        $sent++;
                    }
                }
            }
        }

        return response()->json(['sent' => $sent]);
    }
}
