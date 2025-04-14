<?php

use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Features\AdvertisementController;
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
    Route::controller(CarController::class)->prefix('cars')->group(function () {
        Route::prefix('client')->group(function () {
            Route::get('/', 'index')->name('cars.index');
            Route::get('/brands','getBrands')->name('cars.brands');
            Route::get('/car-by-id/{id}', 'showCarsById')->name('car.showCarsById');
            Route::get('/car-by-seller-name/{sellerName}','getCarsBySellerName')->name('car.getCarsBySellerName');
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::prefix('seller')->group(function () {
                Route::get('/','showCarsForSeller')->name('cars.showCarsForSeller');
                Route::post('/', 'store')->name('cars.store');
                Route::post('/{id}', 'update')->name('cars.update');
            });
            Route::prefix('admin')->group(function () {
                Route::get('/', 'index')->name('cars.admin.index');
                Route::post('/{id}', 'update')->name('cars.update');
                Route::delete('/{id}', 'destroy')->name('cars.destroy');
            });
        });
    });

    Route::controller(AdvertisementController::class)->prefix('advertisements')->group(function () {
        Route::prefix('client')->group(function () {
            Route::get('/', 'index')
                ->name('advertisement.index');
            Route::get('/{id}', 'showAdsById')
                ->name('advertisement.showAdsById');
        });
        Route::middleware('auth:sanctum')->group(function () {
            Route::prefix('seller')->group(function () {
                Route::get('/', 'showAdsForSeller')->name('advertisement.showAdsForSeller'); //show ads for auth seller
                Route::post('/', 'store')->name('advertisement.store');
                Route::post('/{id}', 'updateBySeller')->name('advertisement.updateBySeller');
            });

            Route::prefix('admin')->group(function () {
                Route::post('/{id}', 'updateByAdmin')->name('advertisement.updateByAdmin');
                Route::delete('/{id}', 'destroy')->name('advertisement.destroy');
            });
        });
    });
});
