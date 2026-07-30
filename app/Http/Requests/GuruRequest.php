<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $guru = $this->route('guru'); // Ambil model guru dari route binding
        $userId = $guru ? $guru->user_id : null;

        return [
            'nama_guru' => 'required|string|max:255',
            'nip' => [
                'required',
                'string',
                'max:50',
                Rule::unique('gurus')->ignore($guru ? $guru->id : null),
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users')->ignore($userId),
            ],
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_guru.required' => 'Nama guru wajib diisi.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
            'password.min' => 'Password minimal 8 karakter.',
        ];
    }
}
