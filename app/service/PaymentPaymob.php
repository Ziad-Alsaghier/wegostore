<?php

namespace App\service;

use App\service\order\placeOrder;
use Illuminate\Support\Facades\Http;

trait PaymentPaymob
{
    // This Trait About Srvic Payment Paymob
protected $paymentRequest = ['user_id', 'plan_id','payment_method_id', 'transaction_id', 'description', 'invoice_image', 'status'];
 
    use placeOrder;
     public function getToken() {
     //this function takes api key from env.file and get token from paymob accept
     $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
     'api_key' => env('PAYMOB_API_KEY')
     ]);
     return $response->object()->token;
     }

      public function createOrder( $request,$tokens,$user) {
        //This function takes last step token and send new order to paymob dashboard
        // in This Function We Make Order For Returned Token 
        // $amount = new Checkoutshow; here you add your checkout controller
        // $total = $amount->totalProductAmount(); total amount function from checkout controller
        //here we add example for test only
       
           $items = $this->placeOrder($request,$user);
            
        //  $total = 100;
        // $items = [
        //     [ "name"=> "ASC1515",
        //         "amount_cents"=> "500000",
        //         "description"=> "Smart Watch",
        //         "quantity"=> "1"
        //     ],
        //     [
        //         "name"=> "ERT6565",
        //         "amount_cents"=> "200000",
        //         "description"=> "Power Bank",
        //         "quantity"=> "1"
        //     ]
        // ];
        $data = $items;
         $totalAmountCents = collect($data)->sum('amount_cents');
; 
            $data = [
            "auth_token" =>   $tokens,
            "delivery_needed" =>"false",
            "amount_cents"=> $totalAmountCents*100,
            "currency"=> "EGP",
            "items"=> $items,

        ];
        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', $data);
        return $response->object();
    }

       public function getPaymentToken($order, $token)
    {
        //this function to add details to paymob order dashboard and you can fill this data from your Model Class as below

      
        // $amountt = new Checkoutshow;
        // $totall = $amountt->totalProductAmount();
        // $todayDate = Carbon::now();
        // $dataa = Order::where('user_id',Auth::user()->id)->whereDate('created_at',$todayDate)->orderBy('created_at','desc')->first();

        //we just added dummy data for test
        //all data we fill is required for paymob
        $billingData = [
            "apartment" => '45', //example $dataa->appartment
            "email" => "ziadm0176@gmai.com", //example $dataa->email
            "floor" => '7',
            "first_name" => 'ziad',
            "street" => "NA",
            "building" => "NA",
            "phone_number" => '01099475854',
            "shipping_method" => "NA",
            "postal_code" => "NA",
            "city" => "Alexandria",
            "country" => "Egypt",
            "last_name" => "mohamed",
            "state" => "0"
        ];
        $data = [
            "auth_token" => $token,
            "amount_cents" => 100*100,
            "expiration" => 3600,
            "order_id" => $order->id, // this order id created by paymob
            "billing_data" => $billingData,
            "currency" => "EGP",
            "integration_id" => env('PAYMOB_INTEGRATION_ID')
        ];
         $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', $data);
        return $response->object()->token;
    }
}
