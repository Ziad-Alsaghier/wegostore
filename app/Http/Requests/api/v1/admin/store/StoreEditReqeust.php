<?php

namespace App\Http\Requests\api\v1\admin\store;

use Illuminate\Foundation\Http\FormRequest;

class StoreEditReqeust extends FormRequest
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
            //
        'link_cbanal'=>'sometimes',
        'store_name'=>'sometimes',
        'link_store'=>'sometimes',
        'email'=>'sometimes',
        'password'=>'sometimes',
        'status'=>'sometimes',
        ];
    }
}
