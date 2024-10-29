<?php

namespace App\Http\Controllers\api\v1\admin\payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\payment\PaymentMethodRequest;
use App\Http\Requests\api\v1\admin\payment\PaymentMethodUpdateRequest;
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
        $thumbnail = $this->imageUpload(request:$request,inputName:'thumbnail',destinationPath:'admin/paymentMethod');
        try {
            $NewPaymentMethod['thumbnail'] = $thumbnail;
            
            $createPaymentMethod = $this->paymentMethod->create($NewPaymentMethod);
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
        URL : http://localhost/wegostore/public/admin/v1/payment/method/show;
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

         public function modify(PaymentMethodUpdateRequest $request)
      {
                URL : http://localhost/wegostore/public/admin/v1/payment/method/update;

            $paymentMethodRequest = $request->validated(); // Get Array Of Reqeust Secure 
            $paymentMethod_id = $paymentMethodRequest['paymentMethod_id']; // Get paymentMethod_id Request
              $paymentMethod = $this->paymentMethod->where('id', $paymentMethod_id)->first(); // Get paymentMethod Need Updating
            $image = $this->imageUpdate($request, $paymentMethod,'thumbnail');
            $paymentMethodRequest['thumbnail'] = $image;
            $paymentMethod->update($paymentMethodRequest);
            return response()->json([
                  'message'=>'paymentMethod Updated Successfully',
                  'plan.update'=> $paymentMethod
            ]);
           
      }

      public function destroy(Request $paymentMethod_id){
                URL : http://localhost/wegostore/public/admin/v1/payment/method/delete;
        try {
            $paymentMethod = $this->paymentMethod->find($paymentMethod_id);
            $paymentMethod->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'paymentMethod.error'=>'Something Wrong',
                'message'=>$th,
            ]);
        }

      }
}
