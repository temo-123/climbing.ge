<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

use LanguageDetection\Language;

// use View;
// use URL;
// use App\User;
// use App\Models\Site;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\LanguageDetection\Language::class, function ($app) {
            return new \LanguageDetection\Language(['en', 'ka']);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        
        ResetPassword::createUrlUsing(function ($user, string $token) {
            // Use APP_SSH environment variable for flexibility
            $appSsh = env('APP_SSH', 'https://');
            $userPageUrl = env('USER_PAGE_URL', 'user.climbing.ge');
            return $appSsh . $userPageUrl . '/reset-password/' . $token . '/' . $user->id;
        });
    }
}
