<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kompetensi;
use App\Models\Industri;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Buat data dummy
        Guru::factory(5)->create();
        Siswa::factory(20)->create();
        Kompetensi::factory(10)->create();
        Industri::factory(10)->create();

        // Buat guru tambahan untuk login
        $guruUser = User::create([
            'name' => 'Guru Pembimbing',
            'email' => 'guru@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        Guru::create([
            'user_id' => $guruUser->id,
            'nip' => 'NIP001',
            'nama_guru' => 'Guru Pembimbing',
            'no_telepon' => '08123456789',
            'alamat' => 'Jl. Guru No. 1',
        ]);

        // Buat siswa tambahan untuk login
        $siswaUser = User::create([
            'name' => 'Siswa PKL',
            'email' => 'siswa@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        Siswa::create([
            'user_id' => $siswaUser->id,
            'nis' => 'NIS001',
            'nama_siswa' => 'Siswa PKL',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'no_telepon' => '08123456788',
            'alamat' => 'Jl. Siswa No. 1',
        ]);
    }
}
