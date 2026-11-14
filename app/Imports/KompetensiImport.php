<?php

namespace App\Imports;

use App\Models\Kompetensi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class KompetensiImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $success = 0;
    public int $skipped = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $kode = trim((string) ($row['kode_kompetensi'] ?? ''));
            $nama = trim((string) ($row['nama_kompetensi'] ?? ''));
            $kategori = trim((string) ($row['kategori'] ?? 'Semua')) ?: 'Semua';

            if (!in_array($kategori, ['RPL', 'TKJ', 'DKV', 'PSPT', 'Semua'], true)) {
                $kategori = 'Semua';
            }

            if ($kode === '' || $nama === '') {
                $this->skipped++;
                continue;
            }

            if (Kompetensi::where('kode_kompetensi', $kode)->exists()) {
                $this->skipped++;
                continue;
            }

            Kompetensi::create([
                'kode_kompetensi' => $kode,
                'nama_kompetensi' => $nama,
                'kategori'        => $kategori,
                'deskripsi'       => $row['deskripsi'] ?? null,
            ]);

            $this->success++;
        }
    }
}