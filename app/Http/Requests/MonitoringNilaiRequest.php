<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonitoringNilaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        return $user && ($user->role === 'guru' || $user->role === 'admin');
    }

    public function rules(): array
    {
        return [
            'penempatan_id' => 'required|exists:penempatan_pkl,id',
            'aspek_penilaian' => 'required|string|max:100',
            'nilai' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string',
            'tanggal_penilaian' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'penempatan_id.required' => 'Penempatan tidak valid.',
            'penempatan_id.exists' => 'Penempatan tidak ditemukan.',
            'aspek_penilaian.required' => 'Aspek penilaian wajib diisi.',
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.integer' => 'Nilai harus berupa angka.',
            'nilai.min' => 'Nilai minimal 0.',
            'nilai.max' => 'Nilai maksimal 100.',
            'tanggal_penilaian.required' => 'Tanggal penilaian wajib diisi.',
        ];
    }
}
