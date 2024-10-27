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

      public function __construct(private Plan $plan) {}
      use UploadImage;
      public function store(PlanRequest $request): JsonResponse
      {
            URL:
            http: //localhost/wegostore/public/admin/v1/plan/create ;
            $newPlan = $request->validated();
            try {
                  $image = $this->imageUpload($request, 'image', 'admin/plan');
                  $newPlan['image'] = $image;
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
            $planRequest = $request->validated(); // Get Array Of Reqeust Secure 
            $plan_id = $planRequest['plan_id']; // Get plan_id Request
            $plan = $this->plan->where('id', $plan_id)->first(); // Get Plan Need Updating
            if ($request->hasFile('image')) { // If Need Update Image
                  $imagePath = $plan->image; // Get Old Path Image
                 $deletOldImage =  $this->deleteImage($imagePath); // Delete Old Image
                  if($deletOldImage ){
                        $uploadImage = $this->imageUpload(request: $request, inputName: 'image', destinationPath: 'admin/plan');
                  }
            } 
           
      }
}
