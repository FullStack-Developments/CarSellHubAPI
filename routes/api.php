<?php

use App\Http\Controllers\AuthenticatedController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::controller(AuthenticatedController::class)
        ->group(function () {
            Route::post('register', 'register');
            Route::post('login', 'login');
            Route::post('logout', 'logout')
                ->middleware('auth:sanctum');
        });

    Route::controller(EmailVerificationController::class)
        ->group(function (){
            Route::post('verify-email', 'verifyEmail');
            Route::post('resend-code', 'resendVerificationEmail')
                ->middleware(['auth:sanctum', 'throttle:tenMinutes']);
        });

    Route::controller(ResetPasswordController::class)
        ->group(function (){
            Route::post('forget-password', 'forgetPassword')
                ->middleware('throttle:tenMinutes');
        });
});
