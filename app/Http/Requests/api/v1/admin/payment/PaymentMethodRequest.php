<?php

namespace App\Http\Requests\api\v1\admin\payment;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentMethodRequest extends FormRequest
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
            // This Is About Name Request To Create Payment Method
            'name'=>['required'],
            'description'=>['required'],
            'thumbnail'=>['required'],
            'status'=>['nullable'],
        ];
    }

    protected function failedValidation(Validator $validator){
       throw new HttpResponseException(response()->json([
            'create.message'=>'Something Wrong',
            'error'=>$validator->errors()
        ],400));
    }
}
