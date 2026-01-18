<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User\Admin\User', 'middleware'=>['auth:sanctum', 'banned']], function() {
    /*
    *   Login verify routes
    */
    // Route::group(['middleware'=>'auth:sanctum'], function() {
    //     // Route::get('email/verify/{hash}', 'VerificationController@verify')->name('verification.verify');
    //     Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');
    //     Route::get('auth_user', 'AuthenticationController@user')->name('auth_user');
    // });

    /*
    *   Users routes
    */
    // Route::apiResource('/user_site', 'UserSiteController');


    Route::prefix('set_task')->group( function() {
        Route::controller(TaskController::class)->group( function() {
            // Route::get('/get_all_tasks', 'get_all_tasks');
            // Route::get('/get_user_tasks', 'get_user_tasks');
            // Route::get('/get_task_data/{task_id}', 'get_task_data');

            Route::post('/create_task', 'create_task');
            Route::post('/update_task/{task_id}', 'update_task');
            Route::post('/update_task_status/{task_id}', 'update_task_status');

            Route::delete('/del_task/{task_id}', 'del_task');
        });

        Route::controller(TaskCategoryController::class)->prefix('set_task_category')->group( function() {
            // Route::get('/get_all_task_categories', 'get_all_task_categories');
            // Route::get('/get_task_category_data/{task_category_id}', 'get_task_category_data');

            Route::post('/create_task_category', 'create_task_category');
            Route::post('/update_task_category/{task_category_id}', 'update_task_category');

            Route::delete('/del_task_category/{task_category_id}', 'del_task_category');
        });
    });

    Route::controller(UsersController::class)->prefix('set_user')->group( function() {
        Route::get('/get_auth_user_permissions', 'get_auth_user_permissions');
        Route::get('/get_auth_user_data', 'get_auth_user_data');
        Route::get('/get_user_data/{user_id}', 'get_user_data');
        Route::get('/get_all_users', 'get_all_users');

        Route::get('/get_worker_users', 'get_worker_users');

        Route::get('/post_user/{user_id}', 'get_post_user');

        Route::controller(UserNotificationsController::class)->prefix('set_notifications')->group( function() {
            Route::post('/send_user_favorites_notification/{action}', 'send_user_favorites_notification');
        });
    });

    Route::controller(MultimediaController::class)->prefix('set_multimedia')->group( function() {
        Route::get('/get_images_folder_structure', 'getImagesFolderStructure');
        Route::post('/upload_images', 'uploadImages');
        Route::delete('/delete_items', 'deleteItems');
        Route::post('/create_folder', 'createFolder');
        Route::get('/get_file_metadata', 'getFileMetadata');
        Route::get('/search', 'search');
    });


    /*
    *   Users option routes
    */
    // Route::apiResource('/users', 'UsersController');
    // Route::get('/post_user/{user_id}', 'UsersController@get_post_user');
    // Route::post('user_image_update/{user_id}', 'UsersController@user_image_update');



    // Route::controller(ServiceFollowing::class)->prefix('set_follow')->group( function() {
        // Route::post('/{service_id}', 'follow');
        // Route::delete('/del_follower/{id}', 'del_follower');
        // Route::get('/following_users_list', 'get_following_users_list');
    // });

    /*
    *   Blog posts routes
    */
    // Route::controller(PostController::class)->prefix('set_post')->group( function() {
    //     Route::get('/get_posts', 'get_posts');
    //     Route::get('/get_editing_post/{id}', 'get_editing_post');
    //     Route::get('/get_activ_post/{id}', 'get_activ_post');
    //     Route::post('/add_post', 'add_post');
    //     Route::post('/edit_post/{id}', 'edit_post');
    //     Route::delete('/del_post/{id}', 'del_post');
    // });


    /*
    *   Warehouse
    */
    // Route::controller(WarehouseController::class)->prefix('set_warehouse')->group( function() {
    //     Route::get('/get_warehouses', 'get_warehouses');
    //     Route::get('/get_warehouse_data/{id}', 'get_warehouse_data');
    //     Route::get('/get_editing_warehouse/{id}', 'get_editing_warehouse');
    //     Route::get('/get_activ_warehouse/{id}', 'get_activ_warehouse');

    //     // Product option management in warehouses
    //     Route::get('/get_warehouse_product_options/{id}', 'get_warehouse_product_options');
    //     Route::get('/get_product_option_details/{id}/{product_option_id}', 'get_product_option_details');
    //     Route::post('/add_warehouse', 'add_warehouse');
    //     Route::post('/edit_warehouse/{id}', 'edit_warehouse');
    //     Route::delete('/del_warehouse/{id}', 'del_warehouse');
    //     Route::post('/add_product_option_to_warehouse/{id}', 'add_product_option_to_warehouse');
    //     Route::post('/edit_product_option_quantity/{id}/{product_option_id}', 'edit_product_option_quantity');
    //     Route::delete('/delete_product_option_from_warehouse/{id}/{product_option_id}', 'delete_product_option_from_warehouse');
    //     Route::post('/migrate_product_option/{id}/{product_option_id}', 'migrate_product_option');
    // });

    /*
    *   User adresses
    */
    // Route::controller(UserAdresesController::class)->group( function() {
    //     Route::get('/get_user_adreses', 'get_user_adreses');
    //     Route::post('/get_editing_adres/{adres_id}', 'get_editing_adres');
    //     Route::get('/get_activ_adres/{adres_id}', 'get_activ_adres');
    //     Route::post('/add_user_adreses', 'add_user_adreses');
    //     Route::post('/edit_adres/{adres_id}', 'edit_adres');
    //     Route::delete('/del_user_adreses/{adres_id}', 'del_user_adreses');
    // });

    /*
    *   User roles and pormisions routes
    */
    // Route::apiResource('/role', 'RolesController');
    Route::controller(RolesController::class)->prefix('set_role')->group( function() {
        Route::apiResource('/', 'RolesController');

        Route::get('get_editing_role/{role_id}', 'get_editing_role');
        Route::get('get_editing_role_permissions/{role_id}', 'get_editing_role_permissions');

        Route::get('get_user_permissions/{user_id}', 'get_user_permissions');
        Route::post('/create_role', 'create_role');
        Route::post('/edit_role/{role_id}', 'edit_role');
        Route::delete('/del_role_permission/{role_id}/{permission_id}', 'del_role_permission');
        Route::delete('/del_role/{role_id}', 'del_role');
        Route::post('/edit_permissions_and_role/{user_id}', 'edit_permissions_and_role');
        Route::delete('/del_user_pemisino/{permission_id}/{user_id}', 'del_user_pemisino');
    });
    Route::get('/parmisions_list', 'RolesController@get_parmisions_list');

    /*
    *   Permissions
    */
    Route::controller(PermissionsController::class)->prefix('set_permission')->group(function() {
        Route::get('get_parmisions_for_role/{role_id}', 'get_parmisions_for_role');
    });

    // Task routes removed as TaskController does not exist



    // Route::controller(NonRegisteredCommenterController::class)->prefix('set_non_registered_commenter')->group(function() {
    //     Route::get('get_non_registered_commenter', 'get_non_registered_commenter');
    //     Route::delete('/del_non_registered_commenter/{id}', 'del_non_registered_commenter');
    // });


    // /*
    // *   Warehouse
    // */
    // Route::controller(WarehouseController::class)->prefix('set_warehouse')->group( function() {
    //     Route::get('/get_warehouses', 'get_warehouses');
    //     Route::get('/get_warehouse_data/{id}', 'get_warehouse_data');
    //     Route::get('/get_editing_warehouse/{id}', 'get_editing_warehouse');
    //     Route::get('/get_activ_warehouse/{id}', 'get_activ_warehouse');

    //     // Product option management in warehouses
    //     Route::get('/get_warehouse_product_options/{id}', 'get_warehouse_product_options');
    //     Route::get('/get_product_option_details/{id}/{product_option_id}', 'get_product_option_details');
    // });

    /*
    *   User roles and pormisions routes
    */
    // Route::apiResource('/role', 'RolesController');
    Route::controller(RolesController::class)->prefix('set_role')->group( function() {
        Route::apiResource('/', 'RolesController');

        Route::get('get_editing_role/{role_id}', 'get_editing_role');
        Route::get('get_editing_role_permissions/{role_id}', 'get_editing_role_permissions');

        Route::get('get_user_permissions/{user_id}', 'get_user_permissions');
    });
    Route::get('/parmisions_list', 'RolesController@get_parmisions_list');

    /*
    *   Permissions
    */
    Route::controller(PermissionsController::class)->prefix('set_permission')->group(function() {
        Route::get('get_parmisions_for_role/{role_id}', 'get_parmisions_for_role');
    });

    // Task routes removed as TaskController does not exist


    /*
    *   Export routes
    */
    // Route::controller(ExportController::class)->prefix('set_export')->group(function() {
    //     Route::get('/categories', 'getUniqueCategories');
    //     Route::get('/articles/{category}', 'getArticlesByCategory');
    //     // Editing routes moved to adminAction.php
    // });
    /*
    *   Export routes
    */
    Route::controller(ExportController::class)->prefix('set_export')->group(function() {
        Route::get('/categories', 'getUniqueCategories');
        Route::get('/articles/{category}', 'getArticlesByCategory');
        Route::post('/articles_pdf', 'exportArticlesPdf');
    });
});
