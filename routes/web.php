<?php

use Illuminate\Support\Facades\Route;

// Route::group(['prefix' => LocalisationService::locale(),'middleware' => 'setLocale'], function() {

    // Route::get('/redirect', 'Auth\SocialAuthFacebookController@redirectFacebook');
    // Route::get('/callback', 'Auth\SocialAuthFacebookController@facebookCallback');

    Route::domain(env('BASE_URL'))->group(function () {
        Route::group(['namespace'=>'Site'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('index');
        });
    });
    
    Route::domain('films.' . env('BASE_URL'))/*->middleware('verified')*/->group(function () {
        Route::group(['namespace'=>'Films'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('films_index');
        });
    });

    Route::domain('shop.' . env('BASE_URL'))->group(function () {
        Route::group(['namespace'=>'Shop'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('shop_index');
        });
    });

    Route::domain('forum.' . env('BASE_URL'))->group(function () {
        Route::group(['namespace'=>'Forum'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('forum_index');
        });
    });

    Route::domain('user.' . env('BASE_URL'))->group(function () {
        // Route::group(['middleware'=>'auth:sanctum'], function() {
            // Route::group(['middleware'=>['web']], function() {
                Route::group(['namespace'=>'User'], function() {
                    Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('user_index');
                });
            // });
        // });
    });

// });

Auth::routes(['verify' => true]);