<?php

namespace App\Http\Requests\api\v1\admin\extra;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExtraRequest extends FormRequest
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
            'name'=>['required'],
            'price'=>['nullable', 'numeric'],
            'description'=>['required'],
            'status'=>['required', 'in:one_time,recurring'],
            'yearly'=>['nullable', 'numeric'],
            'setup_fees'=>['nullable', 'numeric'],
            'monthly'=>['nullable', 'numeric'],
            
            'quarterly' => ['numeric'],
            'semi-annual' => ['numeric'],
            'discount_monthly' => ['numeric'],
            'discount_quarterly' => ['numeric'],
            'discount_semi_annual' => ['numeric'],
            'discount_yearly' => ['numeric'],

        ];
    }


    protected function failedValidation(Validator $validator){
        new HttpResponseException(response()->json([
            'extra.error'=>'Something Wrong',
            'message'=>$validator->errors(),
        ],400));
    }
}
