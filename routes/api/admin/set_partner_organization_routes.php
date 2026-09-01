<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User\Admin\PartnerOrganization', 'middleware'=>['auth:sanctum', 'banned']], function() {
    Route::controller(PartnerOrganizationController::class)->prefix('set_partner_organization')->group( function() {
        Route::get('/get_all_organizations', 'get_all_organizations');
        Route::get('/get_editing_organization_data/{id}', 'get_editing_organization_data');
        Route::post('/add_organization', 'add_organization');
        Route::post('/edit_organization/{id}', 'edit_organization');
        Route::delete('/del_organization/{id}', 'del_organization');
        Route::post('/bulk_delete', 'bulk_delete');
    });

    Route::controller(PartnerOrganizationMemberController::class)->prefix('set_partner_organization_member')->group( function() {
        Route::get('/get_all_members', 'get_all_members');
        Route::get('/get_editing_member_data/{id}', 'get_editing_member_data');
        Route::post('/add_member', 'add_member');
        Route::post('/edit_member/{id}', 'edit_member');
        Route::delete('/del_member/{id}', 'del_member');
        Route::post('/bulk_delete', 'bulk_delete');

        Route::get('/get_user_status/{user_id}', 'get_user_status');
        Route::post('/assign_user/{user_id}', 'assign_user');
        Route::delete('/unassign_user/{user_id}', 'unassign_user');
    });
});
