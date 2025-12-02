<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User\Admin\Blog'], function() {
    /*
    *   Blog posts routes
    */
    // Route::controller(PostController::class)->prefix('post')->group( function() {
    //     Route::get('/get_all_posts_and_news_for_blog/{locale}', 'get_all_posts_and_news_for_blog');
    //     Route::get('/get_post/{url_title}', 'get_post');
    //     Route::get('/get_news/{url_title}', 'get_news');
    // });


    // Route::controller(PostController::class)->prefix('post')->group( function() {
    //     Route::get('/get_posts', 'get_posts');
    //     Route::get('/get_editing_post/{id}', 'get_editing_post');
    //     Route::get('/get_activ_post/{id}', 'get_activ_post');
    // });

    Route::controller(PostController::class)->prefix('set_post')->group( function() {
        // Route::get('/get_posts', 'get_posts');
        Route::get('/get_editing_post/{id}', 'get_editing_post');
        // Route::get('/get_activ_post/{id}', 'get_activ_post');
        Route::post('/add_post', 'add_post');
        Route::post('/edit_post/{id}', 'edit_post');
        Route::delete('/del_post/{id}', 'del_post');
    });
});
