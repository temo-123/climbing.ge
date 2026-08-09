<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User\Admin\Training', 'middleware'=>['auth:sanctum', 'banned']], function() {
    Route::controller(TrainingController::class)->prefix('set_training')->group( function() {

    });
});
