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


Route::get('/following_users_list', 'Api\ServiceFollowing@get_following_users_list');

// Canvas image save route
Route::post('/save-canvas-image', 'Api\CanvasController@saveImage');

// Route::controller(ServiceFollowing::class)->prefix('get_follow')->group( function() {
//     // Editing routes moved to adminAction.php
//     Route::get('/following_users_list', 'get_following_users_list');
// });
