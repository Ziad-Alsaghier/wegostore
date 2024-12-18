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
    protected $paymentRequest = [
        'name',
        'description',
        'paymentMethod_id',
        'status',
    ];
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

            $paymentMethodRequest = $request->only($this->paymentRequest); // Get Array Of Reqeust Secure 
            $paymentMethod_id = $paymentMethodRequest['paymentMethod_id']; // Get paymentMethod_id Request
            $paymentMethod = $this->paymentMethod->where('id', $paymentMethod_id)->first(); // Get paymentMethod Need Updating
            if (!is_string($request->thumbnail)) {
                $image = $this->imageUpdate($request, $paymentMethod,'thumbnail','admin/paymentMethod');
                $paymentMethodRequest['thumbnail'] = $image;
            }
            $paymentMethod->update($paymentMethodRequest);
            return response()->json([
                  'message'=>'paymentMethod Updated Successfully',
                  'plan.update'=> $paymentMethod
            ]);
           
      }

      public function destroy(int $paymentMethod_id){
                URL : http://localhost/wegostore/public/admin/v1/payment/method/delete;
        try {
            $paymentMethod = $this->paymentMethod
            ->where('name', '!=', 'paymob')
            ->find($paymentMethod_id);
            if (empty($paymentMethod)) {
                return response()->json([
                    'faild' => "You can't delete it"
                ], 400);
            }
            $paymentMethod->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'paymentMethod.error'=>'This Payment Method Not Found',
                'message'=>$th->getMessage(),
            ],status:404);
        }
        return response()->json(['payment.success']);

      }
}
