<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'email|unique:customers',
            'contact' => 'regex:/^[0-9+-]+$/',
            'address' => 'string',
            'cnic' => 'max:15|regex:/^[0-9-]+$/',
            'ntn' => 'max:10|regex:/^[0-9-]+$/',
            'strn' => 'max:18|regex:/^[0-9-]+$/',
            'parent_account_id' => 'required|numeric',
            'controlling_account_id' => 'numeric'
        ];
    }
}
