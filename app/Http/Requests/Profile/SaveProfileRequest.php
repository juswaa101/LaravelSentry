<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class SaveProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048', 'bail'],
            'password' => ['sometimes', 'nullable', 'min:8', 'bail'],
            'confirm_password' => ['sometimes', 'nullable', 'same:password', 'bail'],
        ];
    }
}
