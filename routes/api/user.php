<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User'], function() {
    /*
    *   Login verify routes
    */
    Route::group(['middleware'=>'auth:sanctum'], function() {
        // Route::get('email/verify/{hash}', 'VerificationController@verify')->name('verification.verify');
        Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');
        Route::get('auth_user', 'AuthenticationController@user')->name('auth_user');
    });

    Route::controller(SocialLinkController::class)->prefix('site_social_links')->group( function() {
        Route::get('/get_site_social_links', 'get_site_social_links');
        // Editing routes moved to adminAction.php
    });

    /*
    *   Users routes
    */
    Route::apiResource('/user_site', 'UserSiteController');

    Route::controller(UsersController::class)->prefix('user')->group( function() {
        Route::get('/get_auth_user_permissions', 'get_auth_user_permissions');
        Route::get('/get_auth_user_data', 'get_auth_user_data');
        Route::get('/get_user_data/{user_id}', 'get_user_data');
        Route::get('/get_all_users', 'get_all_users');

        Route::get('/get_worker_users', 'get_worker_users');

        Route::get('/post_user/{user_id}', 'get_post_user');

        Route::controller(UserNotificationsController::class)->prefix('notifications')->group( function() {
            // Editing routes moved to adminAction.php
        });


        // Route::controller(TeamMemberController::class)->prefix('team')->group( function() {
        //     Route::get('/get_member_status/{id}', 'get_member_status');
        //     // Editing routes moved to adminAction.php
        //     Route::get('/get_team_members', 'get_team_members');
        //     Route::get('/get_team_members/{id}', 'get_team_members');
        // });
    });


    Route::controller(UserOptionController::class)->prefix('options')->group( function() {
        // Route::get('/get_user_data', 'get_user_data');
        Route::get('/get_selected_user_data/{user_id}', 'get_selected_user_data');
        Route::get('/get_user_notification_data', 'get_user_notification_data');
        Route::post('/user_info_update/{user_id}', 'user_info_update');
        Route::post('/update_user_notification_data', 'update_user_notification_data');
    // });
    // Route::controller(UserOptionController::class)->prefix('options')->group( function() {
        // Route::get('/get_user_data', 'get_user_data');
        Route::get('/get_selected_user_data/{user_id}', 'get_selected_user_data');
        Route::get('/get_user_notification_data', 'get_user_notification_data');
    });

    /*
    *   Users option routes
    */
    // Route::apiResource('/users', 'UsersController');
    // Route::get('/post_user/{user_id}', 'UsersController@get_post_user');
    // Route::post('user_image_update/{user_id}', 'UsersController@user_image_update');




    /*
    *   Blog posts routes
    */
    // Route::controller(PostController::class)->prefix('post')->group( function() {
    //     Route::get('/get_posts', 'get_posts');
    //     Route::get('/get_editing_post/{id}', 'get_editing_post');
    //     Route::get('/get_activ_post/{id}', 'get_activ_post');
    // });

    /*
    *   User adresses
    */
    // Route::controller(UserAdresesController::class)->group( function() {
    //     Route::get('/get_user_adreses', 'get_user_adreses');
    //     Route::post('/get_editing_adres/{adres_id}', 'get_editing_adres');
    //     Route::get('/get_activ_adres/{adres_id}', 'get_activ_adres');
    // });
    Route::controller(UserAdresesController::class)->group( function() {
        Route::get('/get_user_adreses', 'get_user_adreses');
        Route::post('/get_editing_adres/{adres_id}', 'get_editing_adres');
        Route::get('/get_activ_adres/{adres_id}', 'get_activ_adres');
        Route::post('/add_user_adreses', 'add_user_adreses');
        Route::post('/edit_adres/{adres_id}', 'edit_adres');
        Route::delete('/del_user_adreses/{adres_id}', 'del_user_adreses');
    });
});
