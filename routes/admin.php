<?php

use App\Http\Controllers\api\v1\admin\profile\ProfileController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1')->group(function () {
    // Route::withoutMiddleware()->group(function () { // When Need Make any Request Without Middleware

    // });

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
                Route::put('update/', 'modify')->name('profile.update');
            });
 });
