<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'brand' => ['required', 'string'],
            'model' => ['required', 'string'],
            'color' => ['required', 'string'],
            'manufacture_year' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'country' => ['required', 'string'],
            'city' => ['required', 'string'],
            'is_new' => ['boolean'],
            'is_sold' => ['boolean'],
            'description' => ['string'],
            'first_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'second_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'third_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ];
    }
}
