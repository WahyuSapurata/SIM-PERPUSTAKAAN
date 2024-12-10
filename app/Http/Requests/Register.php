<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
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
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'jurusan' => 'required',
            'password' => 'nullable|min:8',
            'password_confirmation' => 'nullable|same:password',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Kolom nama harus di isi.',
            'username.required' => 'Kolom nim harus di isi.',
            'email.required' => 'Kolom email harus di isi.',
            'jurusan.required' => 'Kolom jurusan harus di isi.',
            'password.min' => 'Password harus 8 karakter',
            'password_confirmation.same' => 'Ulangi password tidak sama dengan password',
        ];
    }
}
