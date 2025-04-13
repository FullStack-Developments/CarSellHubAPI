<?php

namespace App\Http\Requests\Ads;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterAdsRequest extends FormRequest
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
            'name' => ['nullable', 'string'],
            'min_hits' => ['nullable', 'integer', 'min:0'],
            'max_hits' => ['nullable', 'integer', 'min:0'],
            'min_views' => ['nullable', 'integer'],
            'max_views' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', Rule::in(['pending', 'approved', 'rejected'])],
        ];
    }
}
