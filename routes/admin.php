<?php

use App\Http\Controllers\api\v1\admin\payment\PaymentMethodController;
use App\Http\Controllers\api\v1\admin\plan\PlanController;
use App\Http\Controllers\api\v1\admin\profile\ProfileController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1')->group(function () {
    // Route::withoutMiddleware()->group(function () { // When Need Make any Request Without Middleware

    // });

Route::controller(controller:ProfileController::class)->prefix(prefix:'profile')->group(callback:function () {
                Route::put(uri:'update/', action:'modify')->name(name:'modify.update');
});

Route::controller(controller:PaymentMethodController::class)->prefix(prefix:'payment')->group(callback:function () {
                Route::post(uri:'method/create/', action:'store')->name(name:'store.paymentMethod');
                Route::get(uri:'method/show/', action:'show')->name(name:'show.paymentMethod');
                Route::post(uri:'method/update/', action:'modify')->name(name:'modify.paymentMethod');
                Route::delete(uri:'method/delete/{id}', action:'destroy')->name(name:'destroy.paymentMethod');
});

Route::controller(controller:PlanController::class)->prefix(prefix:'plan')->group(callback:function () {
                Route::post(uri:'create/', action:'store')->name(name:'store.plan');
                Route::post(uri:'update/', action:'modify')->name(name:'update.plan');
                Route::get(uri:'show/', action:'show')->name(name:'show.plan');
                Route::delete(uri:'delete/{id}', action:'destroy')->name(name:'destroy.plan');
});


});
