<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

use LanguageDetection\Language;
use Flitt\Configuration;

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
        // every outgoing mail: no mail to banned users + embed theme images (MailGuardService)
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Mail\Events\MessageSending::class, [\App\Services\MailGuardService::class, 'handle']);

        Schema::defaultStringLength(191);
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        
        ResetPassword::createUrlUsing(function ($user, string $token) {
            // Use APP_SSH environment variable for flexibility
            $appSsh = env('APP_SSH', 'https://');
            $userPageUrl = env('USER_PAGE_URL', 'user.climbing.ge');
            return $appSsh . $userPageUrl . '/reset-password/' . $token . '/' . $user->id;
        });

        // friendly climbing.ge template instead of Laravel's default lines
        ResetPassword::toMailUsing(function ($user, string $token) {
            $url = env('APP_SSH', 'https://') . env('USER_PAGE_URL', 'user.climbing.ge') . '/reset-password/' . $token . '/' . $user->id;
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Reset your climbing.ge password 🔑')
                ->markdown('emails.auth.reset_password', [
                    'action_url' => $url,
                    'name' => $user->name ?? null,
                    'expire' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60),
                ]);
        });

        Configuration::setMerchantId(config('flitt.merchant_id'));
        Configuration::setSecretKey(config('flitt.secret_key'));
        Configuration::setApiVersion(config('flitt.api_version'));
    }
}
