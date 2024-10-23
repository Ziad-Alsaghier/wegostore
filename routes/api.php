<?php

use App\Http\Controllers\api\v1\auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(AuthController::class)->prefix('v1/auth/')->group(function () {
    Route::post('login','auth')->name('auth.login');
});

 Route::get('/login', function () {
 return response()->json(['error'=>'You Are Unauthorized'],401);
 })->name('login');
