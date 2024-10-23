<?php

namespace App\Http\Controllers\api\v1\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\vi\user\SignUpReqeust;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\StoreUser;
use App\Models\User;
use App\order\placeOrder;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SignUpController extends Controller
{

    // This Controller About Signup User
    use placeOrder;
    public function __construct(
        private User $user,
        private Payment $payment,
        private PaymentMethod $paymentMethod,
        private Plan $plan,
        private StoreUser $storeUser
        ){}


   
        public function signUp(SignUpReqeust $request ){
            
                $signUpData = $request->validated() ; // Get array About Requests 
                $user = $this->user->create($signUpData); // Create New User
                $user =  $user->generateToken($user); // Start Genrate Token and Return User Sign up
                
               return $request->requestDemo == false ? $placeOrder = $this->placeOrder($request,$user) : false;
                 

            return response()->json($placeOrder);
        }
        
}
