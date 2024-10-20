<?php

namespace App\Http\Controllers\api\v1\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\auth\AuthRequst;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // This Is About any User Need Login 
    public function __construct(private User $user){}

    public function auth(AuthRequst $request):JsonResponse{
    // URL : http://localhost/wegostore/public/api/v1/auth/login
       $credentials =  $request->validated(); // Get Email & Password From Request Validate 
        $check = Auth::attempt($credentials);
        if($check){ // Start Check Credentials
             $user = $this->user->where('email',$credentials['email'])->first(); // Get Current User Login 
            $user->generateToken($user); // Genrate Token 
        //   Message Success
          return response()->json([
                'auth.success'=>'Login Successfully',
                'user'=>$user
            ]);
        //   Message Success
        } else {
            return response()->json(['message_auth'=>'Something Wrong In Your Credentials'],400);
        }
    }
}
