<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\user\SignUpController ;

Route::prefix('/v1')->group(function () {
    Route::withoutMiddleware('IsUser')->group(function () { // This All Route out Of Middleware User 
        Route::controller(SignUpController::class)->group(function (){ // Sign Up Routes
                Route::post(uri:'signUp', action:"signUp"); // POST /sign1Up  
        });
    });

})->middleware('IsUser');
