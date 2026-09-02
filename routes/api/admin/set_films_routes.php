<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User\Admin\Films', 'middleware'=>['auth:sanctum', 'banned']], function() {
    /*
    *   Films routes
    */
    Route::apiResource('/set_films', 'FilmsController')->only(['index']);
    Route::get('/set_films/get_films/{locale}', 'FilmsController@get_films');
    Route::get('/set_films/get_same_films/{category_id}/{film_id}/{locale}', 'FilmsController@get_same_films');
    Route::get('/set_films/get_film/{locale}/{url_title}', 'FilmsController@get_film');
    Route::get('/set_films/get_films_categories/{locale}', 'FilmsController@get_films_categories');
    Route::get('/set_films/films_search/{locale}', 'FilmsController@films_search');
    Route::get('/set_films/top_films/{top_film_type}/{locale}', 'FilmsController@get_films_top');

    Route::apiResource('film_tags', 'FilmTagsController');
    // Route::apiResource('film_categories', 'FilmCategoryController');

    Route::get('/set_films/get_faworite_film_list', 'FilmsController@get_faworite_film_list');

    /*
    *   Editing routes
    */
    Route::post('/set_films/add_to_faworite', 'FilmsController@add_to_faworite');
    Route::delete('/set_films/del_from_faworite/{film_id}', 'FilmsController@del_from_faworite');
});
