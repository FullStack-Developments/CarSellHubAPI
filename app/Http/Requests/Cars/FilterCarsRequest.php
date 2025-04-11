<?php

namespace App\Http\Requests\Cars;

use App\Models\Car;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterCarsRequest extends FormRequest
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
            'brand' => ['nullable', 'string'],
            'model' => ['nullable', 'string'],
            'color' => ['nullable', 'string'],
            'year' => ['nullable', 'integer', "between : 1999, ". date('Y')],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'max:gte:price_min'],
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'is_new' => 'nullable|boolean',
            'is_sold' => 'nullable|boolean',
        ];
    }
}
