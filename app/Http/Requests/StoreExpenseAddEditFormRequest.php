<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseAddEditFormRequest extends FormRequest
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
            'category_id' => 'required',
            'supplier_id' => 'nullable',
            'bill_number' => 'required',
            'date' => 'required|date', 
            'total_amount' => 'required|numeric|min:1', 
            'paid_amount' => 'required|numeric', 
            'balance_amount' => 'required|numeric', 
            'description' => 'nullable|string', 
        ];

    }

    // public function messages()
    // {

    // }
}
