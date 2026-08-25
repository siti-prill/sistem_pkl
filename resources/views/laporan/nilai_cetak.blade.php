<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Nilai PKL - {{ $penempatan->siswa->nama_siswa }}</title>
    <style>
        @page { size: A4; margin: 20mm 15mm 25mm 15mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; color: #000; line-height: 1.4; }
        .container { width: 100%; max-width: 700px; margin: 0 auto; }

        h1 { text-align: center; font-size: 14px; font-weight: bold; text-decoration: underline; margin-bottom: 20px; }

        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 2px 0; vertical-align: top; }
        .info-table .label { width: 160px; font-weight: bold; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.data-table th,
        table.data-table td { border: 1px solid #000; padding: 4px 6px; font-size: 11px; }
        table.data-table th { background: #f0f0f0; font-weight: bold; text-align: center; }
        table.data-table td.no { text-align: center; width: 30px; }
        table.data-table td.angka { text-align: center; width: 50px; }
        table.data-table td.huruf { text-align: center; width: 40px; }
        table.data-table td.komponen-ket { font-weight: bold; }
        table.data-table td.sub-item { padding-left: 20px; }
        table.data-table td.jumlah-row { font-weight: bold; background: #f5f5f5; }

        .keterangan { margin: 15px 0; }
        .keterangan h3 { font-size: 11px; font-weight: bold; margin-bottom: 5px; }
        .keterangan table { border-collapse: collapse; }
        .keterangan table td { border: 1px solid #000; padding: 2px 8px; font-size: 10px; }

        .footer { margin-top: 30px; text-align: right; }
        .footer .lokasi-tanggal { margin-bottom: 60px; }
        .footer .ttd { line-height: 1.6; }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }

        .print-btn { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .print-btn button { background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 14px; }
        .print-btn button:hover { background: #4338ca; }
    </style>
</head>
<body>
    <div class="print-btn no-print">
        <button onclick="window.print()">Cetak</button>
    </div>

    <div class="container">
        <h1>DAFTAR NILAI PRAKTIK KERJA LAPANGAN</h1>

        <table class="info-table">
            <tr>
                <td class="label">Nama</td>
                <td>: {{ $penempatan->siswa->nama_siswa }}</td>
            </tr>
            <tr>
                <td class="label">NIS</td>
                <td>: {{ $penempatan->siswa->nis }}</td>
            </tr>
            <tr>
                <td class="label">Kompetensi Keahlian</td>
                <td>: {{ $penempatan->kompetensi->nama_kompetensi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Program Keahlian</td>
                <td>: {{ $penempatan->siswa->jurusan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tempat PKL</td>
                <td>: {{ $penempatan->industri->nama_perusahaan ?? '-' }}</td>
            </tr>
        </table>

        <!-- A. Aspek Kejuruan -->
        <h2 style="font-size:12px; font-weight:bold; margin: 15px 0 5px 0;">A. Aspek Kejuruan</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width:30px;">No</th>
                    <th rowspan="2">Komponen Kompetensi Kejuruan</th>
                    <th colspan="2">Nilai</th>
                </tr>
                <tr>
                    <th style="width:50px;">Angka</th>
                    <th style="width:40px;">Huruf</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; $allKejuruanNilai = []; @endphp
                @foreach($kejuruanRoot as $komponen)
                    <tr>
                        <td class="no komponen-ket">{{ $no++ }}</td>
                        <td class="komponen-ket">{{ $komponen->nama_aspek }}</td>
                        <td class="angka"></td>
                        <td class="huruf"></td>
                    </tr>
                    @foreach($komponen->children->where('is_active', true) as $child)
                        @php
                            $existing = $nilais->get($child->nama_aspek);
                            if ($existing) $allKejuruanNilai[] = $existing->nilai;
                        @endphp
                        <tr>
                            <td class="no"></td>
                            <td class="sub-item">{{ $child->nama_aspek }}</td>
                            <td class="angka">{{ $existing ? $existing->nilai : '' }}</td>
                            <td class="huruf">{{ $existing ? \App\Models\TemplatePenilaian::nilaiToHuruf($existing->nilai) : '' }}</td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="2" class="jumlah-row" style="text-align:right; padding-right:10px;">Jumlah</td>
                    <td class="angka jumlah-row">{{ count($allKejuruanNilai) > 0 ? array_sum($allKejuruanNilai) : '' }}</td>
                    <td class="huruf jumlah-row"></td>
                </tr>
                <tr>
                    <td colspan="2" class="jumlah-row" style="text-align:right; padding-right:10px;">Rata-rata</td>
                    <td class="angka jumlah-row">{{ count($allKejuruanNilai) > 0 ? number_format(array_sum($allKejuruanNilai) / count($allKejuruanNilai), 1) : '' }}</td>
                    <td class="huruf jumlah-row">{{ count($allKejuruanNilai) > 0 ? \App\Models\TemplatePenilaian::nilaiToHuruf((int)(array_sum($allKejuruanNilai) / count($allKejuruanNilai))) : '' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- B. Aspek Sikap -->
        <h2 style="font-size:12px; font-weight:bold; margin: 15px 0 5px 0;">B. Aspek Sikap</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width:30px;">No</th>
                    <th rowspan="2">Komponen Sikap</th>
                    <th colspan="2">Nilai</th>
                </tr>
                <tr>
                    <th style="width:50px;">Angka</th>
                    <th style="width:40px;">Huruf</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; $allSikapNilai = []; @endphp
                @foreach($sikapItems as $item)
                    @php
                        $existing = $nilais->get($item->nama_aspek);
                        if ($existing) $allSikapNilai[] = $existing->nilai;
                    @endphp
                    <tr>
                        <td class="no">{{ $no++ }}</td>
                        <td>{{ $item->nama_aspek }}</td>
                        <td class="angka">{{ $existing ? $existing->nilai : '' }}</td>
                        <td class="huruf">{{ $existing ? \App\Models\TemplatePenilaian::nilaiToHuruf($existing->nilai) : '' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="jumlah-row" style="text-align:right; padding-right:10px;">Jumlah</td>
                    <td class="angka jumlah-row">{{ count($allSikapNilai) > 0 ? array_sum($allSikapNilai) : '' }}</td>
                    <td class="huruf jumlah-row"></td>
                </tr>
                <tr>
                    <td colspan="2" class="jumlah-row" style="text-align:right; padding-right:10px;">Rata-rata</td>
                    <td class="angka jumlah-row">{{ count($allSikapNilai) > 0 ? number_format(array_sum($allSikapNilai) / count($allSikapNilai), 1) : '' }}</td>
                    <td class="huruf jumlah-row">{{ count($allSikapNilai) > 0 ? \App\Models\TemplatePenilaian::nilaiToHuruf((int)(array_sum($allSikapNilai) / count($allSikapNilai))) : '' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Keterangan -->
        <div class="keterangan">
            <h3>Keterangan Nilai Angka dan Huruf</h3>
            <table>
                <tr>
                    <td>90 – 100</td>
                    <td style="font-weight:bold; width:30px;">A</td>
                    <td>( Sangat Kompeten )</td>
                </tr>
                <tr>
                    <td>80 – 89</td>
                    <td style="font-weight:bold;">B</td>
                    <td>( Kompeten )</td>
                </tr>
                <tr>
                    <td>70 – 79</td>
                    <td style="font-weight:bold;">C</td>
                    <td>( Cukup Kompeten )</td>
                </tr>
                <tr>
                    <td>&lt; 70</td>
                    <td style="font-weight:bold;">D</td>
                    <td>( Kurang Kompeten )</td>
                </tr>
            </table>
        </div>

        <!-- Footer TTD -->
        <div class="footer">
            <div class="lokasi-tanggal">
                ({{ $penempatan->industri->lokasi ?? '........' }}), {{ \Carbon\Carbon::parse($penempatan->tanggal_selesai ?? now())->translatedFormat('d F Y') }}
            </div>
            <div class="ttd">
                <strong>Pembimbing Lapangan</strong><br><br><br><br>
                <strong>( {{ $penempatan->industri->penanggung_jawab ?? $penempatan->industri->nama_perusahaan ?? '........' }} )</strong><br>
                NIK. ........
            </div>
        </div>
    </div>
</body>
</html>
