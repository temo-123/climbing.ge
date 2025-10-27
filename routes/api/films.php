<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\Films'], function() {
    /*
    *   Films routes
    */
    Route::apiResource('/films', 'FilmsController');
    Route::get('/film/get_films/{locale}', 'FilmsController@get_films');
    Route::get('/film/get_same_films/{category_id}/{film_id}/{locale}', 'FilmsController@get_same_films');
    Route::get('/film/get_film/{locale}/{url_title}', 'FilmsController@get_film');
    Route::get('/film/get_films_categories/{locale}', 'FilmsController@get_films_categories');
    Route::get('/film/films_search/{locale}', 'FilmsController@films_search');
    Route::get('/film/top_films/{top_film_type}/{locale}', 'FilmsController@get_films_top');

    Route::apiResource('film_tags', 'FilmTagsController');
    // Route::apiResource('film_categories', 'FilmCategoryController');

    Route::get('/film/get_faworite_film_list', 'FilmsController@get_faworite_film_list');
});
