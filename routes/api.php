<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialLoginController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('forgot-password', [AuthController::class, 'forgetPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::prefix('auth')->group(function () {
#google facebook
Route::get('google/redirect', [SocialLoginController::class, 'redirectToGoogle']);
Route::get('google/callback', [SocialLoginController::class, 'handleGoogleCallback']);

#Login facebook
Route::get('facebook/redirect', [SocialLoginController::class, 'redirectToFacebook']);
Route::get('facebook/callback', [SocialLoginController::class, 'handleFacebookCallback']);

#Login Instagram 
Route::get('instagram/redirect', [SocialLoginController::class, 'redirectToInstagram']);
Route::get('instagram/callback', [SocialLoginController::class, 'handleInstagramCallback']);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::get('/test', function () {
    echo "OK";
    return;
});
