<?php

namespace App\Http\Controllers\api\v1\admin\payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // This About All Payment 
        public function __construct(private Payment $payment){}
    public function bindPayment(){
        url:http://localhost/wegostore/public/admin/v1/payment/show/pending
        try {
            $payments = $this->payment->with('payment_method','user','order_payment.order_items.plans')->where('status',"pending")->get();
            empty($payments) == Null ? $data = $payments : $data = "Not Found any Payments";
            return response()->json([
            'payment.message'=>'data Returned Successfully',
            'payment'=>$data
            ]);
        } catch (\Throwable $th) {
                return response()->json(['payment.message'=>'Not Found any Payments']);
        }
    }
    public function historyPayment(){
        url:http://localhost/wegostore/public/admin/v1/payment/show/history
         try {
            $historyPayments = $this->payment->get();
            empty($historyPayments) == Null ? $data = $historyPayments : $data = "History Is Empty";
            return response()->json([
            'payment.message'=>'data Returned Successfully',
            'payment'=>$data
            ]);
        } catch (\Throwable $th) {
                return response()->json(['payment.message'=>'Not Found any Payments']);
        }
    }

    
}
