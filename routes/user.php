<?php

use App\Http\Controllers\api\v1\user\profile\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\user\SignUpController ;

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

})->middleware('IsUser');
