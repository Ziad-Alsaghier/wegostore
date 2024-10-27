<?php

use App\Http\Controllers\api\v1\admin\payment\PaymentMethodController;
use App\Http\Controllers\api\v1\admin\plan\PlanController;
use App\Http\Controllers\api\v1\admin\profile\ProfileController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1')->group(function () {
    // Route::withoutMiddleware()->group(function () { // When Need Make any Request Without Middleware

    // });

Route::controller(ProfileController::class)->prefix('profile')->group(function () {
                Route::put('update/', 'modify')->name('profile.update');
});

Route::controller(PaymentMethodController::class)->prefix('payment')->group(function () {
                Route::post('method/create/', 'store')->name('store.paymentMethod');
                Route::post('method/show/', 'show')->name('store.paymentMethod');
});
Route::controller(PlanController::class)->prefix('plan')->group(function () {
                Route::post('create/', 'store')->name('store.paymentMethod');
                Route::post('update/', 'modify')->name('store.paymentMethod');
                Route::post('show/', 'show')->name('store.paymentMethod');
});


});
