<?php

namespace App\Http\Requests\api\v1\admin\extra;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExtraUpdateRequest extends FormRequest
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
              // This Request About Make Request For Extra Model
              'name'=>['sometimes'],
              'price'=>['sometimes'],
              'description'=>['sometimes'],
              'status'=>['sometimes'],
              'yearly'=>['sometimes'],
              'setup_fees'=>['sometimes'],
              'monthly'=>['sometimes'],
              'quarterly' => ['numeric', 'nullable'],
              'semi_annual' => ['numeric', 'nullable'],
              'discount_monthly' => ['numeric', 'nullable'],
              'discount_quarterly' => ['numeric', 'nullable'],
              'discount_semi_annual' => ['numeric', 'nullable'],
              'discount_yearly' => ['numeric', 'nullable'],
              'translations' => 'sometimes|array',
              'translations.*.locale' => 'sometimes|string|max:2', // e.g., 'en', 'ar'
              'translations.*.key' => 'sometimes|string', // e.g., 'name', 'description'
              'translations.*.value' => 'sometimes|string',
           
        ];
    }
  protected function failedValidation(Validator $validator){
        new HttpResponseException(response()->json([
            'extra.error'=>'Something Wrong',
            'message'=>$validator->errors(),
        ],400));
    }
}
