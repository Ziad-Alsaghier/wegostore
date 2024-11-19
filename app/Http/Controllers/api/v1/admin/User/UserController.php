<?php

namespace App\Http\Controllers\api\v1\admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\api\v1\admin\tutorial\TutorialRequest;

use App\Models\User;

class UserController extends Controller
{
    public function __construct(private User $user){}
    protected $userRequest = [
        'name',
        'email',
        'phone',
        'status',
    ];
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

    public function create(){

    }

    public function user_login($id){
        $user = $this->user
        ->where('id', $id)
        ->where('role', 'user')
        ->first();
        if (empty($user)) {
            return response()->json([
                'faild' => 'User not found'
            ], 404);
        }
        $user->generateToken($user);
        return response()->json([
            'success' => 'You login success',
            'user' => $user
        ]);
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
