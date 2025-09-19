<?php

namespace App\Http\Requests\FiscalYear;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Maintain\ConfigController;

class UpdateFiscalYearRequest extends FormRequest
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
            'fy_title' => ['required', 'max:255', Rule::unique('fiscal_years')->ignore($this->fiscalYear->id)],
            'fy_start_date' => ['required', 'date'],
            'fy_end_date' => ['required', 'date'],
            // 'is_active' => ['required', 'in:fyActive,fyInActive']
        ];
    }
}
