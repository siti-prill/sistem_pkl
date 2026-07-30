<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $siswa = $this->route('siswa'); // Ambil model siswa dari route binding
        $userId = $siswa ? $siswa->user_id : null;

        return [
            'nama_siswa' => 'required|string|max:255',
            'nis' => [
                'required',
                'string',
                'max:50',
                Rule::unique('siswas')->ignore($siswa ? $siswa->id : null),
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users')->ignore($userId),
            ],
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
            'jurusan.required' => 'Jurusan wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ];
    }
}
