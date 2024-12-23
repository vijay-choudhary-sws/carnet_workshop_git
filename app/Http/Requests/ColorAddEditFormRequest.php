<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorAddEditFormRequest extends FormRequest
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
            'color' => 'required|max:50|regex:/^[(a-zA-Z0-9\s)\p{L}]+$/u',
            //'color' => 'required|max:50|regex:/^[a-zA-Z0-9_][a-zA-Z0-9_][a-zA-Z0-9_ ]*[a-zA-Z0-9_]$/',
        ];
    }

    public function messages()
    {
        return [
            'color.required' => trans('message.Color name is required.'),
            'color.max' => trans('message.Maximum 50 characters allowed.'),
            'color.regex' => trans('message.Special symbols are not allowed.'),
        ];

    }
}
