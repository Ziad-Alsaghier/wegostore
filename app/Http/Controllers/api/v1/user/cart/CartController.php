<?php

namespace App\Http\Controllers\api\v1\user\cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use App\UploadImage;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use App\Mail\PaymentMail;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Extra;

class CartController extends Controller
{
    public function __construct(private Payment $payments, private Order $orders,
    private Extra $extra){}
    protected $paymentRequest = [
        'payment_method_id',
        'amount',
    ];
    use UploadImage;

    public function payment(Request $request){
        // cart
        // Keys
        // domain[{id, package, price_item}], plan[{id, package, price_item}], 
        // extra[{id, package, price_item}], 
        // payment_method_id, invoice_image, amount
        // package => [1, 3, 6, yearly]
        
        $validator = Validator::make($request->all(), [
            'domain.*.id' => 'exists:domains,id',
            'domain.*.package' => 'in:1,3,6,yearly',
            'domain.*.price_item' => 'numeric',
            'plan.*.id' => 'exists:plans,id',
            'plan.*.package' => 'in:1,3,6,yearly',
            'plan.*.price_item' => 'numeric',
            'extra.*.id' => 'exists:extras,id',
            'extra.*.package' => 'in:1,3,6,yearly|nullable',
            'extra.*.price_item' => 'numeric',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric',
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }
     
        $arr_package = [
            '1' => 'monthly',
            '3' => 'quarterly',
            '6' => 'semi_annual',
            'yearly' => 'yearly'
        ];

        $paymentRequest = $request->only($this->paymentRequest);
        $paymentRequest['user_id'] = $request->user()->id;
        $paymentRequest['status'] = 'pending';
        if ($request->invoice_image) {
            $invoice_image = $this->imageUpload($request, 'invoice_image', 'user/payment/invoice_image');
            $paymentRequest['invoice_image'] = $invoice_image;
        }
        $payment = $this->payments
        ->create($paymentRequest);

        if ($request->domain) {
            return $request->domain;
            foreach ($request->domain as $domain) {
                $this->orders
                ->create([
                    'user_id' => $request->user()->id,
                    'domain_id' => $domain['id'],
                    'payment_id' => $payment->id,
                    'price_item' => $domain['price_item'],
                ]);
                    // 'package' => $domain['package'] ?? null ignor package cause domain dont has 

            }
        }
         if ($request->extra) {
            //    foreach ($request->extra as $item) {
            //    $extra = $this->extra
            //    ->where('id', $item['id'])
            //    ->first();
            //    if (count($extra->plan_included) > 0) {
            //         $plan_user = $request->user()->plan;
            //    }
            //    }
            foreach ($request->extra as $extra) {
                $this->orders
                ->create([
                    'user_id' => $request->user()->id,
                    'extra_id' => $extra['id'],
                    'package' => $extra['package'] ?? null,
                    'payment_id' => $payment->id,
                    'price_item' => $extra['price_item'],
                    'price_cycle' => $arr_package[$extra['package']],
                ]);
            }
        }
        if ($request->plan) {
            foreach ($request->plan as $plan) {
                $this->orders
                ->create([
                    'user_id' => $request->user()->id,
                    'plan_id' => $plan['id'],
                    'payment_id' => $payment->id,
                    'package' => $plan['package'] ?? null,
                    'price_item' => $plan['price_item'],
                    'price_cycle' => $arr_package[$plan['package']],
                ]);
            }
        }
       
        $data = $this->payments
        ->where('id', $payment->id)
        ->with(['orders' => function($query){
            $query->with(['plans', 'domain.store', 'extra']);
        }, 'payment_method', 'user'])
        ->first();
        Mail::to($request->user()->email)->send(new InvoiceMail($data));
        Mail::to('wegotores@gmail.com')->send(new PaymentMail($data));

        return response()->json([
            'success' => $request->all()
        ]);
    }
}
