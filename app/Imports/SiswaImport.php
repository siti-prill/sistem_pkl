<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SiswaImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $success = 0;
    public int $skipped = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nis = trim((string) ($row['nis'] ?? ''));
            $nama = trim((string) ($row['nama_siswa'] ?? ''));
            $email = trim((string) ($row['email'] ?? ''));

            if ($nis === '' || $nama === '' || $email === '') {
                $this->skipped++;
                continue;
            }

            if (User::where('email', $email)->exists() || Siswa::where('nis', $nis)->exists()) {
                $this->skipped++;
                continue;
            }

            $password = trim((string) ($row['password'] ?? 'password')) ?: 'password';

            $user = User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'siswa',
            ]);
            $user->setPasswordCopy($password);

            Siswa::create([
                'user_id' => $user->id,
                'nis' => $nis,
                'nama_siswa' => $nama,
                'jurusan' => $row['jurusan'] ?? 'XII RPL',
                'no_telepon' => $row['no_telepon'] ?? null,
                'alamat' => $row['alamat'] ?? null,
            ]);

            $this->success++;
        }
    }
}
