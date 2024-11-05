<?php

use App\Http\Controllers\api\v1\user\profile\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\v1\user\SignUpController;

use App\Http\Controllers\api\v1\user\store\StoreController;

use App\Http\Controllers\api\v1\user\subscription\SubscriptionController;

use App\Http\Controllers\api\v1\user\domain\DomainController;

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
            Route::put(uri:'/delete/{id}',action:'delete_store')->name(name:'stores.delete_store');
        });
    });
    Route::prefix('/subscription')->group(function () {
        Route::controller(SubscriptionController::class)->group(function () {
            Route::get(uri:'/',action:'plans')->name(name:'subscription.view');
            Route::get(uri:'/payment_methods',action:'payment_methods')->name(name:'subscription.payment_methods');
            Route::post(uri:'/buy_plan',action:'buy_plan')->name(name:'subscription.buy_plan');
        });
    });
    Route::prefix('/domains')->group(function () {
        Route::controller(DomainController::class)->group(function () {
            Route::get(uri:'/my_domains',action:'my_domains')->name(name:'domains.my_domains');
            Route::get(uri:'/domain_request',action:'domain_request')->name(name:'domains.domain_request');
        });
    });

})->middleware('IsUser');
