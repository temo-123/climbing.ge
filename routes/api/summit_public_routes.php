<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Summit\SummitPublicController;

Route::prefix('summit')->group(function () {
    Route::controller(SummitPublicController::class)->group(function () {
        Route::get('/list', 'index');
        Route::get('/show/{url_title}', 'show');
        Route::get('/routes/{id}', 'get_routes_for_summit');
        Route::post('/ascent/{summit_id}', 'submit_ascent');
    });
});
