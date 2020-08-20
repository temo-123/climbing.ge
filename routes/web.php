<?php

use Illuminate\Support\Facades\Route;

Route::domain(env('APP_URL'))->group(function () {
    Route::group(['namespace'=>'Site'], function() {
        Route::get('/', 'IndexController@index')->name('index');
        Route::get('/about_us', 'AboutController@index')->name('about_us_page');

        Route::get('/news/{title}', ['uses'=>'NewsController@news_page', 'as'=>'news_page']);
        Route::get('/security/{title}', ['uses'=>'SecurityController@security_page', 'as'=>'security_page']);

        Route::group(['prefix'=>'events'], function() {
            Route::get('/{title}', ['uses'=>'EventsController@events_page', 'as'=>'events_page']);
        });

        Route::get('/outdoor', 'OutdoorController@outdoor_list')->name('outdoor_list');
        Route::get('/outdoor/{title}', ['uses'=>'OutdoorController@outdoor_page', 'as'=>'outdoor_page']);

        Route::get('/indoor', 'IndoorController@indoor_list')->name('indoor_list');
        Route::get('/indoor/{title}', ['uses'=>'IndoorController@indoor_page', 'as'=>'indoor_page']);

        Route::get('/mountaineering', 'MountController@mount_list')->name('mount_list');
        Route::get('/mountaineering/{title}', ['uses'=>'MountController@mount_page', 'as'=>'mount_page']);

        Route::get('/ice_and_mix', 'IceController@ice_list')->name('ice_list');
        Route::get('/ice/{title}', ['uses'=>'IceController@ice_page', 'as'=>'ice_page']);

        Route::get('/other', 'OtherActivityController@other_list')->name('other_list');
        Route::get('/other/{title}', ['uses'=>'OtherActivityOtherController@other_page', 'as'=>'other_page']);

        Route::get('/partniors/{title}', ['uses'=>'PartnersController@partners_page', 'as'=>'partners_page']);

        Route::get('/sitemap.xml', 'SitemapController@sitemap_xml');
        Route::get('/sitemap', 'SitemapController@sitemap');
    
        Route::group(['namespace'=>'App'], function() {
            Route::post('/search', 'SearchController@search');
            Route::get('members/search', 'SearchController@index');

            Route::post('/send_message', 'MessageController@send');

            Route::any('/add_comment', 'CommentController@add_comment');
            Route::get('/get_comments', "CommentController@get_comments");

            Route::get('/sitemap.xml', 'SitemapController@sitemap_xml');
            Route::get('/sitemap', 'SitemapController@sitemap');
        });
    });
});

Route::domain('user.'.env('APP_URL'))->group(function () {
    Route::group(['namespace'=>'User'], function() {
        Route::get('/', 'IndexController@index')->name('user_index');
    });
});

Route::domain('shop.'.env('APP_URL'))->group(function () {
    Route::group(['namespace'=>'Shop'], function() {
        Route::get('/', 'IndexController@index')->name('shop_index');

        // Route::get('/', 'ShopController@shop_list')->name('shop_list');
        Route::get('/product/{title}', ['uses'=>'ShopController@shop_page', 'as'=>'shop_page']);
        Route::get('/seller/{id}', ['uses'=>'ShopController@seller_page', 'as'=>'seller_page']);

        Route::get('/favorite_product/{product_id}/{actions}', ['uses'=>'PrioritiesController@favorite_product', 'as'=>'favorite_product']);

        Route::get('/sitemap.xml', 'SitemapController@sitemap_xml');
        Route::get('/sitemap', 'SitemapController@sitemap');
    });
});

Auth::routes();