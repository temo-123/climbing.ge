<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Summit\SummitPublicController;

Route::prefix('summit')->group(function () {
    Route::controller(SummitPublicController::class)->group(function () {
        Route::get('/list', 'index');
        Route::get('/list_by_mount/{lang}', 'list_by_mount');
        Route::get('/list_filtered/{mount_id}', 'list_filtered_by_mount');
        Route::get('/show/{url_title}', 'show');
        Route::get('/routes/{id}', 'get_routes_for_summit');
        Route::get('/ascents/{url_title}', 'get_ascents');
        Route::post('/ascent/{summit_id}', 'submit_ascent');
        Route::get('/my_ascents', 'my_ascents')->middleware('auth:sanctum');
    });
});
