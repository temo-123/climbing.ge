<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\Blog'], function() {

    Route::controller(PostController::class)->prefix('get_post')->group( function() {
        Route::get('/get_all_posts_and_news_for_blog/{locale}', 'get_all_posts_and_news_for_blog');

        Route::get('/get_post/{url_title}', 'get_post');
        Route::get('/get_news/{url_title}', 'get_news');

        Route::get('/get_all_posts', 'get_all_posts');
    });
    
});
