<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User\Admin\User', 'middleware'=>['auth:sanctum', 'banned']], function() {

    Route::prefix('set_task')->group( function() {
        Route::controller(TaskController::class)->group( function() {
            Route::post('/create_task', 'create_task');
            Route::post('/update_task/{task_id}', 'update_task');
            Route::post('/update_task_status/{task_id}', 'update_task_status');

            Route::delete('/del_task/{task_id}', 'del_task');
        });

        Route::controller(TaskCategoryController::class)->prefix('set_task_category')->group( function() {
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

    Route::controller(PermissionsController::class)->prefix('set_permission')->group(function() {
        Route::get('get_parmisions_for_role/{role_id}', 'get_parmisions_for_role');
    });

    Route::controller(RolesController::class)->prefix('set_role')->group( function() {
        Route::apiResource('/', 'RolesController');

        Route::get('get_editing_role/{role_id}', 'get_editing_role');
        Route::get('get_editing_role_permissions/{role_id}', 'get_editing_role_permissions');

        Route::get('get_user_permissions/{user_id}', 'get_user_permissions');
    });
    Route::get('/parmisions_list', 'RolesController@get_parmisions_list');

    Route::controller(PermissionsController::class)->prefix('set_permission')->group(function() {
        Route::get('get_parmisions_for_role/{role_id}', 'get_parmisions_for_role');
    });

    Route::controller(ExportController::class)->prefix('set_export')->group(function() {
        Route::get('/categories', 'getUniqueCategories');
        Route::get('/articles/{category}', 'getArticlesByCategory');
        Route::post('/articles_pdf', 'exportArticlesPdf');
    });
});
