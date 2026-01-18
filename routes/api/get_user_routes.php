<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User', 'middleware'=>['auth:sanctum', 'banned']], function() {



    Route::prefix('get_task')->group( function() {
        Route::controller(TaskController::class)->group( function() {
            Route::get('/get_all_tasks', 'get_all_tasks');
            Route::get('/get_user_tasks', 'get_user_tasks');
            Route::get('/get_task_data/{task_id}', 'get_task_data');

            // Route::post('/create_task', 'create_task');
            // Route::post('/update_task/{task_id}', 'update_task');
            // Route::post('/update_task_status/{task_id}', 'update_task_status');

            // Route::delete('/del_task/{task_id}', 'del_task');
        });

        // Route::controller(TaskCategoryController::class)->prefix('get_task_category')->group( function() {
        //     Route::get('/get_all_task_categories', 'get_all_task_categories');
        //     Route::get('/get_task_category_data/{task_category_id}', 'get_task_category_data');

            // Route::post('/create_task_category', 'create_task_category');
            // Route::post('/update_task_category/{task_category_id}', 'update_task_category');

            // Route::delete('/del_task_category/{task_category_id}', 'del_task_category');
        // });
    });

    /*
    *   Users routes
    */
    Route::apiResource('/user_site', 'UserSiteController');

    Route::controller(UsersController::class)->prefix('get_user')->group( function() {
        Route::get('/get_auth_user_permissions', 'get_auth_user_permissions');
        Route::get('/get_auth_user_data', 'get_auth_user_data');
        Route::get('/get_user_data/{user_id}', 'get_user_data');
        Route::get('/get_all_users', 'get_all_users');

        Route::get('/get_worker_users', 'get_worker_users');

        Route::get('/post_user/{user_id}', 'get_post_user');

        Route::controller(UserNotificationsController::class)->prefix('get_notifications')->group( function() {
            // Editing routes moved to adminAction.php
        });

        Route::controller(TeamMemberController::class)->prefix('get_team')->group( function() {
            Route::get('/get_member_status/{id}', 'get_member_status');
            
            Route::get('/get_team_members', 'get_team_members');
            // Route::get('/get_team_member/{id}', 'get_team_member');
        });
    });

    Route::controller(FaworitesController::class)->prefix('get_faworite')->group( function() {
        Route::get('/get_faworite_outdoor_region', 'get_faworite_outdoor_region');
        Route::get('/get_interested_events', 'get_interested_events');
        Route::get('/check_interested_status/{event_id}', 'check_interested_status');
        Route::get('/check_favorite_status/{article_id}', 'check_favorite_status');
    });

    Route::controller(FaworitesController::class)->prefix('set_faworite_by_user')->group( function() {
        Route::post('/add_to_interested_events', 'add_to_interested_events');
        Route::post('/add_to_favorite_outdoor_area/{article_id}', 'add_to_favorite_outdoor_area');
    });

    Route::controller(FaworitesController::class)->prefix('set_faworite')->group( function() {
        Route::delete('/del_interested_event/{favoryte_ivent_if}', 'del_interested_event');
        Route::delete('/del_favorite_outdoor_area/{favorite_outdoor_id}', 'del_favorite_outdoor_area');
    });


    Route::controller(UserOptionController::class)->prefix('get_options')->group( function() {
        // Route::get('/get_user_data', 'get_user_data');
        Route::get('/get_selected_user_data/{user_id}', 'get_selected_user_data');
        Route::get('/get_user_notification_data', 'get_user_notification_data');
        Route::post('/user_info_update/{user_id}', 'user_info_update');
        Route::post('/update_user_notification_data', 'update_user_notification_data');
    // });
    // Route::controller(UserOptionController::class)->prefix('get_options')->group( function() {
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
    // Route::controller(PostController::class)->prefix('get_post')->group( function() {
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
