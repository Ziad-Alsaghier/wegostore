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
use App\service\order\placeOrder;
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
                $code = rand(10000, 99999);
                Mail::to($request->email)->send(new SignupCodeMail($code));
                $signUpData['code'] = $code;
                $signUpData['status'] = 0;
                $user = $this->user->create($signUpData); // Create New User
            } catch (\Throwable $th) {
                throw new HttpResponseException(response()->json(['signUp.message' => 'Something Wrong In Sign-up'], 500));
            }
            // $user =  $user->generateToken($user); // Start Genrate Token and Return User Sign up
            Mail::to('wegotores@gmail.com')->send(new SignupMail($user));

            return response()->json([
                'signup.message'=>'Sign-up Successfully and Payment processing Successfully',
                
            ],200);
        }
        
        public function code(Request $request){
            // Keys
            // code, email 
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'email' => 'required|email',
            ]);
            if ($validator->fails()) { // if Validate Make Error Return Message Error
                return response()->json([
                    'error' => $validator->errors(),
                ],400);
            }
            $user = $this->user
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->first();
            if (empty($user)) {
                return response()->json(['faild' => 'Code is wrong'], 400);
            }
            $user->update([
                'status' => 1
            ]);
            $user = $user->generateToken($user);

            return response()->json([
                'success' => 'You active account success',
                'user' => $user,
            ]);
        }

   
        public function resend_code(Request $request ){
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
