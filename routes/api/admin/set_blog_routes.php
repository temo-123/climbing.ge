<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User\Admin\Blog'], function() {

    Route::controller(PostController::class)->prefix('set_post')->group( function() {
        Route::get('/get_editing_post/{id}', 'get_editing_post');
        Route::post('/add_post', 'add_post');
        Route::post('/edit_post/{id}', 'edit_post');
        Route::delete('/del_post/{id}', 'del_post');
    });
    
});
