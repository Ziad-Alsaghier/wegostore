<?php

namespace App\Http\Controllers\api\v1\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\SignUpRequest;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\Store;
use App\Models\StoreUser;
use App\Models\User;
use App\order\placeOrder;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupMail;

class SignUpController extends Controller
{

    // This Controller About Signup User
    use placeOrder;
    public function __construct(
        private User $user,
        private Payment $payment,
        private PaymentMethod $paymentMethod,
        private Plan $plan,
        private Store $storeUser
        ){}


   
        public function signUp(SignUpRequest $request ){
            $signUpData = $request->validated() ; // Get array About Requests 
            try {
                $signUpData['role'] = 'user';
                $user = $this->user->create($signUpData); // Create New User
            } catch (\Throwable $th) {
                throw new HttpResponseException(response()->json(['signUp.message' => 'Something Wrong In Sign-up'], 500));
            }
            $user =  $user->generateToken($user); // Start Genrate Token and Return User Sign up
            Mail::to('wegotores@gmail.com')->send(new SignupMail($user));
            return response()->json([
                'signup.message'=>'Sign-up Successfully and Payment processing Successfully',
                'user'=>$user
                
            ],200);
        }
        
}
