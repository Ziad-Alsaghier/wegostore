<?php

use App\Http\Controllers\api\v1\admin\order\OrderController;
use App\Http\Controllers\api\v1\admin\payment\PaymentController;
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
Route::prefix('payment')->group(function () {// -Payments
            // Start Payment Method
        Route::controller(PaymentMethodController::class)->group(function () {
                        Route::post('method/create/', 'store')->name('store.paymentMethod'); // Store Payment Method
                        Route::get('method/show/', 'show')->name('show.paymentMethod'); // Show payment Method
                        Route::post('method/update/', 'modify')->name('modify.paymentMethod'); // update payment Method
                        Route::delete('method/delete/{paymentMethod_id}', 'destroy')->name('destroy.paymentMethod'); // Destroy payment Method
        });
        // Start Payment
        Route::controller(PaymentController::class)->group(function () {
                        Route::get('show/pending', 'bindPayment')->name('payment.show');// Show Payment Pending
                        Route::get('show/history', 'historyPayment')->name('payment.show'); // Show Payment History
        });
});

    Route::controller(OrderController::class)->prefix( 'order')->group(function () {
                        Route::get('show/pending', 'bindOrder')->name('payment.show');// Show Payment Pending
    });
Route::controller(PlanController::class)->prefix('plan')->group(function () {
                Route::post('create/', 'store')->name('store.plan');
                Route::post('update/', 'modify')->name('update.plan');
                Route::get('show/', 'show')->name('show.plan')->withoutMiddleware(['IsUser','auth:sanctum']);
                Route::delete('delete/{plan_id}', 'destroy')->name('show.plan');
});


});
