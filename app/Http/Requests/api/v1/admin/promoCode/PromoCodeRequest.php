<?php

namespace App\Http\Requests\api\v1\admin\promoCode;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PromoCodeRequest extends FormRequest
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
            // This Request About Create Promo Code
            'code'=>["required"],
            'title'=>["required"],
            'calculation_method'=>["required"],
            'usage'=>["sometimes"],
            'user_usage'=>["sometimes"],
            'user_type'=>["required"],
            'quarterly'=>["required"],
            'semi-annual'=>["required"],
            'yearly'=>["required"],
            'monthly'=>["required"],
            'start_date'=>["required"],
            'end_date'=>["required"],
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
                'promoCode.message'=>'Something Wrong',
                'error'=>$validator->errors(),
        ],400));
    }
}
