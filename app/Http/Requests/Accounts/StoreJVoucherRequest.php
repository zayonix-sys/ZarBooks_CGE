<?php

namespace App\Http\Requests\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class StoreJVoucherRequest extends FormRequest
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
            'payee' => 'string',
            'messer' => 'string',
            'description' => 'required|string|max:255',
            'controlling_account_id' => 'required|array',
            'controlling_account_id.*' => 'required|numeric|distinct',
            'particular' => 'required|array',
            'particular.*' => 'required',
            'dr_amount' => 'required|array',
            //'dr_amount.*' => 'numeric',
            'cr_amount' => 'required|array',
            //'cr_amount.*' => 'numeric',
            'dr_total' => 'required|numeric',
            'cr_total' => 'required|numeric|same:dr_total',

//            'fiscal_year_id' => 'required',
//            'user_id' => 'required',
//            'transactions_id' => 'required',

        ];
    }
}
