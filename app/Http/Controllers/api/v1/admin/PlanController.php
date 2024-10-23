<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\plan\PlanRequest;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    // This Is About Create,Read,Update,Delete The Plan
    public function __construct(private Plan $plan){}

    public function store(PlanRequest $request){
        $newPlan = $request->validated();
        $createNewPlan = $this->plan->create($newPlan);
        return response()->json(['message.success'=>'Plan Added Successfully'],200);
    }
}
