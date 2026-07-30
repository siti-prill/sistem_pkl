<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenempatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|exists:industris,id',
            'guru_id' => 'required|exists:gurus,id',
            'kompetensi_id' => 'required|exists:kompetensis,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:aktif,selesai,batal',
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_id.required' => 'Siswa wajib dipilih.',
            'siswa_id.exists' => 'Siswa tidak valid.',
            'industri_id.required' => 'Industri wajib dipilih.',
            'industri_id.exists' => 'Industri tidak valid.',
            'guru_id.required' => 'Guru pembimbing wajib dipilih.',
            'guru_id.exists' => 'Guru tidak valid.',
            'kompetensi_id.required' => 'Kompetensi wajib dipilih.',
            'kompetensi_id.exists' => 'Kompetensi tidak valid.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ];
    }
}
