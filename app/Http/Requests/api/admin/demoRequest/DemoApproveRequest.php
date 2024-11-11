<?php

namespace App\Http\Requests\api\admin\demoRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DemoApproveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // This Request about All Demo Request
              'email'=>['required','email','unique:user_demo_requests,email'],
              'demo_link'=>['required','unique:user_demo_requests,demo_link'],
              'password'=>['required'],
              'start_date'=>['required'],
              'end_date'=>['required'],
              'status'=>['sometimes'],
        ];
    }


     public function failedValidation(Validator $validator){
     throw new HttpResponseException(response()->json([
     'message'=>'Something Wrong',
     'demoRequest.errors'=>$validator->errors(),
     ],400));
     }
}
