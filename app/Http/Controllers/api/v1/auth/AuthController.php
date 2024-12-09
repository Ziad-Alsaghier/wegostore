<?php

namespace App\Http\Controllers\api\v1\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\auth\AuthRequst;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    // This Is About any User Need Login 
    public function __construct(private User $user){}

    public function auth(AuthRequst $request){
    // URL : http://localhost/wegostore/public/api/v1/auth/login
       $credentials =  $request->validated(); // Get Email & Password From Request Validate 
         $check = Auth::attempt($credentials);
        if($check && Auth::user()->status){ // Start Check Credentials
             $user = $this->user->where('email',$credentials['email'])->first(); // Get Current User Login 
            $user->generateToken($user); // Genrate Token 
            if (!empty($user->expire_date) && !empty($user->start_date)) {
                $date1 = Carbon::createFromFormat('Y-m-d', $user->expire_date);
                $date2 = Carbon::createFromFormat('Y-m-d', $user->start_date);
                $diff = $date1->diffInDays($date2); // Get the difference in days 
                if ($diff <= 31) {
                    $user->duration = '1';
                }
                elseif ($diff <= 100) {
                    $user->duration = '3';
                }
                elseif ($diff <= 200) {
                    $user->duration = '6';
                }
                else {
                    $user->duration = 'yearly';
                }
            }
            else{
                $user->duration = null;
            }
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
