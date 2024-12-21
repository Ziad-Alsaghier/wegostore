<?php

namespace App\Http\Controllers\api\v1\user\subscription;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
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
        $locale = $request->query('locale', app()->getLocale()); // Get Local Translation
        $plans = $this->plans
        ->withLocale($locale)
        ->get(); // Get Plan With Translation by Local 

        foreach ($plans as $item) {
            if ($request->user()->plan_id == $item->id && $request->user()->expire_date >= date('Y-m-d')) {
                $item->my_plan = true;
                $item->package = $request->user()->package;
            } 
            else {
                $item->my_plan = false;
            }
        }
        $plans = PlanResource::collection($plans);
        
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
        // Keys
        // invoice_image, plan_id, payment_method_id
        $planRequest = $request->only($this->planRequest); 
        $image_path = $this->imageUpload($request, 'invoice_image', 'user/payment/invoice_image');
        $planRequest['invoice_image'] = $image_path;
        
        $planRequest['user_id'] = $request->user()->id;
        $this->payments
        ->create($planRequest);

        return response()->json([
            'success' => 'You buy plan success'
        ]);
    }
}
