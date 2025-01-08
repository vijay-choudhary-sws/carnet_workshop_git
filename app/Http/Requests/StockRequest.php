<?php

namespace App\Http\Requests;

use App\Rules\UniqueSparePartLabel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StockRequest extends FormRequest
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
            'name' => 'required',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:1',
            'category_id' => 'required|integer',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('message.Name is required.'),
            'category_id.required' => trans('message.Category is required.'),
            'price.required' => trans('message.Price is required.'),
            'stock.required' => trans('message.Stock is required.'),
        ];

    }
}
