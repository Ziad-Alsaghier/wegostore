<?php

namespace App\Http\Controllers\api\v1\user\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Plan;

class SubscriptionController extends Controller
{
    public function __construct(private Plan $plans){}

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
}
