<?php

namespace App\Http\Requests\Accounts;

use App\Enum\VoucherStatus;
use App\Enum\VoucherType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class storeDrVoucherRequest extends FormRequest
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
            'trn_date' => 'required|date',
            'trn_type' => ['in:Pending, Approved, Rejected'],
            'payee' => 'required|string',
            'messer' => 'required|string',
            'credit_account' => 'required',
            'description' => 'required|string|max:255',
            'trn_amount' => 'required|numeric',
            'particular' => 'required|array',
            'particular.*' => 'required',
            'controlling_account_id' => 'required|array',
            'controlling_account_id.*' => 'required|numeric',
            'dr_amount' => 'required|array',
            'dr_amount.*' => 'required|digits_between:1,15',
//            'cr_amount' => 'required|numeric',
//            'fiscal_year_id' => 'required',
//            'user_id' => 'required',
//            'transactions_id' => 'required',

        ];
    }
}
