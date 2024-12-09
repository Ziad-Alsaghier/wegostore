<?php

namespace App\Http\Requests\api\v1\admin\payment;

use Illuminate\Foundation\Http\FormRequest;

class ApprovePaymentRequest extends FormRequest
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
             // This Is About Request To Approved The Payment
             'payment_id'=>'sometimes',
             'store_id'=>'sometimes',
             'link_cbanal'=>'required_if:status,done',
             'link_store'=>'required_if:status,done',
             'email'=>'required_if:status,done',
             'password'=>'required_if:status,done',
             'status'=>'required',
            
        ];
    }
}
