<?php

use App\Http\Controllers\AuthenticatedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'auth'], function () {
    Route::controller(AuthenticatedController::class)
        ->group(function () {
            Route::post('register', 'register');
            Route::post('login', 'login');

//            Route::middleware('auth:sanctum')
//                ->group(function () {
//                    Route::get('logout', 'logout');
//                    Route::get('user', 'user');
//                });
        });
});
