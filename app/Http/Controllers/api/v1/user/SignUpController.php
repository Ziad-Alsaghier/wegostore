<?php

namespace App\Http\Controllers\api\v1\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\SignUpRequest;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\StoreUser;
use App\Models\User;
use App\order\placeOrder;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
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


   
        public function signUp(SignUpRequest $request ){
                $signUpData = $request->validated() ; // Get array About Requests 
                            try {
                                $user = $this->user->create($signUpData); // Create New User
                            } catch (\Throwable $th) {
                             throw new HttpResponseException(response()->json(['signUp.message' => 'Something Wrong In Sign-up'], 500));

                            }
                $user =  $user->generateToken($user); // Start Genrate Token and Return User Sign up
                $signUpData['requestDemo'] == false ? $placeOrder = $this->placeOrder($request,$user) : false;
                            if($placeOrder->status() !== 200 ){
                                return response(['placeOrder.message'=>'Sign-up Successfully Put Something Wrong In Place order']);
                            }
                return response()->json(['success'=>'Sign-Up Successfully'],200);
        }
        
}
