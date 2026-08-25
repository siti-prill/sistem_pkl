<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kompetensi;
use App\Models\Industri;
use App\Models\User;
use App\Models\TemplatePenilaian;
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
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        $adminUser->setPasswordCopy('password');

        // Buat guru tambahan untuk login
        $guruUser = User::updateOrCreate(
            ['email' => 'guru@gmail.com'],
            [
                'name' => 'Guru Pembimbing',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );
        $guruUser->setPasswordCopy('password');

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
        $siswaUser->setPasswordCopy('password');

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

        // ==================== DATA INDUSTRI USER ====================
        $industriUser = User::updateOrCreate(
            ['email' => 'industri@gmail.com'],
            [
                'name' => 'Admin Industri',
                'password' => Hash::make('password'),
                'role' => 'industri',
            ]
        );
        $industriUser->setPasswordCopy('password');

        $industri = Industri::updateOrCreate(
            ['kode_perusahaan' => 'IND-001'],
            [
                'user_id' => $industriUser->id,
                'nama_perusahaan' => 'PT Teknologi Nusantara',
                'lokasi' => 'Padang',
                'alamat' => 'Jl. Industri No. 10, Padang',
                'no_telepon' => '08123456780',
                'email' => 'info@teknologinusantara.co.id',
                'bidang_usaha' => 'Teknologi',
                'jurusan' => 'XII RPL',
                'penanggung_jawab' => 'Budi Santoso',
                'kuota' => 10,
                'status' => 'aktif',
            ]
        );

        // ==================== TEMPLATE PENILAIAN ====================
        $templates = [
            ['nama_aspek' => 'Kedisiplinan', 'deskripsi' => 'Tingkat ketepatan waktu dalam kehadiran dan penyelesaian tugas', 'instruksi' => 'Nilai 0-100 berdasarkan catatan kehadiran dan ketepatan waktu', 'urutan' => 1],
            ['nama_aspek' => 'Kerja Sama', 'deskripsi' => 'Kemampuan bekerja sama dalam tim dan berkomunikasi dengan rekan kerja', 'instruksi' => 'Amati kemampuan siswa dalam berinteraksi dengan karyawan lain', 'urutan' => 2],
            ['nama_aspek' => 'Kemandirian', 'deskripsi' => 'Kemampuan bekerja secara mandiri tanpa pengawasan terus-menerus', 'instruksi' => 'Apakah siswa bisa menyelesaikan tugas sendiri?', 'urutan' => 3],
            ['nama_aspek' => 'Tanggung Jawab', 'deskripsi' => 'Tingkat keseriusan dalam menjalankan tugas dan mempertanggungjawabkan hasil kerja', 'instruksi' => 'Apakah siswa menyelesaikan tugas yang diberikan dengan baik?', 'urutan' => 4],
            ['nama_aspek' => 'Kreativitas', 'deskripsi' => 'Kemampuan memberikan ide dan solusi baru dalam pekerjaan', 'instruksi' => 'Apakah siswa memberikan masukan atau ide baru?', 'urutan' => 5],
            ['nama_aspek' => 'Penguasaan Materi', 'deskripsi' => 'Penerapan ilmu yang dipelajari di sekolah dalam pekerjaan di industri', 'instruksi' => 'Seberapa baik siswa menerapkan pengetahuan teknis?', 'urutan' => 6],
            ['nama_aspek' => 'Sikap dan Perilaku', 'deskripsi' => 'Sikap sopan santun, etika kerja, dan profesionalisme', 'instruksi' => 'Apakah siswa memiliki sikap yang baik di lingkungan kerja?', 'urutan' => 7],
        ];

        foreach ($templates as $template) {
            TemplatePenilaian::updateOrCreate(
                ['nama_aspek' => $template['nama_aspek']],
                array_merge($template, ['rentang_nilai_min' => 0, 'rentang_nilai_max' => 100, 'is_active' => true])
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
