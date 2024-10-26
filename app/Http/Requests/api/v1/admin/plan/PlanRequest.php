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
        'title'=>['required'],
        'image'=>['required'],
        'limet_store'=>['required'],
        'duration'=>['required'],
        'description'=>['required'],
        'setup_fees'=>['required'],
        'price_per_month'=>['required'],
        'price_per_year'=>['required'],
        ];
    }


    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'message'=>'Something Wrong',
            'error'=>$validator->errors()
        ]));
    } 
}
