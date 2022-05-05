<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => LocalisationService::locale(),'middleware' => 'setLocale'], function() {

    // Route::get('/redirect', 'Auth\SocialAuthFacebookController@redirectFacebook');
    // Route::get('/callback', 'Auth\SocialAuthFacebookController@facebookCallback');

    Route::domain(config('app.url'))->group(function () {
        Route::group(['namespace'=>'Site'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('index');
        });
    });
    
    Route::domain('films.' . config('app.url'))/*->middleware('verified')*/->group(function () {
        Route::group(['namespace'=>'Films'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('films_index');
        });
    });

    Route::domain('shop.' . config('app.url'))->group(function () {
        Route::group(['namespace'=>'Shop'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('shop_index');
        });
    });

    Route::domain('forum.' . config('app.url'))->group(function () {
        Route::group(['namespace'=>'Forum'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('forum_index');
        });
    });

    Route::domain('user.' . config('app.url'))->group(function () {
        // Route::group(['middleware'=>'auth:sanctum'], function() {
            // Route::group(['middleware'=>['web']], function() {
                Route::group(['namespace'=>'User'], function() {
                    Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('user_index');
                });
            // });
        // });
    });

});

Auth::routes(['verify' => true]);