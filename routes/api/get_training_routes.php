<?php

use Illuminate\Support\Facades\Route;

// Public routes - no authentication required for viewing content
Route::group(['namespace'=>'Api\Training'], function() {
    Route::controller(TrainingController::class)->prefix('get_training')->group( function() {
        Route::get('/get_all_trainings', 'get_all_trainings');
        Route::get('/get_training_data/{id}', 'get_training_data');
    });

    Route::controller(TrainingPlanController::class)->prefix('get_training_plan')->group( function() {
        Route::get('/get_all_plans', 'get_all_plans');
        Route::get('/get_plan_data/{id}', 'get_plan_data');
    });
});
