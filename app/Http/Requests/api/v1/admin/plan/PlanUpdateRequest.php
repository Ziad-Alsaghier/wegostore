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
            'title'=>['required'],
            'image'=>['nullable'],
            'limet_store'=>['required'],
            'app'=>['required'],
            'description'=>['required'],
            'setup_fees'=>['required'],
            'price_per_month'=>['required'],
            'price_per_year'=>['required'],
        ];
    }

    protected function failedValidation(Validator $validator){
      throw  new HttpResponseException(response()->json([
            'error'=>'Something Wrong',
            'message'=>$validator->errors()
        ]));
    }
}
