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


        Route::controller(TeamMemberController::class)->prefix('team')->group( function() {
            Route::get('/get_member_status/{id}', 'get_member_status');
            // Editing routes moved to adminAction.php
            Route::get('/get_team_members', 'get_team_members');
            Route::get('/get_team_members/{id}', 'get_team_members');
        });
    });

    /*
    *   Users option routes
    */
    // Route::apiResource('/users', 'UsersController');
    // Route::get('/post_user/{user_id}', 'UsersController@get_post_user');
    // Route::post('user_image_update/{user_id}', 'UsersController@user_image_update');


    Route::controller(UserOptionController::class)->prefix('options')->group( function() {
        // Route::get('/get_user_data', 'get_user_data');
        Route::get('/get_selected_user_data/{user_id}', 'get_selected_user_data');
        Route::get('/get_user_notification_data', 'get_user_notification_data');
    });

    Route::controller(ServiceFollowing::class)->prefix('follow')->group( function() {
        // Editing routes moved to adminAction.php
        Route::get('/following_users_list', 'get_following_users_list');
    });

    /*
    *   Blog posts routes
    */
    Route::controller(PostController::class)->prefix('post')->group( function() {
        Route::get('/get_posts', 'get_posts');
        Route::get('/get_editing_post/{id}', 'get_editing_post');
        Route::get('/get_activ_post/{id}', 'get_activ_post');
    });

    /*
    *   Live camera routes
    */
    Route::controller(LiveCameraController::class)->prefix('live_camera')->group( function() {
        Route::get('/get_live_cameras', 'get_live_cameras');
        Route::get('/get_editing_live_camera/{id}', 'get_editing_live_camera');
        Route::get('/get_activ_live_camera/{id}', 'get_activ_live_camera');
    });

    /*
    *   Warehouse
    */
    Route::controller(WarehouseController::class)->prefix('warehouse')->group( function() {
        Route::get('/get_warehouses', 'get_warehouses');
        Route::get('/get_warehouse_data/{id}', 'get_warehouse_data');
        Route::get('/get_editing_warehouse/{id}', 'get_editing_warehouse');
        Route::get('/get_activ_warehouse/{id}', 'get_activ_warehouse');

        // Product option management in warehouses
        Route::get('/get_warehouse_product_options/{id}', 'get_warehouse_product_options');
        Route::get('/get_product_option_details/{id}/{product_option_id}', 'get_product_option_details');
    });

    /*
    *   User adresses
    */
    Route::controller(UserAdresesController::class)->group( function() {
        Route::get('/get_user_adreses', 'get_user_adreses');
        Route::post('/get_editing_adres/{adres_id}', 'get_editing_adres');
        Route::get('/get_activ_adres/{adres_id}', 'get_activ_adres');
    });

    /*
    *   User roles and pormisions routes
    */
    // Route::apiResource('/role', 'RolesController');
    Route::controller(RolesController::class)->prefix('role')->group( function() {
        Route::apiResource('/', 'RolesController');

        Route::get('get_editing_role/{role_id}', 'get_editing_role');
        Route::get('get_editing_role_permissions/{role_id}', 'get_editing_role_permissions');

        Route::get('get_user_permissions/{user_id}', 'get_user_permissions');
    });
    Route::get('/parmisions_list', 'RolesController@get_parmisions_list');

    /*
    *   Permissions
    */
    Route::controller(PermissionsController::class)->prefix('permission')->group(function() {
        Route::get('get_parmisions_for_role/{role_id}', 'get_parmisions_for_role');
    });

    // Task routes removed as TaskController does not exist

    Route::controller(NonRegisteredCommenterController::class)->prefix('non_registered_commenter')->group(function() {
        Route::get('get_non_registered_commenter', 'get_non_registered_commenter');
        // Editing routes moved to adminAction.php
    });

    /*
    *   Export routes
    */
    Route::controller(ExportController::class)->prefix('export')->group(function() {
        Route::get('/categories', 'getUniqueCategories');
        Route::get('/articles/{category}', 'getArticlesByCategory');
        // Editing routes moved to adminAction.php
    });
});
