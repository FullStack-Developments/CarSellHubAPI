<?php

use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Features\AdvertisementController;
use App\Http\Controllers\Features\CarController;
use App\Http\Controllers\Features\ReviewController;
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

//Routes for admin
Route::prefix('admin')
->middleware('auth:sanctum')
->group(function (){
    Route::controller(CarController::class)
    ->prefix('cars')
    ->group(function () {
        Route::get('/', 'index')->name('admin.cars.index');
        Route::get('brands/show','getBrands')->name('admin.cars.brands');
        Route::get('car-by-seller-name/{sellerName}','getCarsBySellerName')->name('admin.cars.getCarsBySellerName');
        Route::post('{id}', 'update')->name('admin.cars.update');
        Route::delete('{id}', 'destroy')->name('admin.cars.destroy');
    });

    Route::controller(AdvertisementController::class)
        ->prefix('advertisements')
        ->group(function () {
            Route::get('/', 'showAllAdsForAdmin')->name('admin.advertisement.showAllAds');
            Route::get('/{id}', 'showAdById')->name('admin.advertisement.showAdById');
            Route::post('/{id}', 'updateByAdmin')->name('admin.advertisement.updateByAdmin');
            Route::delete('/{id}', 'destroy')->name('admin.advertisement.destroy');
        });
});

// feature Routes for sellers and clients
Route::group(['prefix' => 'home'], function (){
    Route::controller(CarController::class)->prefix('cars')->group(function () {
        Route::prefix('client')->group(function () {
            Route::get('/', 'index')->name('client.cars.index');
            Route::get('/brands','getBrands')->name('client.cars.brands');
            Route::get('/car-by-id/{id}', 'showCarsById')->name('client.cars.showCarsById');
            Route::get('/car-by-seller-name/{sellerName}','getCarsBySellerName')->name('client.cars.getCarsBySellerName');
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::prefix('seller')->group(function () {
                Route::get('/','showCarsForSeller')->name('seller.cars.showCarsForSeller');
                Route::post('/', 'store')->name('seller.cars.store');
                Route::post('/{id}', 'update')->name('seller.cars.update');
            });
        });
    });

    Route::controller(AdvertisementController::class)->prefix('advertisements')->group(function () {
        Route::prefix('client')->group(function () {
            Route::get('/', 'showAllAdsForClients')->name('client.advertisement.index');
            Route::get('/{id}', 'showAdById')->name('client.advertisement.showAdById');
        });

        Route::prefix('seller')->middleware('auth:sanctum')->group(function () {
            Route::get('/', 'showAdsForSeller')->name('seller.advertisement.showAdsForSeller'); //show ads for auth seller
            Route::post('/', 'store')->name('seller.advertisement.store');
            Route::post('/{id}', 'updateBySeller')->name('seller.advertisement.updateBySeller');
        });
    });

    Route::controller(ReviewController::class)->prefix('reviews')->group(function () {
        Route::get('/', 'indexPublicReviews')->name('reviews.index-public-reviews');
        Route::post('/','store')->name('reviews.store');
        Route::get('/car/{carId}','indexReviewsByCarId')->name('reviews.index-reviews-by-carId');

        Route::prefix('seller')->middleware('auth:sanctum')->group(function () {
            Route::get('/', 'indexReviewsForCarSeller')->name('seller.reviews.index-reviews-for-car-seller');
        });
    });
});
