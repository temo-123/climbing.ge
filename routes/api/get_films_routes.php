<?php

use Illuminate\Support\Facades\Route;

// Public routes - no authentication required for viewing content
Route::group(['namespace'=>'Api\Films'], function() {
    /*
    *   Films routes
    */
    Route::apiResource('/get_films', 'FilmsController')->only(['index']);
    Route::get('/get_films/get_films/{locale}', 'FilmsController@get_films');
    Route::get('/get_films/get_same_films/{category_id}/{film_id}/{locale}', 'FilmsController@get_same_films');
    Route::get('/get_films/get_film/{locale}/{url_title}', 'FilmsController@get_film');
    Route::get('/get_films/get_films_categories/{locale}', 'FilmsController@get_films_categories');
    Route::get('/get_films/films_search/{locale}', 'FilmsController@films_search');
    Route::get('/get_films/top_films/{top_film_type}/{locale}', 'FilmsController@get_films_top');

    Route::apiResource('film_tags', 'FilmTagsController')->only(['index', 'show']);
    // Route::apiResource('film_categories', 'FilmCategoryController');

    Route::get('/get_films/get_faworite_film_list', 'FilmsController@get_faworite_film_list')->middleware('auth:sanctum');
});
