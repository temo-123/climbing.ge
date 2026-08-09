<?php

use Illuminate\Support\Facades\Route;

// Public routes - no authentication required for viewing content
Route::group(['namespace'=>'Api\Training'], function() {
    Route::controller(TrainingController::class)->prefix('get_training')->group( function() {
        //
    });
});
