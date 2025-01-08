<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurcahseSparePartRequest extends FormRequest
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
            'category' => ['required', 'array', 'min:1'],
            'category.*' => ['required', 'integer'],

            'item' => ['required', 'array', 'min:1'],
            'item.*' => ['required', 'integer', 'exists:spare_parts,id'],

            'quantity' => ['required', 'array', 'min:1'],
            'quantity.*' => ['required', 'integer', 'min:1'],

            'price' => ['required', 'array', 'min:1'],
            'price.*' => ['required', 'numeric', 'min:0'],

            'total_price' => ['required', 'array', 'min:1'],
            'total_price.*' => ['required', 'numeric', 'min:0'],

            'total_amount' => ['required', 'numeric', 'min:0'],

            'payment_id' => 'required|numeric|exists:payments,id',

        ];
    }

    public function messages()
    {
        return [
            'category.required' => 'The category field is required.',
            'category.array' => 'The category field must be an array.',
            'category.min' => 'At least one category is required.',
            'category.*.required' => 'Each category must be selected.',
            'category.*.integer' => 'Each category must be a valid integer.',

            'item.required' => 'The item field is required.',
            'item.array' => 'The item field must be an array.',
            'item.min' => 'At least one item is required.',
            'item.*.required' => 'Each item must be selected.',
            'item.*.integer' => 'Each item must be a valid integer.',
            'item.*.exists' => 'The selected item is invalid.',

            'quantity.required' => 'The quantity field is required.',
            'quantity.array' => 'The quantity field must be an array.',
            'quantity.min' => 'At least one quantity is required.',
            'quantity.*.required' => 'Each quantity must be specified.',
            'quantity.*.integer' => 'Each quantity must be a valid integer.',
            'quantity.*.min' => 'Each quantity must be at least 1.',

            'price.required' => 'The price field is required.',
            'price.array' => 'The price field must be an array.',
            'price.min' => 'At least one price is required.',
            'price.*.required' => 'Each price must be specified.',
            'price.*.numeric' => 'Each price must be a valid number.',
            'price.*.min' => 'Each price must be greater than or equal to 0.',

            'total_price.required' => 'The total price field is required.',
            'total_price.array' => 'The total price field must be an array.',
            'total_price.min' => 'At least one total price is required.',
            'total_price.*.required' => 'Each total price must be specified.',
            'total_price.*.numeric' => 'Each total price must be a valid number.',
            'total_price.*.min' => 'Each total price must be greater than or equal to 0.',

            'total_amount.required' => 'The total amount field is required.',
            'total_amount.numeric' => 'The total amount must be a valid number.',
            'total_amount.min' => 'The total amount must be greater than or equal to 0.',

            'payment_id.required' => 'The payment has not been completed yet.',
        ];

    }
}
