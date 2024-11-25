<?php

namespace App\Http\Controllers\api\v1\admin\activity;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected $activityRequest = ['name'];
    // This Is About All Activities
    public function __construct(private Activity $activity){}

    public function view(){
        $activity = $this->activity
        ->get();

        return response()->json([
            'activity' => $activity
        ]);
    }
    
    public function store(Request $request){
        $newActivity = $request->only($this->activityRequest);
       try {
        //code...
        $createActivity =$this->activity->create($newActivity);
        response()->json([
        'activity.message'=>'Activity Created Successfully'
        ]);
       } catch (\Throwable $th) {
            response()->json([
            'activity.message'=>'Activity Created Successfully'
            ]);
       }
      
    }
}
