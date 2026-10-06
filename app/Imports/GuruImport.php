<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class GuruImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $success = 0;
    public int $skipped = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nip = trim((string) ($row['nip'] ?? ''));
            $nama = trim((string) ($row['nama_guru'] ?? ''));
            $email = trim((string) ($row['email'] ?? ''));

            if ($nip === '' || $nama === '' || $email === '') {
                $this->skipped++;
                continue;
            }

            if (User::where('email', $email)->exists() || Guru::where('nip', $nip)->exists()) {
                $this->skipped++;
                continue;
            }

            $password = trim((string) ($row['password'] ?? 'password')) ?: 'password';

            $user = User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'guru',
            ]);
            $user->setPasswordCopy($password);

            Guru::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'nama_guru' => $nama,
                'no_telepon' => $row['no_telepon'] ?? null,
                'alamat' => $row['alamat'] ?? null,
            ]);

            $this->success++;
        }
    }
}
