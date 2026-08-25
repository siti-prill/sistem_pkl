<?php

namespace Database\Seeders;

use App\Models\TemplatePenilaian;
use Illuminate\Database\Seeder;

class TemplatePenilaianSeeder extends Seeder
{
    public function run(): void
    {
        TemplatePenilaian::truncate();

        $kejuruanByJurusan = [
            'RPL' => [
                'komponen' => [
                    'Kompetensi Dasar' => ['Pemrograman Front-End', 'Pemrograman Back-End', 'Basis Data'],
                    'Kompetensi Keahlian' => ['Pengembangan Web', 'Pengembangan Mobile', 'Pengujian Perangkat Lunak', 'DevOps & Deployment'],
                ],
            ],
            'TKJ' => [
                'komponen' => [
                    'Kompetensi Dasar' => ['Konfigurasi Jaringan', 'Administrasi Sistem', 'Hardware & Troubleshooting'],
                    'Kompetensi Keahlian' => ['Konfigurasi Router & Switch', 'Administrasi Server', 'Keamanan Jaringan', 'Pemeliharaan Jaringan'],
                ],
            ],
            'DKV' => [
                'komponen' => [
                    'Kompetensi Dasar' => ['Desain Grafis', 'Tipografi', 'Teori Warna'],
                    'Kompetensi Keahlian' => ['Desain UI/UX', 'Animasi 2D', 'Fotografi Produk', 'Video Editing'],
                ],
            ],
            'PSPT' => [
                'komponen' => [
                    'Kompetensi Dasar' => ['Etika Bisnis', 'Komunikasi', 'Administrasi Niaga'],
                    'Kompetensi Keahlian' => ['Digital Marketing', 'Customer Service', 'Manajemen Penjualan', 'Pengelolaan Akun Media Sosial'],
                ],
            ],
        ];

        $sikapItems = ['Disiplin', 'Kerja sama', 'Inisiatif', 'Kerajinan', 'Tanggung jawab', 'Prestasi'];

        $urutanGlobal = 1;

        foreach ($kejuruanByJurusan as $jurusan => $data) {
            foreach ($data['komponen'] as $komponenNama => $items) {
                $komponen = TemplatePenilaian::create([
                    'nama_aspek' => $komponenNama,
                    'kategori' => 'kejuruan',
                    'jurusan' => $jurusan,
                    'parent_id' => null,
                    'tipe' => 'komponen',
                    'deskripsi' => "Komponen {$komponenNama} jurusan {$jurusan}",
                    'rentang_nilai_min' => 0,
                    'rentang_nilai_max' => 100,
                    'urutan' => $urutanGlobal++,
                    'is_active' => true,
                ]);

                foreach ($items as $item) {
                    TemplatePenilaian::create([
                        'nama_aspek' => $item,
                        'kategori' => 'kejuruan',
                        'jurusan' => $jurusan,
                        'parent_id' => $komponen->id,
                        'tipe' => 'item',
                        'rentang_nilai_min' => 0,
                        'rentang_nilai_max' => 100,
                        'urutan' => $urutanGlobal++,
                        'is_active' => true,
                    ]);
                }
            }

            foreach ($sikapItems as $i => $sikap) {
                TemplatePenilaian::create([
                    'nama_aspek' => $sikap,
                    'kategori' => 'sikap',
                    'jurusan' => $jurusan,
                    'parent_id' => null,
                    'tipe' => 'item',
                    'deskripsi' => "Penilaian aspek sikap: {$sikap}",
                    'rentang_nilai_min' => 0,
                    'rentang_nilai_max' => 100,
                    'urutan' => $urutanGlobal++,
                    'is_active' => true,
                ]);
            }
        }
    }
}
