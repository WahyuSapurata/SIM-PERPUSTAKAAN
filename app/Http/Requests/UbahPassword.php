<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UbahPassword extends FormRequest
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
            'password' => 'nullable|min:8',
            'password_confirmation' => 'nullable|same:password',
        ];
    }

    public function messages()
    {
        return [
            'password.min' => 'Password harus 8 karakter',
            'password_confirmation.same' => 'Ulangi password tidak sama dengan password',
        ];
    }
}
