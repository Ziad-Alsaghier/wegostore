<?php

namespace App\Http\Controllers\api\v1\admin\payment;

use App\Http\Controllers\Controller;
use App\Mail\DemoMail;
use App\Models\Domain;
use App\Models\Extra;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Plan;
use App\service\PaymentPaymob;
use App\service\UserCheck;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class PaymentPaymobController extends Controller
{

    public function __construct(
        private Payment $payment,
        private PaymentMethod $paymentMethod,
        private Order $order,
        private Extra $extra,
        private Plan $plan,
        private Domain $domain,
        ){}
        
protected $paymentRequest = ['user_id', 'plan_id','payment_method_id', 'transaction_id', 'description', 'invoice_image', 'status'];
       //
    use PaymentPaymob,UserCheck;
      public function credit(Request $request) {
        //this fucntion that send all below function data to paymob and use it for routes;
        $user = $request->user();
             $planCheck = $this->checkPlan($user) ;
             if(!$planCheck){
                     throw new HttpResponseException(response()->json([
                    'plan.message'=>'This User Don\'t Have Plan'
            ],400));
        }   
        
         $request->only($this->paymentRequest);
       return $tokens = $this->getToken();
        return$order = $this->createOrder($request,$tokens,$user);
         $paymentToken = $this->getPaymentToken($user,$request->total_amount,$order, $tokens);
       $items = $order->items;
    //    $items = $order['order'];
        $totalAmount = $request->total_amount;
        Mail::to('ziadm0176@gmail.com')->send(new DemoMail($items,$totalAmount));
        
        $paymentLink = "https://accept.paymob.com/api/acceptance/iframes/" . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentToken;
        // return response()->json([
        //     'redirect_link'=>
        // ]);
            return response()->json(
                [
                'url' => $paymentLink,
                'items'=>$items,
                'totalAmount'=>$totalAmount,
                ]
            );

        // return Redirect::away('https://accept.paymob.com/api/acceptance/iframes/'.env('PAYMOB_IFRAME_ID').'?payment_token='.$paymentToken);
    }
    public function callback(Request $request)
    {
        //this call back function its return the data from paymob and we show the full response and we checked if hmac is correct means successfull payment
        $data = $request->all();
        ksort($data);
      $hmac = $data['hmac'];
        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];
        $connectedString = '';
        foreach ($data as $key => $element) {
            if(in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
       $secret = env('PAYMOB_HMAC');
        $hased = hash_hmac('sha512', $connectedString, $secret);
        if ( $hased == $hmac) {
            //this below data used to get the last order created by the customer and check if its exists to 
            // $todayDate = Carbon::now();
            // $datas = Order::where('user_id',Auth::user()->id)->whereDate('created_at',$todayDate)->orderBy('created_at','desc')->first();
            $status = $data['success'];
            // $pending = $data['pending'];

            if ( $status == "true" ) {
                 $payment_id = $data['order'];
                $payment =  $this->payment->with('orders')->where('transaction_id',$payment_id)->first();
                //here we checked that the success payment is true and we updated the data base and empty the cart and redirct the customer to thankyou page
                 $approvedOrder =  $this->order_success($payment);
                 $approvedOrder;    
                return response()->json([
                    'success'=>'Payment Process Successfully',
                    'data'=>$approvedOrder,
                ]);

            }
            else {
                // $datas->update([
                //     'payment_id' => $data['id'],
                //     'payment_status' => "Failed"
                // ]);


                return response()->json(['message'=> 'Something Went Wrong Please Try Again']);

            }
            
        }else {
            return response()->json(['message'=> 'Something Went Wrong Please Try Again']);
        }
    }
}
