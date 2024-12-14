<?php

namespace App\Http\Requests;

use App\Rules\UniqueSparePartLabel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccessoryRequest extends FormRequest
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
            'name' => ['required', new UniqueSparePartLabel],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'unit_id' => 'required|integer',
            'brand' => 'nullable',
            'suitable_for' => 'nullable',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'stock' => 'required|integer',
            'description' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('message.Name is required.'),
            'name.unique' => trans('message.Name must be unique.'),
            'image.required_without' => trans('message.Image is required.'),
            'unit_id.required' => trans('message.Unit is required.'),
            'brand.required' => trans('message.Suitable for is required.'),
            'suitable_for.required' => trans('message.Suitable for is required.'),
            'price.required' => trans('message.Price is required.'),
            'discount.required' => trans('message.Discount is required.'),
            'stock.required' => trans('message.Stock is required.'),
            'description.required' => trans('message.Decription is required.'),
        ];

    }
}
