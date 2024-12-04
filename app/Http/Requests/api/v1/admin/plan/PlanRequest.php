<?php

namespace App\Http\Requests\api\v1\admin\plan;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PlanRequest extends FormRequest
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
            // This About Request For Plan
        'name'=>['required'],
        'title'=>['nullable'],
        'fixed' => ['in:0,1'],
        'image'=>['sometimes'],
        'limet_store'=>['required'],
        'app'=>['required', 'in:0,1'],
        'description'=>['required'],
        'setup_fees'=>['required'],
        'monthly'=>['required', 'numeric'],
        'yearly'=>['required', 'numeric'],
        'quarterly'=>['required', 'numeric'],
        'semi-annual'=>['required', 'numeric'],
        'discount_monthly'=>['numeric', 'nullable'],
        'discount_quarterly'=>['numeric', 'nullable'],
        'discount_semi_annual'=>['numeric', 'nullable'],
        'discount_yearly' => ['numeric', 'nullable'],
        ];
    }


    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'message'=>'Something Wrong',
            'error'=>$validator->errors()
        ],400));
    } 
}
