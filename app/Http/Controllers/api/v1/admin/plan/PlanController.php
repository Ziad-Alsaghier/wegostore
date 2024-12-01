<?php

namespace App\Http\Controllers\api\v1\admin\plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\plan\PlanRequest;
use App\Http\Requests\api\v1\admin\plan\PlanUpdateRequest;
use App\Models\Plan;
use App\UploadImage;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PlanController extends Controller
{
      // This Controller about Plan

      public function __construct(
            private Plan $plan,
            private Storage $storage,
            ) {}
      use UploadImage;
      public function store(PlanRequest $request): JsonResponse
      {
            URL: //localhost/wegostore/public/admin/v1/plan/create ;
            // Keys
            // name, title, fixed, image, limet_store, app, description,
            // setup_fees, monthly, yearly, quarterly,
            // semi-annual, discount_monthly, discount_quarterly, 
            // discount_semi_annual, discount_yearly
            $newPlan = $request->validated();
            try {
                  if ($request->image) {
                        $image = $this->imageUpload(request:$request, inputName:'image', destinationPath: 'admin/plan');
                        $newPlan['image'] = $image;
                  }
                  $createNewPlan = $this->plan->create($newPlan);
                  $createNewPlan->imageUrl = url($image);
                  return response()->json([
                        'message.success' => 'Plan Added Successfully',
                        'plan' => $createNewPlan,

                  ], 200);
            } catch (\Throwable $th) {
                  return response()->json(['message.error' => 'Plan Process Creating Failed'], 200);
            }
      }

      public function modify(PlanUpdateRequest $request)
      {
            URL : https://login.wegostores.com/admin/v1/plan/update
            // Keys
            // name, title, fixed, image, limet_store, app, description,
            // setup_fees, monthly, yearly, quarterly,
            // semi-annual, discount_monthly, discount_quarterly, 
            // discount_semi_annual, discount_yearly
            
            $planRequest = $request->validated(); // Get Array Of Reqeust Secure 
            $plan_id = $planRequest['plan_id']; // Get plan_id Request
             $plan = $this->plan->where('id', $plan_id)->first(); // Get Plan Need Updating
             if (!is_string($request->image)) {
                  $image = $this->imageUpdate($request, $plan,'image','admin/plan');
                  $planRequest['image'] = $image;
             }
            $plan->update($planRequest);
            return response()->json([
                  'message'=>'Plan Updated Successfully',
                  'plan.update'=> $plan
            ]);
      }

      public function show():JsonResponse{
            URL : https://login.wegostores.com/admin/v1/plan/show
                try {
                            $plan = $this->plan->get();
                } catch (\Throwable $th) {
                        response()->json([
                            'error'=>'Something Wrong',
                            'message'=>$th
                        ],status:500);
                }
                        return response()->json([
                              'plan.success'=>'Data Returned Successfully',
                              'plan'=>$plan
                        ],status:200);
        }
        
        
      public function destroy(int $plan_id){
            URL: https://login.wegostores.com/admin/v1/plan/delete
            try {
            $this->plan->where('id', $plan_id)
             ->delete();
                  return response()->json([
                        'plan.message'=>'Plan Deleted Successfully'
                  ],status:200);
        } catch (\Throwable $th) {
            return response()->json([
                'plan.error'=>'This Plan Not Found',
                'message'=>$th->getMessage(),
            ],status:404);
        }

      }
}
