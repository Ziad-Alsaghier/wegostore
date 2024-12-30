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
use App\services\PaymentPaymob;
use App\services\UserCheck;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Mail\SubscriptionEmail;
use App\Mail\PaymentMail;
use Carbon\Carbon;

class PaymentPaymobController extends Controller
{

    public function __construct(
        private Payment $payment,
        private PaymentMethod $paymentMethod,
        private Order $order,
        private Extra $extra,
        private Plan $plan,
        private Domain $domain,
    ) {}

    protected $paymentRequest = ['user_id', 'plan_id', 'payment_method_id', 'transaction_id', 'description', 'invoice_image', 'status'];

    protected $cart = ['cart'];
    use PaymentPaymob, UserCheck;
    public function credit(Request $request)
    {
        //this fucntion that send all below function data to paymob and use it for routes;
        $user = $request->user();
        $planCheck = $this->checkUserPlan($user);

         $paymentRequest =  $request->only($this->paymentRequest);
        $cart = $request->only($this->cart);
        $tokens = $this->getToken();
        if (!$planCheck) {
             $order = $this->createOrder($request, $tokens, $user, 'plan');
        } else {
           
             $order = $this->createOrder($request, $tokens, $user, 'cart');
        }
        $amount_cents = $order->amount_cents;

        $paymentToken = $this->getPaymentToken($user, $amount_cents, $order, $tokens);

        $items = $order->items;
        //    $items = $order['order'];
        $totalAmount = (float)$request->total_amount;
        Mail::to($user->email)->send(new DemoMail($items,$totalAmount));
        $paymentLink = "https://accept.paymob.com/api/acceptance/iframes/" . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentToken;
        //  redirect($paymentLink);
        return response()->json(
            [
                'url' => $paymentLink,
                'items' => $items,
                'totalAmount' => $amount_cents,
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
            if (in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
        $secret = env('PAYMOB_HMAC');
        $hased = hash_hmac('sha512', $connectedString, $secret);
        if ($hased == $hmac) {
            //this below data used to get the last order created by the customer and check if its exists to 
            // $todayDate = Carbon::now();
            // $datas = Order::where('user_id',Auth::user()->id)->whereDate('created_at',$todayDate)->orderBy('created_at','desc')->first();
            $status = $data['success'];
            // $pending = $data['pending'];

            if ($status == "true") {
                $payment_id = $data['order'];
                $payment =  $this->payment->with('orders','orders.plans','orders.extra','orders.domain')->where('transaction_id', $payment_id)->first();  
				$data = $this->payment
                ->where('id', $payment->id)
                ->with(['orders' => function($query){
                    $query->with(['plans', 'domain.store', 'extra']);
                }, 'payment_method', 'user'])
                ->first();
                Mail::to('wegotores@gmail.com')->send(new PaymentMail($data));
                $orders = $payment->orders;
                foreach ($orders as $key => $order) {
                    if (!empty($order->plan_id)) {
                        $user = $request->user();
                        $duration = 1;
                        if ($order->price_cycle == 'yearly') {
                            $expire_date = Carbon::now()->addYear();
                            $duration = 'yearly';
                        } 
                        elseif ($order->price_cycle == 'semi_annual') {
                            $expire_date = Carbon::now()->addMonth(6);
                            $duration = 6;
                        } 
                        elseif ($order->price_cycle == 'quarterly') {
                            $expire_date = Carbon::now()->addMonth(3);
                            $duration = 3;
                        }
                        elseif ($order->price_cycle == 'monthly') {
                            $expire_date = Carbon::now()->addMonth(1);
                            $duration = 1;
                        }
                        $order->expire_date = $expire_date;
                        $order->save();
                        $data = $order;
                        $order->plans; 
                        $order->users;
                        
                        Mail::to('wegotores@gmail.com')->send(new SubscriptionEmail($data));
                    }
                }
                //here we checked that the success payment is true and we updated the data base and empty the cart and redirct the customer to thankyou page

                 $approvedOrder =  $this->order_success($payment);
                $approvedOrder;
                   $redirectUrl = 'https://web.wegostores.com/dashboard_user/cart';
                   $message = 'Your payment is being processed. Please wait...';
                   $timer = 3; // 3  seconds
                $totalAmount = $data['amount_cents'];
              return  view('paymob.checkout', compact('payment','totalAmount','message','redirectUrl','timer'));
            //    return redirect()->away($redirectUrl . '?' . http_build_query([
            //    'success' => true,
            //    'payment_id' => $payment_id,
            //    'total_amount' => $totalAmount,
            //    "alert('payment Success')"
            //    ]));
               
            } else {
                $payment_id = $data['order'];
                $payment =  $this->payment->with('orders','orders.plans','orders.extra','orders.domain')->where('transaction_id', $payment_id)->first();

                $payment->update([
                    'payment_id' => $data['id'],
                    'payment_status' => "Failed"
                ]);


                return response()->json(['message' => 'Payment faield Check Your Credit']);
            }
        } else {
            return response()->json(['message' => 'Something Went Wrong Please Try Again']);
        }
    }
}
