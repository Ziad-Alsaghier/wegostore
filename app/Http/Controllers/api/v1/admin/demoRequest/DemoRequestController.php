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
                url : https://login.wegostores.com/admin/v1/demoRequest/show
        try {
            $demoRequest = $this->userDemoRequest->with('users','activity')->get();
            $data = empty($demoRequest) == Null ? $demoRequest : $demoRequest = "Not Found any Demo Requst";
            return response()->json([
                'demoRequest.message'=>'data returned Successfully',
                'demoRequest'=>$data
            ],200);
        } catch (\Throwable $th) {
            new HttpResponseException(response()->json(['error' => 'Not Found any Demo Request']));
        }
    }
   

    public function approved(DemoApproveRequest $request, $id)
    {
                url : https://login.wegostores.com/admin/v1/demoRequest/approved/{id}
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
