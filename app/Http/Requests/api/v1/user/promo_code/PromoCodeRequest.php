<?php

namespace App\Http\Requests\api\v1\user\promo_code;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

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
            'code' => 'required',
            'plan.*.plan_id' => 'required|exists:plans,id',
            'plan.*.duration' => 'required|in:quarterly,semi-annual,yearly,monthly',
            'extra.*.extra_id' => 'required|exists:extras,id',
            'extra.*.duration' => 'required|in:quarterly,semi-annual,yearly,monthly',
            'domains.*' => 'exists:domains,id',
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'message'=>'Something Wrong',
            'error'=>$validator->errors(),
        ], 400));
    }
}
