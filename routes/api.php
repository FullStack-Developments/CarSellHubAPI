<?php

use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Features\CarController;
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
            Route::post('reset-password', 'resetPassword');
        });
});

Route::group(['prefix' => 'home'], function (){
    Route::controller(CarController::class)
        ->prefix('car')
        ->group(function () {
            Route::get('/', 'index');
            Route::middleware('auth:sanctum')->group(function () {
                Route::post('/', 'store');
            });

        });
});
