<?php

use App\Http\Controllers\AuthenticatedController;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::controller(AuthenticatedController::class)
        ->group(function () {
            Route::post('register', 'register');
            Route::post('login', 'login');
            Route::middleware('auth:sanctum')
                ->group(function () {
                    Route::post('logout', 'logout');
                });
        });

    Route::controller(EmailVerificationController::class)
        ->group(function (){
            Route::post('verify-email', 'verifyEmail');
            Route::middleware('auth:sanctum')
                ->group(function () {
                    Route::post('resend-code', 'resendVerificationEmail');
                });
        });
});
