<?php

namespace App\Http\Controllers\api\v1\user\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\api\v1\user\subscription\PlanRequest;
use App\UploadImage;

use App\Models\Plan;
use App\Models\PaymentMethod;
use App\Models\Payment;

class SubscriptionController extends Controller
{
    public function __construct(private Plan $plans, private PaymentMethod $payment_methods,
    private Payment $payments){}
    protected $planRequest = [
        'plan_id',
        'payment_method_id',
    ];
    use UploadImage;

    public function plans(Request $request){
        // login.wegostores.com/user/v1/subscription
        $plans = $this->plans
        ->get();
        foreach ($plans as $item) {
            if ($request->user()->plan_id == $item->id) {
                $item->my_plan = true;
            } 
            else {
                $item->my_plan = false;
            }
        }
        
        return response()->json([
            'plans' => $plans
        ]);
    }

    public function payment_methods(){
        // login.wegostores.com/user/v1/subscription/payment_methods
        $payment_methods = $this->payment_methods
        ->get();

        return response()->json([
            'payment_methods' => $payment_methods
        ]);
    }

    public function buy_plan(PlanRequest $request){
        // login.wegostores.com/user/v1/subscription/buy_plan
        $planRequest = $request->only($this->planRequest);
        if (is_file($request->invoice_image)) {
            $image_path = $this->imageUpload($request, 'invoice_image', 'user/payment/invoice_image');
            $planRequest['invoice_image'] = $image_path;
        }
        $planRequest['user_id'] = $request->user()->id;
        $this->payments
        ->create($planRequest);

        return response()->json([
            'success' => 'You buy plan success'
        ]);
    }
}