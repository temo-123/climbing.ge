<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\AuthenticationController;


Route::middleware('auth:sanctum')->get('token', function () {
    return auth()->user()->createToken('authToken')->plainTextToken;
});

// Route::get('login/{provider}/callback','Auth\SocialController@Callback');
// Route::get('login/{provider}', 'Auth\SocialController@redirect');

/*
*   Login verify routes
*/
Route::group(['namespace'=>'Auth', 'middleware'=>'auth:sanctum'], function() {
    // Route::get('email/verify/{hash}', 'VerificationController@verify')->name('verification.verify');
    Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');
    Route::get('auth_user', 'AuthenticationController@user')->name('auth_user');
});

Route::group(['namespace'=>'Auth'], function() {
    Route::controller(VerificationController::class)->prefix('email')->group( function() {
        Route::get('/verify/{user_id}/{hash}', 'verify');
        // Route::get('/verify/{id}/{hash}', 'verify')->name('api.verification.verify');
        // Route::get('/resend', 'resend')->name('api.verification.resend'); // resending work on defolt laravel function
    });

    // POST login route for encrypted password authentication
    Route::post('/login', 'LoginController@apiLogin');

    Route::controller(SocialController::class)->prefix('login')->group( function() {
        Route::get('/{provider}/callback','Callback');
        Route::get('/{provider}', 'redirect');
        Route::post('/social/create_password/{email}', 'create_password');
    });

    Route::controller(ForgotPasswordController::class)->prefix('password')->group( function() {
        Route::post('/send_forget_mail', 'send_forget_mail');
    });

    Route::controller(ResetPasswordController::class)->prefix('password')->group( function() {
        Route::post('/reset_password', 'reset_password');
    });
});
