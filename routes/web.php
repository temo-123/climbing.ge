<?php

use Illuminate\Support\Facades\Route;

// Error-page helper: serves the SPA shell with the correct HTTP status code.
// Must be registered BEFORE each domain's catch-all so these specific paths win.
if (!function_exists('spaError')) {
    function spaError(string $view, int $status): \Illuminate\Http\Response
    {
        return response(view($view), $status);
    }
}

    Route::domain(env('SITE_URL'))->group(function () {
        Route::get('/404',    fn() => spaError('site.index', 404));
        Route::get('/403',    fn() => spaError('site.index', 403));
        Route::get('/500',    fn() => spaError('site.index', 500));
        Route::get('/banned', fn() => spaError('site.index', 403));

        Route::group(['namespace'=>'Site'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('index');
        });
    });

    Route::domain(env('FILMS_URL'))->group(function () {
        Route::get('/404',    fn() => spaError('films.index', 404));
        Route::get('/403',    fn() => spaError('films.index', 403));
        Route::get('/500',    fn() => spaError('films.index', 500));

        Route::group(['namespace'=>'Films'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('films_index');
        });
    });

    Route::domain(env('SHOP_URL'))->group(function () {
        Route::get('/404',    fn() => spaError('shop.index', 404));
        Route::get('/403',    fn() => spaError('shop.index', 403));
        Route::get('/500',    fn() => spaError('shop.index', 500));

        Route::group(['namespace'=>'Shop'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('shop_index');
        });
    });

    Route::domain(env('BLOG_URL'))->group(function () {
        Route::get('/404',    fn() => spaError('blog.index', 404));
        Route::get('/403',    fn() => spaError('blog.index', 403));
        Route::get('/500',    fn() => spaError('blog.index', 500));

        Route::group(['namespace'=>'Blog'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('blog_index');
        });
    });

    Route::domain(env('SUMMIT_URL'))->group(function () {
        Route::get('/404',    fn() => spaError('summit.index', 404));
        Route::get('/403',    fn() => spaError('summit.index', 403));
        Route::get('/500',    fn() => spaError('summit.index', 500));

        Route::group(['namespace'=>'Summit'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('summit_index');
        });
    });

    Route::domain(env('USER_PAGE_URL'))->group(function () {
        Route::get('/404',    fn() => spaError('user.home', 404));
        Route::get('/403',    fn() => spaError('user.home', 403));
        Route::get('/500',    fn() => spaError('user.home', 500));
        Route::get('/banned', fn() => spaError('user.home', 403));

        Route::group(['namespace'=>'User'], function() {
            Route::get('/{any}', 'IndexController@index')->where('any', '(.*)')->name('user_index');
        });
    });

Auth::routes(['verify' => true]);
