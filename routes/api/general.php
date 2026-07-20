<?php

use Illuminate\Support\Facades\Route;

/*
*   Guidbook and Ploducts Search routes
*/

/*
*   Login verify routes
*/
Route::middleware(['auth:sanctum', 'banned'])->group(function () {
    Route::post('/upload/editor-image', 'Api\EditorImageController@upload');
});

/*
*   Editing routes
*/
// Legacy search endpoints (backward compatibility)
Route::post('/productSearch/{query_request}', 'Api\SearchController@productSearch');
Route::post('/articleSearch/{query_request}', 'Api\SearchController@articleSearch');
Route::post('/filmSearch/{query_request}', 'Api\SearchController@filmSearch');

// Unified search endpoints with locale + fuzzy "maybe you mean" support
Route::post('/search/suggest', 'Api\SearchController@suggest');
Route::post('/guide/search', 'Api\SearchController@guideSearch');
Route::post('/shop/search', 'Api\SearchController@shopSearch');
Route::post('/blog/search', 'Api\SearchController@blogSearch');
Route::post('/summit/search', 'Api\SearchController@summitSearch');

// Canvas image save route
Route::post('/save-canvas-image', 'Api\CanvasController@saveImage');

// Payment gateway availability (whether TBC credentials are set in .env)
Route::get('/payment/status', 'Api\PaymentStatusController@status');

// Public routes - no authentication required for viewing content
Route::group(['namespace'=>'Api'], function() {

    Route::controller(ServiceFollowing::class)->prefix('set_follow')->group( function() {
        Route::post('/{service_id}', 'follow');
        Route::delete('/del_follower/{id}', 'del_follower');
    });

    Route::controller(SocialLinkController::class)->prefix('set_site_social_links')->group( function() {
        Route::post('/add_site_social_links', 'add_site_social_links');
        Route::delete('/del_site_social_links/{link_id}', 'del_site_social_links');
    });

    Route::controller(SocialLinkController::class)->prefix('get_site_social_links')->group( function() {
        Route::get('/get_site_social_links', 'get_site_social_links');
    });

    Route::controller(SiteDataController::class)->prefix('get_site_data')->group( function() {

        Route::get('/get_site_global_data', 'get_site_global_data');
        Route::get('/get_site_locale_data/{lang}', 'get_site_locale_data');

        Route::get('get_site_locale_data_for_site/{leng}', 'get_site_locale_data_for_site');
    });

    Route::controller(SiteDataController::class)->prefix('set_site_data')->group( function() {
        Route::post('/edit_site_global_data', 'edit_site_global_data');

        Route::post('/add_site_local_data', 'add_site_local_data');
        Route::post('/edit_site_local_data/{id}/{leng}', 'edit_site_local_data');

        Route::get('/fix_article_bugs', 'fix_article_bugs');
        Route::get('/site_data_counts', 'site_data_counts');
        Route::get('/article_errors/{locale}', 'get_article_errors');
    });
});