<?php

namespace App\Http\Requests\api\v1\admin\tutorial;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class TutorialRequest extends FormRequest
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
            'title' => ['required'],
            'video' => ['required'],
            'tutorial_group_id' => ['required', 'exists:tutorial_groups,id'],
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string|max:2', // e.g., 'en', 'ar'
            'translations.*.key' => 'required|string', // e.g., 'name', 'description'
            'translations.*.value' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'tutorial.message' => 'Something Wrong',
            'error' => $validator->errors(),
        ], 400));
    }
}
