<?php

namespace App\Http\Controllers\api\v1\admin\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function __construct(private User $user){}

    public function view(){
        $users = $this->user
        ->where('role', 'user')
        ->get();

        return response()->json([
            'users' => $users
        ]);
    }

    public function login($id){
        $user = $this->user
        ->where('role', 'user')
        ->where('id', $id)
        ->first();
        if (empty($user)) {
            return response()->json([
                'faild' => 'Id is wrong'
            ], 400);
        }
        $user->generateToken($user);

        return response()->json([
            'user' => $user
        ]);
    }
}
