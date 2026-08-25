<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndustriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $industri = $this->route('industri');
        $isCreate = !$industri;

        return [
            'kode_perusahaan' => [
                'required',
                'string',
                'max:50',
                Rule::unique('industris')->ignore($industri ? $industri->id : null),
            ],
            'nama_perusahaan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'email_login' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($industri && $industri->user ? $industri->user->id : null),
            ],
            'password' => $isCreate
                ? 'required|string|min:8'
                : 'nullable|string|min:8',
            'password_confirmation' => $isCreate
                ? 'required|same:password'
                : 'nullable|same:password',
            'bidang_usaha' => 'required|string|max:255',
            'jurusan' => 'nullable|string|in:' . implode(',', \App\Models\Industri::JURUSAN_LIST),
            'penanggung_jawab' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'status' => 'required|in:aktif,tidak_aktif',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_perusahaan.required' => 'Kode perusahaan wajib diisi.',
            'kode_perusahaan.unique' => 'Kode perusahaan sudah terdaftar.',
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_telepon.required' => 'No telepon wajib diisi.',
            'email_login.required' => 'Email login wajib diisi.',
            'email_login.unique' => 'Email login sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok.',
            'bidang_usaha.required' => 'Bidang usaha wajib diisi.',
            'penanggung_jawab.required' => 'Penanggung jawab wajib diisi.',
            'kuota.required' => 'Kuota wajib diisi.',
            'kuota.min' => 'Kuota minimal 1.',
        ];
    }
}
