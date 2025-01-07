<?php

namespace App\Http\Controllers\api\v1\admin\payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\payment\ApprovePayment;
use App\Http\Requests\api\v1\admin\payment\ApprovePaymentRequest;
use App\Models\Payment;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionEmail;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // This About All Payment 
        public function __construct(
            private Payment $payment,
            private Store $store,
            private User $user,
        ){}
    public function bindPayment(){

        url:http://localhost/wegostore/public/admin/v1/payment/show/pending
        try {
            $payments = $this->payment->with(['payment_method','user.plan',
            'orders' => function($query){
                $query->with(['plans', 'domain', 'extra', 'store']);
            }])
            ->where('status',"pending")->get();
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
            $historyPayments = $this->payment
            ->with(['payment_method','user', 'orders' => function($query){
                $query->with(['plans', 'domain', 'extra', 'store']);
            }])->where('status', '!=', "pending")->get();
            empty($historyPayments) && $historyPayments == Null ? $data = "History Is Empty" :  $data = $historyPayments;
            return response()->json([
            'payment.message'=>'data Returned Successfully',
            'payment'=>$data
            ]);
        } catch (\Throwable $th) {
                return response()->json(['payment.message'=>'Not Found any Payments']);
        }
    }

    public function approve_payment(Request $request, $id){
        // payment/approve/{id}
        $payment = $this->payment
        ->where('id', $id)
        ->first();
        $payment->update([
            'status' =>  'approved'
        ]);
        
        foreach ($payment->orders as $order) {
            if (!empty($order->plan_id)) {
                $user = $this->user
                ->where('id', $payment->user_id)
                ->first();
                $user->plan_id = $order->plan_id;
                $duration = '1';
                if ($order->price_cycle == 'yearly') {
                    $expire_date = Carbon::now()->addYear();
                    $duration = 'yearly';
                } 
                elseif ($order->price_cycle == 'semi_annual') {
                    $expire_date = Carbon::now()->addMonth(6);
                    $duration = '6';
                } 
                elseif ($order->price_cycle == 'quarterly') {
                    $expire_date = Carbon::now()->addMonth(3);
                    $duration = '3';
                }
                elseif ($order->price_cycle == 'monthly') {
                    $expire_date = Carbon::now()->addMonth(1);
                    $duration = '1';
                }
                $user->expire_date = $expire_date;
                $user->start_date = date('Y-m-d');
                $user->package = $duration;
                $user->save();
                $order->expire_date = $expire_date;
                $order->save();
                $data = $order;
                $order->plans; 
                $order->users;
                
                Mail::to('wegotores@gmail.com')->send(new SubscriptionEmail($data));
            }
            if (!empty($order->extra_id)) {
                $expire_date = null;
                if ($order->price_cycle == 'yearly') {
                    $expire_date = Carbon::now()->addYear();
                }
                elseif ($order->price_cycle == 'semi_annual') {
                    $expire_date = Carbon::now()->addMonth(6);
                } 
                elseif ($order->price_cycle == 'quarterly') {
                    $expire_date = Carbon::now()->addMonth(3);
                }
                elseif ($order->price_cycle == 'monthly') {
                    $expire_date = Carbon::now()->addMonth(1);
                }
                $order->expire_date = $expire_date;
                $order->save();
            }
            if (!empty($order->domain_id)) {
                $domain = $order->domain;
                $domain->price_status = true;
                $domain->save();
            }
        }

        return response()->json([
            'success' => 'You approved payment success'
        ]);
    }

    public function rejected_payment(Request $request, $id){
        // payment/rejected/{id}
        // $validator = Validator::make($request->all(), [
        //     'rejected_reason' => 'required',
        // ]);
        // if ($validator->fails()) { // if Validate Make Error Return Message Error
        //     return response()->json([
        //         'error' => $validator->errors(),
        //     ],400);
        // }
        $this->payment
        ->where('id', $id)
        ->update([
            'rejected_reason' => $request->rejected_reason,
            'status' =>  'rejected'
        ]);

        return response()->json([
            'success' => 'You rejected payment success'
        ]);
    }
}
