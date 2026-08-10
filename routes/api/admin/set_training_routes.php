<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User\Admin\Training', 'middleware'=>['auth:sanctum', 'banned']], function() {
    Route::controller(TrainingController::class)->prefix('set_training')->group( function() {
        Route::get('/get_all_trainings', 'get_all_trainings');
        Route::get('/get_training_data/{id}', 'get_training_data');
        Route::get('/search_products', 'search_products');
        Route::post('/create_training', 'create_training');
        Route::post('/update_training/{id}', 'update_training');
        Route::delete('/del_training/{id}', 'del_training');
        Route::post('/bulk_delete', 'bulk_delete');
        Route::post('/bulk_publish', 'bulk_publish');
        Route::post('/bulk_unpublish', 'bulk_unpublish');
    });

    Route::controller(TrainingPlanController::class)->prefix('set_training_plan')->group( function() {
        Route::get('/get_all_plans', 'get_all_plans');
        Route::get('/get_plan_data/{id}', 'get_plan_data');
        Route::post('/create_plan', 'create_plan');
        Route::post('/update_plan/{id}', 'update_plan');
        Route::delete('/del_plan/{id}', 'del_plan');
        Route::post('/bulk_delete', 'bulk_delete');
        Route::post('/bulk_publish', 'bulk_publish');
        Route::post('/bulk_unpublish', 'bulk_unpublish');
    });
});
