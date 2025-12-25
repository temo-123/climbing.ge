<?php

use Illuminate\Support\Facades\Route;

/*
*   Guidbook and Ploducts Search routes
*/

/*
*   Login verify routes
*/
Route::controller('Api\CKEditorController')->prefix('get_ckeditor')->group( function() {
    // Route::get('', 'index');
    Route::post('/upload', 'upload');
});

/*
*   Editing routes
*/
Route::post('/productSearch/{query_request}', 'Api\SearchController@productSearch');
Route::post('/articleSearch/{query_request}', 'Api\SearchController@articleSearch');
Route::post('/filmSearch/{query_request}', 'Api\SearchController@filmSearch');

// Canvas image save route
Route::post('/save-canvas-image', 'Api\CanvasController@saveImage');

Route::controller(Api\ServiceFollowing::class)->prefix('get_follow')->group( function() {
    // Editing routes moved to adminAction.php
    Route::get('/following_users_list', 'get_following_users_list');
    // Route::delete('/del_follower/{id}', 'del_follower');
});

    Route::controller(Api\ServiceFollowing::class)->prefix('set_follow')->group( function() {
        Route::post('/{service_id}', 'follow');
        Route::delete('/del_follower/{id}', 'del_follower');
        // Route::get('/following_users_list', 'get_following_users_list');
    });

    /*
    *   Social links routes
    */
    // Route::controller(Api\SiteDataController::class)->prefix('get_site_social_links')->group( function() {
        // Route::get('/get_site_social_links', 'get_site_social_links');
    // });

    // Route::controller(Api\SiteDataController::class)->prefix('get_site_social_links')->group( function() {
        // Route::post('/add_site_social_links', 'add_site_social_links');
        // Route::delete('/del_site_social_links/{id}', 'del_site_social_links');
        // Route::get('/get_site_social_links', 'get_site_social_links');
    // });


    Route::controller(Api\SocialLinkController::class)->prefix('set_site_social_links')->group( function() {
        Route::post('/add_site_social_links', 'add_site_social_links');
        Route::delete('/del_site_social_links/{link_id}', 'del_site_social_links');
    });

    Route::controller(Api\SocialLinkController::class)->prefix('get_site_social_links')->group( function() {
        Route::get('/get_site_social_links', 'get_site_social_links');
        // Editing routes moved to adminAction.php
    });

    /*
    *   Site data routes
    */
    Route::controller(Api\SiteDataController::class)->prefix('get_site_data')->group( function() {
        // Route::apiResource('/', 'Api\SiteDataController');

        // Route::apiResource('/site_social_links', 'SocialLinkController');

        // Route::get('/get_site_locale_data/{locale}', 'get_site_locale_data');
        // Route::get('/get_locale_site_data/{locale}', 'get_locale_site_data');

        Route::get('/get_site_global_data', 'get_site_global_data');
        // Route::get('/get_site_ka_data', 'get_site_ka_data');
        // Route::get('/get_site_ru_data', 'get_site_ru_data');
        // Route::get('/get_site_us_data', 'get_site_us_data');
        Route::get('/get_site_locale_data/{lang}', 'get_site_locale_data');

        // Editing routes moved to adminAction.php
    });



    Route::controller(Api\SiteDataController::class)->prefix('set_site_data')->group( function() {
        // Route::post('/edit_site_data', 'edit_site_data');
        Route::post('/edit_site_global_data', 'edit_site_global_data');

        Route::post('/add_site_local_data', 'add_site_local_data');
        Route::post('/edit_site_local_data/{id}/{leng}', 'edit_site_local_data');

        // Route::post('/edit_site_ka_data', 'edit_site_ka_data');
        // Route::post('/edit_site_ru_data', 'edit_site_ru_data');
        // Route::post('/edit_site_us_data', 'edit_site_us_data');

        Route::get('/fix_article_bugs', 'fix_article_bugs');
        Route::get('/site_data_counts', 'site_data_counts');
    });
