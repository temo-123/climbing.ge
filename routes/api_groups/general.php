<?php

use Illuminate\Support\Facades\Route;

/*
*   Guidbook and Ploducts Search routes
*/
Route::post('/productSearch/{query_request}', 'Api\SearchController@productSearch');
Route::post('/articleSearch/{query_request}', 'Api\SearchController@articleSearch');
Route::post('/filmSearch/{query_request}', 'Api\SearchController@filmSearch');

/*
*   Login verify routes
*/
Route::controller('Api\CKEditorController')->prefix('ckeditor')->group( function() {
    // Route::get('', 'index');
    Route::post('/upload', 'upload');
});
