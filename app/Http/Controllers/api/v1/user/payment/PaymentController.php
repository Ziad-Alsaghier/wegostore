<?php

namespace App\Http\Controllers\api\v1\user\payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payment;

class PaymentController extends Controller
{
    public function __construct(private Payment $payment){}

    public function history(Request $request){
        // payment/history
        $payment_history = $this->payment
        ->where('status', '!=', 'pending')
        ->where('user_id', $request->user()->id)
        ->with(['orders' => function($query){
            $query->with(['plans', 'domain.store', 'extra']);
        }, 'payment_method', 'user'])
        ->get();

        return response()->json([
            'payment_history' => $payment_history
        ]);
    }
}
