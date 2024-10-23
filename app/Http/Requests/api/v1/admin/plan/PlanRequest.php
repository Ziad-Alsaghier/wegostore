<?php

namespace App\Http\Requests\api\v1\admin\plan;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
        'fixed'=>['required'],
        'limet_store'=>['required'],
        'description'=>['required'],
        'setup_fees'=>['required'],
        'price_per_month'=>['required'],
        'price_per_year'=>['required'],
        ];
    }
}
