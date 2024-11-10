<?php

namespace App\Http\Controllers\api\v1\admin\demoRequest;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDemoRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class DemoRequestController extends Controller
{
    // This About All Of Demo Request
public function __construct(private UserDemoRequest $userDemoRequest){}
    public function view(){
        try {
            $demoRequest = $this->userDemoRequest->get();
        } catch (\Throwable $th) {
            new HttpResponseException(response()->json(['error'=>'Not Found any Demo Request']));
        }
    }


    public function approved(Request $request,$id){
        $demoReqeust_id = $id; // Get Demo Request ID
        $demoReqeust = $this->userDemoRequest->where('id',$demoReqeust_id)->where('status',true); // Get Demo Request 


    } 
}
