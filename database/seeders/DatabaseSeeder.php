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
    /**
     * Daftar jurusan/kelas yang dipakai PKL (kelas XII).
     * Data ini juga jadi pilihan dropdown jurusan di form pengajuan
     * dan form siswa admin, dikelola lewat menu "Data Kompetensi".
     */
    protected array $jurusanList = [
        'XII RPL',
        'XII TKJ 1',
        'XII TKJ 2',
        'XII DKV 1',
        'XII DKV 2',
        'XII PSPT',
    ];

    public function run(): void
    {
        // ==================== DATA DASAR (Permanen) ====================
        // Semua pakai firstOrCreate/updateOrCreate supaya aman dijalankan
        // ulang lewat `php artisan db:seed` tanpa menghapus data lain.
        // CATATAN: jangan pakai `migrate:fresh --seed` kecuali struktur
        // database berubah, karena itu menghapus SEMUA data termasuk
        // data yang kamu isi lewat aplikasi.

        // Buat Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Buat guru tambahan untuk login
        $guruUser = User::updateOrCreate(
            ['email' => 'guru@gmail.com'],
            [
                'name' => 'Guru Pembimbing',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        Guru::updateOrCreate(
            ['nip' => 'NIP001'],
            [
                'user_id' => $guruUser->id,
                'nama_guru' => 'Guru Pembimbing',
                'no_telepon' => '08123456789',
                'alamat' => 'Jl. Guru No. 1',
            ]
        );

        // Buat siswa tambahan untuk login
        $siswaUser = User::updateOrCreate(
            ['email' => 'siswa@gmail.com'],
            [
                'name' => 'Siswa PKL',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );

        Siswa::updateOrCreate(
            ['nis' => 'NIS001'],
            [
                'user_id' => $siswaUser->id,
                'nama_siswa' => 'Siswa PKL',
                'jurusan' => 'XII RPL',
                'no_telepon' => '08123456788',
                'alamat' => 'Jl. Siswa No. 1',
            ]
        );

        // Daftar jurusan -> tersimpan di tabel kompetensi (menu Data Kompetensi)
        foreach ($this->jurusanList as $i => $jurusan) {
            Kompetensi::updateOrCreate(
                ['kode_kompetensi' => 'JUR-' . str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)],
                [
                    'nama_kompetensi' => $jurusan,
                    'deskripsi' => 'Jurusan ' . $jurusan . ' (kelas XII)',
                ]
            );
        }

        // ==================== DATA DUMMY (Hanya saat tabel kosong) ====================
        if (Guru::count() === 0) {
            Guru::factory(3)->create();
        }
        if (Siswa::count() === 0) {
            Siswa::factory(10)->create();
        }
        if (Industri::count() === 0) {
            Industri::factory(8)->create();
        }
    }
}
