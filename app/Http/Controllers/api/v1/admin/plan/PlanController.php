<?php

namespace App\Http\Controllers\api\v1\admin\plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\plan\PlanRequest;
use App\Models\Plan;
use App\UploadImage;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    // This Controller about Plan

      public function __construct(private Plan $plan){}
  use UploadImage;
      public function store(PlanRequest $request){
       URL : http://localhost/wegostore/public/admin/v1/plan/create ;
      $newPlan = $request->validated();
            try {
                  $image = $this->imageUpload($request,'image','admin/plan');
                  $newPlan['image'] = $image;
                 $createNewPlan = $this->plan->create($newPlan);
                 $createNewPlan->imageUrl = url($image);
            
                
                  return response()->json([
                        'message.success'=>'Plan Added Successfully',
                        'plan'=>$createNewPlan,
                            
                  ],200);

            } catch (\Throwable $th) {
                  return response()->json(['message.error'=>'Plan Process Creating Failed'],200);
            }
      }
    
}
