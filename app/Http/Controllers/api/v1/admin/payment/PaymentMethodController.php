<?php

namespace App\Http\Controllers\api\v1\admin\payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\payment\PaymentMethodRequest;
use App\Models\PaymentMethod;
use App\UploadImage;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    // This Controller About All Payment Method Module
    public function __construct(private PaymentMethod $paymentMethod){}
    use UploadImage;
    public function store(PaymentMethodRequest $request){
        URL : http://localhost/wegostore/public/admin/v1/payment/method/create;
        $NewPaymentMethod = $request->validated();
        try {
            $thumbnail = $this->imageUpload($request,'thumbnail','admin/paymentMethod');
            $NewPaymentMethod['thumbnail'] = $thumbnail;
            $createPaymentMethod = $this->paymentMethod->create($NewPaymentMethod);
            $createPaymentMethod->imageUrl = url($thumbnail);
            return response()->json([
                'paymentMethod.message'=>'Payment Method created Successfully',
                'payment'=>$createPaymentMethod
            ],200);
        } catch (\Throwable $th) {
             throw new HttpResponseException(response()->json([
                    'error'=>'Something Wrong',
                    'message'=> $th,
                    ]));
        }
    }
    
        public function show():JsonResponse{

                try {
                            $paymentMethods = $this->paymentMethod->get();

                } catch (\Throwable $th) {
                        response()->json([
                            'error'=>'Something Wrong',
                            'message'=>$th
                        ]);
                }
        return response()->json([
            'paymeny.success'=>'Data Returned Successfully',
            'payment'=>$paymentMethods
        ]);
        }
}
