<?php

namespace Database\Seeders;

use App\Models\TemplatePenilaian;
use Illuminate\Database\Seeder;

class TemplatePenilaianSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing
        TemplatePenilaian::truncate();

        // ===== A. ASPEK KEJURUAN =====

        // Kompetensi Dasar (komponen)
        $kd = TemplatePenilaian::create([
            'nama_aspek' => 'Kompetensi Dasar',
            'kategori' => 'kejuruan',
            'parent_id' => null,
            'tipe' => 'komponen',
            'deskripsi' => 'Komponen kompetensi dasar kejuruan',
            'urutan' => 1,
            'is_active' => true,
        ]);

        TemplatePenilaian::insert([
            ['nama_aspek' => 'a. Kompetensi Dasar 1', 'kategori' => 'kejuruan', 'parent_id' => $kd->id, 'tipe' => 'item', 'rentang_nilai_min' => 0, 'rentang_nilai_max' => 100, 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_aspek' => 'b. Kompetensi Dasar 2', 'kategori' => 'kejuruan', 'parent_id' => $kd->id, 'tipe' => 'item', 'rentang_nilai_min' => 0, 'rentang_nilai_max' => 100, 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_aspek' => 'c. Kompetensi Dasar 3', 'kategori' => 'kejuruan', 'parent_id' => $kd->id, 'tipe' => 'item', 'rentang_nilai_min' => 0, 'rentang_nilai_max' => 100, 'urutan' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Kompetensi Keahlian (komponen)
        $kk = TemplatePenilaian::create([
            'nama_aspek' => 'Kompetensi Keahlian',
            'kategori' => 'kejuruan',
            'parent_id' => null,
            'tipe' => 'komponen',
            'deskripsi' => 'Komponen kompetensi keahlian khusus',
            'urutan' => 2,
            'is_active' => true,
        ]);

        TemplatePenilaian::insert([
            ['nama_aspek' => 'a. Kompetensi Keahlian 1', 'kategori' => 'kejuruan', 'parent_id' => $kk->id, 'tipe' => 'item', 'rentang_nilai_min' => 0, 'rentang_nilai_max' => 100, 'urutan' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_aspek' => 'b. Kompetensi Keahlian 2', 'kategori' => 'kejuruan', 'parent_id' => $kk->id, 'tipe' => 'item', 'rentang_nilai_min' => 0, 'rentang_nilai_max' => 100, 'urutan' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_aspek' => 'c. Kompetensi Keahlian 3', 'kategori' => 'kejuruan', 'parent_id' => $kk->id, 'tipe' => 'item', 'rentang_nilai_min' => 0, 'rentang_nilai_max' => 100, 'urutan' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_aspek' => 'd. Kompetensi Keahlian 4', 'kategori' => 'kejuruan', 'parent_id' => $kk->id, 'tipe' => 'item', 'rentang_nilai_min' => 0, 'rentang_nilai_max' => 100, 'urutan' => 7, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ===== B. ASPEK SIKAP =====
        $sikapItems = ['Disiplin', 'Kerja sama', 'Inisiatif', 'Kerajinan', 'Tanggung jawab', 'Prestasi'];
        foreach ($sikapItems as $i => $item) {
            TemplatePenilaian::create([
                'nama_aspek' => $item,
                'kategori' => 'sikap',
                'parent_id' => null,
                'tipe' => 'item',
                'deskripsi' => "Penilaian aspek sikap: {$item}",
                'rentang_nilai_min' => 0,
                'rentang_nilai_max' => 100,
                'urutan' => 10 + $i,
                'is_active' => true,
            ]);
        }
    }
}
