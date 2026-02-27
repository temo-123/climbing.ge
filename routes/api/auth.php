<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\AuthenticationController;

// Public debug endpoints - no authentication required
Route::get('/debug/login', function () {
    return response()->json([
        'message' => 'Login endpoint is accessible',
        'method' => request()->method(),
        'headers' => request()->headers->all(),
        'accept_header' => request()->header('Accept'),
    ]);
});

Route::get('/debug/test', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user() ? auth()->user()->only('id', 'email', 'name') : null,
    ]);
});

// Protected routes - authentication required
Route::middleware(['auth:sanctum', 'banned'])->group(function () {
    Route::get('token', function () {
        return auth()->user()->createToken('authToken')->plainTextToken;
    });
});

// Public authentication routes (login, password reset, social auth)
Route::group(['namespace'=>'Auth'], function() {
    Route::controller(VerificationController::class)->prefix('email')->group( function() {
        Route::get('/verify/{user_id}/{hash}', 'verify');
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

// Protected routes - authentication required for user-specific operations
Route::middleware(['auth:sanctum', 'banned'])->group(function () {
    Route::group(['namespace'=>'Auth'], function() {
        Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');
        Route::get('auth_user', 'AuthenticationController@user')->name('auth_user');
    });
});
