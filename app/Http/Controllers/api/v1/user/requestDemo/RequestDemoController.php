<?php

namespace App\Http\Controllers\api\v1\user\requestDemo;

use App\Http\Controllers\Controller;
use App\Models\UserDemoRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestDemoController extends Controller
{
    // This Is About All Request Demo For User 
    public function __construct(private UserDemoRequest $userDemoRequest) {}
    protected $requestDemoRequest = ['activity_id'];
     public function store(Request $request)
    {
        url:https://login.wegostores.com/user/v1/demoRequest/create
        // Keys
        // activity_id, 
        $user = $request->user();
        $user_id = $user->id;
           $check  = $user->UserDemoRequest;
           if($check){
            return response()->json([
            'demoRequest.message'=>'This User Have Demo Request',
            ],422);
           }
        try {
            $newUserDemoRequest = $request->only($this->requestDemoRequest);
            $newUserDemoRequest['user_id'] =$user_id;
            $demoRequest = $user->UserDemoRequest()->create($newUserDemoRequest);

            return response()->json([
                'demoRequest.message' => "Demo Request Created Successfuly For  " . $demoRequest->activity->name . " Wait Admin To Approved Your Request...",
                'demoRequest' => $demoRequest,
                'activity' => $demoRequest->activity,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'demoRequest.message' => 'Something Wrong',
                'message' => $th->getMessage()
            ], 500);
        }
    }

        public function view()
    {
        $user = Auth::user();
                url : https://login.wegostores.com/user/v1/demoRequest/show
        try {
            $demoRequest = $user->UserDemoRequest;
            $data = empty($demoRequest) == Null ? $demoRequest : $demoRequest = "Not Found any Demo Requst";
            return response()->json([
                'demoRequest.message'=>'data returned Successfully',
                'demoRequest'=>$data
            ],200);
        } catch (\Throwable $th) {
            new HttpResponseException(response()->json(['error' => 'Not Found any Demo Request']));
        }
    }
}
