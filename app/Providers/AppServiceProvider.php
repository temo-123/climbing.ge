<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use View;
use URL;
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
        $registrar = new \App\Routing\ResourceRegistrar($this->app['router']);

        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($registrar) {
            return $registrar;
        });

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

        $this->get_page_locale();
        $url_afret_locale = $this->url_after_locale();
        // dd($url_afret_locale);
        View::share('url_afret_locale', $url_afret_locale);

        $url_curent = $this->get_locale_url();
        
    }

    public function get_locale_url()
    {
        return URL::current();
    }

    public function get_page_locale()
    {
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

    public function url_after_locale()
    {
        $current_uri = request()->segments();
        if(count($current_uri) > 0 ){
            if($current_uri[0] == "ka" || $current_uri[0] == "ru"){
                unset($current_uri[0]);
                $url_afret_locale = implode("/", $current_uri);
            }
            else{
                $url_afret_locale = implode("/", $current_uri);
            }
        }
        else{
            $url_afret_locale = '';
        }
        return $url_afret_locale;
        // dd($url_afret_locale);
    }
}
