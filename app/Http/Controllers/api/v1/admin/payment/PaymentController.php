<?php

namespace App\Http\Controllers\api\v1\admin\payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\payment\ApprovePayment;
use App\Http\Requests\api\v1\admin\payment\ApprovePaymentRequest;
use App\Models\Payment;
use App\Models\Store;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // This About All Payment 
        public function __construct(
            private Payment $payment,
            private Store $store
            ){}
    public function bindPayment(){
        url:http://localhost/wegostore/public/admin/v1/payment/show/pending
        try {
            $payments = $this->payment->with('payment_method','user','orders')->where('status',"pending")->get();
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
            $historyPayments =
            $this->payment->with('payment_method','user','orders')->where('status',"approved")->get();
            empty($historyPayments) && $historyPayments == Null ? $data = $historyPayments : $data = "History Is Empty";
            return response()->json([
            'payment.message'=>'data Returned Successfully',
            'payment'=>$data
            ]);
        } catch (\Throwable $th) {
                return response()->json(['payment.message'=>'Not Found any Payments']);
        }
    }
}
