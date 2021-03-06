<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use View;
use App\User;
use App\Models\Site;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $users = User::all();
        View::share('users_roles', $users);

        $about_us = Site::get();

        $about_us_array = array();
        
        foreach ($about_us as $about) {
            array_push($about_us_array, $about);
        }

        $about = $about_us_array[0];
        View::share('site', $about);

        $locale = request()->segment(1, '');
        if($locale == "ru"){
            $page_locale = "ru";
            View::share('page_locale', $page_locale);
        }
        elseif ($locale == "ka") {
            $page_locale = "ka";
            View::share('page_locale', $page_locale);
        } else {
            $page_locale = "us";
            View::share('page_locale', $page_locale);
        }
    }
}
