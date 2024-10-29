<?php

use App\Http\Controllers\api\v1\admin\payment\PaymentMethodController;
use App\Http\Controllers\api\v1\admin\plan\PlanController;
use App\Http\Controllers\api\v1\admin\profile\ProfileController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1')->group(function () {
    // Route::withoutMiddleware()->group(function () { // When Need Make any Request Without Middleware

    // });

Route::controller(ProfileController::class)->prefix('profile')->group(function () {
                Route::put('update/', 'modify')->name('modify.update');
});

Route::controller(PaymentMethodController::class)->prefix('payment')->group(function () {
                Route::post('method/create/', 'store')->name('store.paymentMethod');
                Route::get('method/show/', 'show')->name('show.paymentMethod');
                Route::post('method/update/', 'modify')->name('modify.paymentMethod');
                Route::delete('method/delete/', 'destroy')->name('destroy.paymentMethod');
});
Route::controller(PlanController::class)->prefix('plan')->group(function () {
                Route::post('create/', 'store')->name('store.plan');
                Route::post('update/', 'modify')->name('update.plan');
                Route::get('show/', 'show')->name('show.plan');
                Route::delete('delete/', 'destroy')->name(name: 'destroy.plan');
});


});
