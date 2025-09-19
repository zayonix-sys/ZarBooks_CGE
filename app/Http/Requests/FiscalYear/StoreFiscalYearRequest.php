<?php

namespace App\Http\Requests\FiscalYear;

use Illuminate\Foundation\Http\FormRequest;

class StoreFiscalYearRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'fy_title' => 'required|unique:fiscal_years|max:255',
            'fy_start_date' => 'required|date',
            'fy_end_date' => 'required|date|after:fy_start_date'
        ];
    }
}
