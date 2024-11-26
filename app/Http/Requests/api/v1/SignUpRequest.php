<?php

namespace App\Http\Requests\api\v1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SignUpRequest extends FormRequest
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
            //
              // This Reqeust About Sign Up User
              "name"=>['required'],
              "email"=>['required','unique:users'],
              "password"=>['required'],
              "conf_password"=>['required','same:password'],
              "phone"=>['required','unique:users','min:11'],
              "role"=>['nullable'],
              "requestDemo"=>['nullable'],
            //   'address' => ['required']
        ];
        
    }

   

     public function failedValidation(Validator $validator){
     throw new HttpResponseException(response:response()->json([
     'message'=>'Something Wrong',
     'signUp.errors'=>$validator->errors(),
     ],400));
     }
}
