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
        // activity
        $activity = $this->activity
        ->get();

        return response()->json([
            'activity' => $activity
        ]);
    }
    
    public function store(Request $request){
        // activity/add
        $newActivity = $request->only($this->activityRequest);
       try {
        //code...
        $createActivity =$this->activity->create($newActivity);
        return response()->json([
        'activity.message'=>'Activity Created Successfully'
        ]);
       } catch (\Throwable $th) {
        return response()->json([
            'activity.message'=>'Something wrong'
            ], 400);
       } 
    }

    public function modify(Request $request, $id){
        // activity/update/{id}
        $activityRequest = $request->only($this->activityRequest);
        try { 
         $this->activity
         ->where('id', $id)
         ->update($activityRequest);
         return response()->json([
         'activity.message'=>'Activity Updated Successfully'
         ]);
        } catch (\Throwable $th) {
            return response()->json([
             'activity.message'=>'Something wrong'
             ], 400);
        } 
    }

    public function delete($id){
        // activity/delete/{id}
        $this->activity
        ->where('id', $id)
        ->delete();

        return response()->json([
            'success' => 'You delete data success'
        ]);
    }
}
