<?php

namespace App\Http\Requests\api\v1\admin\payment;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentMethodUpdateRequest extends FormRequest
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
              'name'=>['required'],
              'description'=>['required'],
              'thumbnail'=>['required'],
              'paymentMethod_id'=>['required'],
              'status'=>['nullable'],
        ];
    }



    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
                'payment.error' => 'Something Wrong',
                'message'=>$validator->errors()
        ]));
    }
}
