<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KompetensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $kompetensi = $this->route('kompetensi');

        return [
            'kode_kompetensi' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kompetensis')->ignore($kompetensi ? $kompetensi->id : null),
            ],
            'nama_kompetensi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_kompetensi.required' => 'Kode kompetensi wajib diisi.',
            'kode_kompetensi.unique' => 'Kode kompetensi sudah terdaftar.',
            'nama_kompetensi.required' => 'Nama kompetensi wajib diisi.',
        ];
    }
}
