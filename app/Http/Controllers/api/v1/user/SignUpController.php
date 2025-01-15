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
use App\services\order\placeOrder;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupMail;
use App\Mail\SignupCodeMail; 
use Illuminate\Support\Facades\Validator;

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
                $signUpData['status'] = 1;
                $user = $this->user->create($signUpData); // Create New User
                $user = $user->generateToken($user);
            } catch (\Throwable $th) {
                throw new HttpResponseException(response()->json(['signUp.message' => 'Something Wrong In Sign-up'], 500));
            }
            // $user =  $user->generateToken($user); // Start Genrate Token and Return User Sign up
            Mail::to('wegotores@gmail.com')->send(new SignupMail($user));


            return response()->json([
                'success' => 'You active account success',
                'user' => $user,
            ]);
        }
        
        public function code(Request $request){
            // signUp/code
            $validator = Validator::make($request->all(), [ 
                'email' => 'required|email|unique:users,id',
            ]);
            if ($validator->fails()) { // if Validate Make Error Return Message Error
                return response()->json([
                    'error' => $validator->errors(),
                ],400);
            }
            $code = rand(10000, 99999);
            Mail::to($request->email)->send(new SignupCodeMail($code));

            return response()->json([
                'code' => $code
            ]);
        }
   
        public function resend_code(Request $request ){
            // signUp/resend_code
            // Keys
            // email 
            $validator = Validator::make($request->all(), [ 
                'email' => 'required|email|exists:users,email',
            ]);
            if ($validator->fails()) { // if Validate Make Error Return Message Error
                return response()->json([
                    'error' => $validator->errors(),
                ],400);
            }
            $user = $this->user
            ->where('email', $request->email)
            ->first();
            $code = rand(10000, 99999);
            Mail::to($user->email)->send(new SignupCodeMail($code));
            $user->update([
                'code' => $code
            ]);
            return response()->json([
                'success'=>'New Code send to your email success',
                
            ],200);
        }
}
