<?php

namespace App\Http\Controllers\api\v1\user\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\api\v1\user\subscription\PlanRequest;

use App\Models\Plan;
use App\Models\PaymentMethod;

class SubscriptionController extends Controller
{
    public function __construct(private Plan $plans, private PaymentMethod $payment_methods){}

    public function plans(Request $request){
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
        $payment_methods = $this->payment_methods
        ->get();

        return response()->json([
            'payment_methods' => $payment_methods
        ]);
    }

    public function buy_plan(PlanRequest $request){

    }
}
