<?php

namespace App\Http\Controllers\api\v1\admin\demoRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\admin\demoRequest\DemoApproveRequest;
use App\Models\User;
use App\Models\UserDemoRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class DemoRequestController extends Controller
{
    // This About All Of Demo Request
    public function __construct(private UserDemoRequest $userDemoRequest) {}
    protected $requestDemoRequest = ['activity_id', 'user_id'];
    public function view()
    {
        try {
            $demoRequest = $this->userDemoRequest->get();
            !empty($demoRequest) ? $demoRequest : $demoRequest = "Not Found any Demo Requst";
            return response()->json([
                'demoRequest.message'=>'data returned Successfully',
                'demoRequest'=>$demoRequest
            ],404);
        } catch (\Throwable $th) {
            new HttpResponseException(response()->json(['error' => 'Not Found any Demo Request']));
        }
    }
    public function store(Request $request)
    {
        try {
            $user = $request->user();
            $user_id = $user->id;
            $newUserDemoRequest = $request->only($this->requestDemoRequest);
            $newUserDemoRequest['user_id'] = $user_id;

            $demoRequest = $this->userDemoRequest->create($newUserDemoRequest);

            return response()->json([
                'demoRequest.message' => "Demo Request Created Successfuly For  " . $demoRequest->activity->name,
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

    public function approved(DemoApproveRequest $request, $id)
    {
        $newRequestDemo = array_merge(
            ['status' => '1'], // Default value
            $request->validated()
        );
        $demoReqeust_id = $id; // Get Demo Request ID
            try {
              $demoReqeust = $this->userDemoRequest->where('id', $demoReqeust_id)->where('status','0')->first();
             // Get Demo Request
            if(!$demoReqeust){
                 return response()->json([
                 'demoRequest.message' => "Not Fount any Demo Request",
                 ]);
            }
        $updateDemoRequest =  $demoReqeust->update($newRequestDemo);
            } catch (\Throwable $th) {
            return response()->json([
                'demoRequest.message'=>'Something Wrong',
                'demoRequest'=>$th->getMessage()
            ]);
            }

        return response()->json([
                'demoRequest.message' => "Demo Request Approved Successfuly",
                'demoRequest'=>$demoReqeust
        ]);
    }
}
