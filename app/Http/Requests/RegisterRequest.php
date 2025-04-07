<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:45', 'min:3'],
            'last_name' => ['required', 'string', 'max:45', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:45', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone_number' => ['required', 'digits:10', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'picture_profile' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'address' => ['string', 'max:255'],
        ];
    }

}
