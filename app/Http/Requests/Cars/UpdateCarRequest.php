<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand' => ['nullable', 'string'],
            'model' => ['nullable', 'string'],
            'color' => ['nullable', 'string'],
            'year' => ['nullable', 'integer', "between:1999, " . date('Y')],
            'price' => ['nullable', 'numeric'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'is_new' => ['boolean'],
            'is_sold' => ['boolean'],
            'description' => ['string'],
            'first_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'second_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'third_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']

        ];
    }
}
