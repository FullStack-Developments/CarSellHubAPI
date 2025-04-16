<?php

namespace App\Http\Requests\Reviews;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'car_id' => ['nullable','exists:cars,id'],
            'full_name' => ['required', 'string', 'max:45'],
            'phone_number' => ['required', 'digits:10', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'email' => ['required', 'email', 'max:45'],
            'subject' => ['required', 'string', 'max:45'],
            'comment' => ['required', 'string'],
        ];
    }
}
