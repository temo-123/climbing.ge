<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\Admin\Summit\SummitController;

Route::group(['middleware' => ['auth:sanctum', 'banned']], function () {

    Route::prefix('set_summit')->group(function () {
        Route::controller(SummitController::class)->group(function () {
            Route::post('/store', 'store');
            Route::post('/update/{id}', 'update');
            Route::delete('/destroy/{id}', 'destroy');
            Route::post('/save_qr/{id}', 'save_qr');
            Route::post('/add_mount_route_relation', 'add_mount_route_relation');
            Route::delete('/remove_mount_route_relation/{id}', 'remove_mount_route_relation');
        });
    });

    Route::prefix('get_summit_admin')->group(function () {
        Route::controller(SummitController::class)->group(function () {
            Route::get('/index', 'index');
            Route::get('/get_regions', 'get_regions');
            Route::get('/get_mount_routes', 'get_mount_routes');
            Route::get('/get_summit_mount_routes/{summit_id}', 'get_summit_mount_routes');
            Route::get('/get_ascents/{id}', 'get_ascents');
            Route::get('/get_all_ascents', 'get_all_ascents');
        });
    });

});
