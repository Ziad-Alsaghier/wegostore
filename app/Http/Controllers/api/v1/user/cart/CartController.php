<?php

namespace App\Http\Controllers\api\v1\user\cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use App\UploadImage;

use App\Models\Payment;
use App\Models\Order;

class CartController extends Controller
{
    public function __construct(private Payment $payments, private Order $orders){}
    protected $paymentRequest = [
        'payment_method_id'
    ];
    use UploadImage;

    public function payment(Request $request){
        // Keys
        // domain_id[], plan_id[], extra_id[], 
        // payment_method_id, invoice_image

        $validator = Validator::make($request->all(), [
            'domain_id.*' => 'exists:domains,id',
            'plan_id.*' => 'exists:plans,id',
            'extra_id.*' => 'exists:extras,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }

        $paymentRequest = $request->only($this->paymentRequest);
        $paymentRequest['user_id'] = $request->user()->id;
        $paymentRequest['status'] = 'pending';
        if ($request->invoice_image) {
            $invoice_image = $this->imageUpload($request, 'invoice_image', 'user/payment/invoice_image');
            $paymentRequest['invoice_image'] = $invoice_image;
        }
        $payment = $this->payments
        ->create($paymentRequest);

        if ($request->domain_id) {
            foreach ($request->domain_id as $id) {
                $this->orders
                ->create([
                    'user_id' => $request->user()->id,
                    'domain_id' => $id,
                    'payment_id' => $payment->id,
                ]);
            }
        }
        if ($request->plan_id) {
            foreach ($request->plan_id as $id) {
                $this->orders
                ->create([
                    'user_id' => $request->user()->id,
                    'plan_id' => $id,
                    'payment_id' => $payment->id,
                ]);
            }
        }
        if ($request->extra_id) {
            foreach ($request->extra_id as $id) {
                $this->orders
                ->create([
                    'user_id' => $request->user()->id,
                    'extra_id' => $id,
                    'payment_id' => $payment->id,
                ]);
            }
        }

        return response()->json([
            'success' => 'You send cart success'
        ]);
    }
}
