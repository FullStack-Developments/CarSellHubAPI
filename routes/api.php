<?php

use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Features\AdsController;
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
        ->group(function () {
            Route::get('brands/cars','getBrands');
            Route::get('car/seller/{sellerName}','getCarsBySellerName');
            Route::group(['prefix' => 'car'],function(){
                Route::get('/', 'index')
                    ->name('car.index');
                Route::get('/{id}', 'show')
                    ->name('car.show');

                Route::middleware('auth:sanctum')
                    ->group(function () {
                        Route::post('/', 'store')
                            ->name('car.store')
                            ->middleware('can:car.store');
                        Route::post('/{id}', 'update')
                            ->name('car.update')
                            ->middleware('can:car.update');
                        Route::delete('/{id}', 'destroy')
                            ->name('car.destroy')
                            ->middleware('can:car.destroy');
                    });
            });
        });

    Route::controller(AdsController::class)
        ->prefix('advertisement')
        ->group(function () {
            Route::get('/', 'index')
                ->name('advertisement.index');
            Route::get('/{id}', 'show')
                ->name('advertisement.show');
            Route::middleware('auth:sanctum')
                ->group(function () {
                    Route::post('/', 'store')
                    ->name('advertisement.store')
                    ->middleware('can:advertisement.store');
                });
        });
});
