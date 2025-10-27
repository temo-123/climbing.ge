<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\Meil'], function() {
    /*
    *   Mails routes
    */
    Route::apiResource('/message', 'MessageController');
    Route::post('/FollowingNotification', 'FollowingNotificationController@send_notification');
});
