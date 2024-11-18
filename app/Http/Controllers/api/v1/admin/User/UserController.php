<?php

namespace App\Http\Controllers\api\v1\admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private User $user){}
    // This Controller About Users 

    public function view(){
        Url: http://localhost/wegostore/public/admin/v1/users/view
        try {
            $user = $this->user->get();
            return response()->json([
            'user.message'=>'Data Returned Successfully',
            'user'=>$user,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'user.message'=>'Something Wrong In User',
                'error'=>$th->getMessage()
            ]);
        }
    }
    public function subscription(){
        Url : http://localhost/wegostore/public/admin/v1/users/subscription
        try {
            $user = $this->user->whereNotNull('plan_id')->get();
            return response()->json([
            'user.message'=>'Data Returned Successfully',
            'user'=>$user,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'user.message'=>'Something Wrong In User',
                'error'=>$th->getMessage()
            ]);
        }
    }
}
