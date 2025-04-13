<?php

namespace App\Http\Requests\Ads;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdRequest extends FormRequest
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
            'full_name' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'link' => ['required', 'url'],
            'location' => ['required', 'string'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after:start_date'
            ],
        ];
    }
}
