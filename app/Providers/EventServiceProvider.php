<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \App\Models\Guide\Sector::observe(\App\Observers\SectorObserver::class);
        \App\Models\Guide\Mount::observe(\App\Observers\MountObserver::class);
        \App\Models\Summit\Summit::observe(\App\Observers\SummitObserver::class);
        \App\Models\Films\Film::observe(\App\Observers\FilmObserver::class);

        \App\Models\Guide\Article_comment_user::observe(\App\Observers\ArticleCommentUserObserver::class);
        \App\Models\Summit\SummitAscentUser::observe(\App\Observers\SummitAscentUserObserver::class);
        \App\Models\Guide\Sport_route_review::observe(\App\Observers\SportRouteReviewObserver::class);
        \App\Models\Guide\Mtp_review::observe(\App\Observers\MtpReviewObserver::class);
        \App\Models\User\UserFollow::observe(\App\Observers\UserFollowObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
    }
}
