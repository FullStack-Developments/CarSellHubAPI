<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSettingRequest extends FormRequest
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
            'website_name' => ['required' ,'string'],
            'website_icon' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'website_logo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'facebook_url' => ['nullable', 'url'],
            'twitter_url' => ['nullable', 'url'],
            'linkedin_url' => ['nullable', 'url'],
            'instagram_url' => ['nullable', 'url'],
            'whatsapp_url' => ['nullable', 'url'],
            'contact_email' => ['required', 'email'],
            'contact_phone' => ['required', 'digits:10', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'intro_images' => ['required', 'array'],
            'intro_images.*' => ['image','mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'intro_keywords' => ['nullable', 'array'],
            'intro_keywords.*' => ['nullable', 'string', 'max:255'],
            'site_description' => ['required'],
            'language' => ['string', Rule::in(['en','ar'])],
        ];
    }
}
