<?php
// Test
use App\Http\Controllers\api\v1\admin\payment\PaymentPaymobController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
   Route::prefix('payment')->group(function () {
   Route::get('/credit',[PaymentPaymobController::class, 'credit']);
   Route::get('/callback',[PaymentPaymobController::class, 'callback']);
   });
