<?php

namespace App\Imports;

use App\Models\Industri;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class IndustriImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $success = 0;
    public int $skipped = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $kode = trim((string) ($row['kode_perusahaan'] ?? ''));
            $nama = trim((string) ($row['nama_perusahaan'] ?? ''));
            $emailLogin = trim((string) ($row['email_login'] ?? ''));

            if ($kode === '' || $nama === '' || $emailLogin === '') {
                $this->skipped++;
                continue;
            }

            if (User::where('email', $emailLogin)->exists() || Industri::where('kode_perusahaan', $kode)->exists()) {
                $this->skipped++;
                continue;
            }

            $password = trim((string) ($row['password'] ?? 'password')) ?: 'password';
            $kategori = trim((string) ($row['kategori'] ?? 'Semua')) ?: 'Semua';

            $user = User::create([
                'name' => $nama,
                'email' => $emailLogin,
                'password' => Hash::make($password),
                'role' => 'industri',
            ]);
            $user->setPasswordCopy($password);

            Industri::create([
                'user_id' => $user->id,
                'kode_perusahaan' => $kode,
                'nama_perusahaan' => $nama,
                'lokasi' => $row['lokasi'] ?? null,
                'alamat' => $row['alamat'] ?? null,
                'no_telepon' => $row['no_telepon'] ?? null,
                'email' => $row['email'] ?? null,
                'bidang_usaha' => $row['bidang_usaha'] ?? null,
                'kategori' => $kategori,
                'jurusan' => $kategori,
                'penanggung_jawab' => $row['penanggung_jawab'] ?? null,
                'kuota' => (int) ($row['kuota'] ?? 1),
                'status' => $row['status'] ?? 'aktif',
            ]);

            $this->success++;
        }
    }
}
