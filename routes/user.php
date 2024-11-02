<?php

use App\Http\Controllers\api\v1\user\profile\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\v1\user\SignUpController ;

use App\Http\Controllers\api\v1\user\store\StoreController;

Route::prefix('/v1')->group(function () {
    Route::withoutMiddleware(['IsUser','auth:sanctum'])->group(function () { // This All Route out Of Middleware User
        Route::controller(SignUpController::class)->group(function (){ // Sign Up Routes
                Route::post(uri:'signUp', action:"signUp"); // POST /sign1Up  
        });
    });
    Route::prefix('/profile')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::put(uri:'/update',action:'modify')->name(name:'update.profile');
        });
    });
    Route::prefix('/store')->group(function () {
        Route::controller(StoreController::class)->group(function () {
            Route::get(uri:'/',action:'stores')->name(name:'stores.view');
            Route::post(uri:'/add',action:'make_store')->name(name:'stores.make_store');
        });
    });

})->middleware('IsUser');
