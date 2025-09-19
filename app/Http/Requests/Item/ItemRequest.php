<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'part_no' => 'required|string|max:50',
            'description' => '',
            'unit' => 'required|string|max:15',
            'item_category_id' => 'required',
            'purchase_price' => 'digits_between:1,15',
            'sale_price' => 'digits_between:1,15',
            'status' => 'boolean',
            'image_files.*' => 'mimes:pdf,docx,doc,jpg,jpeg,png',
        ];
    }
}
