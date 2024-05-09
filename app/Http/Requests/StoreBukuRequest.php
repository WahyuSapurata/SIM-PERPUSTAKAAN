<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
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
            'uuid_kategori' => 'required',
            'judul' => 'required',
            'sinopsis' => 'required',
            'pengarang' => 'required',
            'tahun_terbit' => 'required',
            'penerbit' => 'required',
            'lokasi' => 'required',
            'stok' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'uuid_kategori.required' => 'Kolom nama kategori harus di isi.',
            'judul.required' => 'Kolom judul harus di isi.',
            'sinopsis.required' => 'Kolom sinopsis harus di isi.',
            'pengarang.required' => 'Kolom pengarang harus di isi.',
            'tahun_terbit.required' => 'Kolom tahun terbit harus di isi.',
            'penerbit.required' => 'Kolom penerbit harus di isi.',
            'lokasi.required' => 'Kolom lokasi harus di isi.',
            'stok.required' => 'Kolom stok harus di isi.',
        ];
    }
}
