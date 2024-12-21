<?php

namespace App\Http\Requests\api\v1\admin\plan;

use Http;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PlanUpdateRequest extends FormRequest
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
            // This Is Rule For Name Request Update Plan
            'plan_id'=>['required'],
            'name'=>['required'],
            'fixed' => ['in:0,1'],
            'title'=>['sometimes'],
            'image'=>['nullable'],
            'limet_store'=>['required'],
            'app'=>['required'],
            'description'=>['required'],
            'setup_fees'=>['required'],
            'monthly'=>['required'],
            'yearly'=>['required'],
            'quarterly'=>['required', 'numeric'],
            'semi_annual'=>['required', 'numeric'],
            'discount_monthly'=>['numeric', 'nullable'],
            'discount_quarterly'=>['numeric', 'nullable'],
            'discount_semi_annual'=>['numeric', 'nullable'],
            'discount_yearly' => ['numeric', 'nullable'],
            'translations' => 'sometimes|array',
            'translations.*.locale' => 'sometimes|string|max:2', // e.g., 'en', 'ar'
            'translations.*.key' => 'sometimes|string', // e.g., 'name', 'description'
            'translations.*.value' => 'sometimes|string',
            'translations.*.id' => 'sometimes|integer',
        ];
    }

    protected function failedValidation(Validator $validator){
      throw  new HttpResponseException(response()->json([
            'error'=>'Something Wrong',
            'message'=>$validator->errors()
        ]));
    }
}
