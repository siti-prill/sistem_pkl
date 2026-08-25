<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Raport PKL</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header h2 { margin: 5px 0 0 0; font-size: 14px; color: #666; }
        .header p { margin: 3px 0; color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th { background: #4f46e5; color: white; padding: 8px; text-align: left; font-size: 11px; }
        table td { padding: 6px 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
        table tr:nth-child(even) { background: #f9fafb; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 11px; }
        .siswa-info { background: #f3f4f6; padding: 12px; border-radius: 6px; margin-bottom: 15px; }
        .siswa-info table { margin-top: 0; }
        .siswa-info table th { background: #6b7280; font-size: 11px; }
        .siswa-info table td { font-size: 11px; }
        .section-title { font-size: 13px; font-weight: bold; margin: 15px 0 8px 0; color: #333; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .badge-guru { background: #4f46e5; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; }
        .badge-industri { background: #0d9488; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; }
        .kesimpulan-box { background: #f5f3ff; border: 1px solid #c4b5fd; padding: 12px; border-radius: 6px; margin-top: 15px; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENILAIAN PRAKTIK KERJA LAPANGAN</h1>
        <p>Tanggal Cetak: {{ now()->format('d F Y H:i') }}</p>
    </div>

    @foreach($raportData as $data)
        @php
            $penempatan = $data['penempatan'];
            $nilaisGuru = $data['nilaisGuru'];
            $nilaisIndustri = $data['nilaisIndustri'];
            $rataGuru = $data['rataGuru'];
            $rataIndustri = $data['rataIndustri'];
            $kesimpulan = $data['kesimpulan'];

            $allNilais = $nilaisGuru->concat($nilaisIndustri);
            $rataTotal = $allNilais->avg('nilai');
            $grade = $rataTotal >= 85 ? 'A' : ($rataTotal >= 70 ? 'B' : ($rataTotal >= 60 ? 'C' : 'D'));
        @endphp

        @if(!$loop->first)
            <div class="page-break"></div>
        @endif

        <!-- Data Siswa -->
        <div class="siswa-info">
            <table>
                <tr>
                    <th style="width:120px;">Nama Siswa</th>
                    <td>: {{ $penempatan->siswa->nama_siswa }}</td>
                    <th style="width:80px;">NIS</th>
                    <td>: {{ $penempatan->siswa->nis }}</td>
                </tr>
                <tr>
                    <th>Jurusan</th>
                    <td>: {{ $penempatan->siswa->jurusan }}</td>
                    <th>Industri</th>
                    <td>: {{ $penempatan->industri->nama_perusahaan }}</td>
                </tr>
                <tr>
                    <th>Guru Pembimbing</th>
                    <td>: {{ $penempatan->guru->nama_guru }}</td>
                    <th>Periode</th>
                    <td>: {{ \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d/m/Y') }}</td>
                </tr>
            </table>
        </div>

        <!-- Ringkasan -->
        <div style="display:flex; gap:10px; margin-bottom:15px;">
            @if($rataGuru)
            <div style="flex:1; text-align:center; background:#eef2ff; padding:8px; border-radius:6px;">
                <div style="font-size:10px; color:#666;">Rata-rata Guru</div>
                <div style="font-size:18px; font-weight:bold; color:#4f46e5;">{{ number_format($rataGuru, 1) }}</div>
            </div>
            @endif
            @if($rataIndustri)
            <div style="flex:1; text-align:center; background:#f0fdfa; padding:8px; border-radius:6px;">
                <div style="font-size:10px; color:#666;">Rata-rata Industri</div>
                <div style="font-size:18px; font-weight:bold; color:#0d9488;">{{ number_format($rataIndustri, 1) }}</div>
            </div>
            @endif
            <div style="flex:1; text-align:center; background:#f5f3ff; padding:8px; border-radius:6px;">
                <div style="font-size:10px; color:#666;">Grade Akhir</div>
                <div style="font-size:18px; font-weight:bold; color:{{ $grade == 'A' ? '#22c55e' : ($grade == 'B' ? '#3b82f6' : ($grade == 'C' ? '#eab308' : '#ef4444')) }};">{{ $grade }}</div>
            </div>
        </div>

        <!-- Tabel Nilai Guru -->
        @if($nilaisGuru->count() > 0)
            <div class="section-title">Penilaian Guru Pembimbing</div>
            <table>
                <thead>
                    <tr>
                        <th style="width:30px;">No</th>
                        <th>Aspek Penilaian</th>
                        <th style="width:60px;">Nilai</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nilaisGuru as $i => $nilai)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $nilai->aspek_penilaian }}</td>
                            <td><strong>{{ $nilai->nilai }}</strong></td>
                            <td>{{ $nilai->catatan ?? '-' }}</td>
                        </tr>
                    @endforeach
                    <tr style="background:#eef2ff; font-weight:bold;">
                        <td colspan="2" style="text-align:right;">Rata-rata</td>
                        <td colspan="2">{{ number_format($rataGuru, 1) }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        <!-- Tabel Nilai Industri -->
        @if($nilaisIndustri->count() > 0)
            <div class="section-title">Penilaian Industri</div>
            <table>
                <thead>
                    <tr>
                        <th style="width:30px;">No</th>
                        <th>Aspek Penilaian</th>
                        <th style="width:60px;">Nilai</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nilaisIndustri as $i => $nilai)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $nilai->aspek_penilaian }}</td>
                            <td><strong>{{ $nilai->nilai }}</strong></td>
                            <td>{{ $nilai->catatan ?? '-' }}</td>
                        </tr>
                    @endforeach
                    <tr style="background:#f0fdfa; font-weight:bold;">
                        <td colspan="2" style="text-align:right;">Rata-rata</td>
                        <td colspan="2">{{ number_format($rataIndustri, 1) }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        <!-- Nilai Kesimpulan (Hanya untuk Admin/Guru) -->
        @if($showKesimpulan && $kesimpulan)
            <div class="kesimpulan-box">
                <div class="section-title" style="margin-top:0; border-bottom-color:#c4b5fd;">Nilai Kesimpulan Akhir (Raport)</div>
                <table>
                    <tr>
                        <td style="width:150px; font-weight:bold;">Nilai Kesimpulan</td>
                        <td style="font-size:16px; font-weight:bold; color:#7c3aed;">{{ number_format($kesimpulan->nilai_kesimpulan, 1) }}</td>
                    </tr>
                    @if($kesimpulan->catatan_kesimpulan)
                    <tr>
                        <td style="font-weight:bold;">Catatan</td>
                        <td>{{ $kesimpulan->catatan_kesimpulan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        @endif

        @if(!$loop->last)
            <hr style="margin: 20px 0; border: 1px dashed #ccc;">
        @endif
    @endforeach

    @if($raportData->isEmpty())
        <p style="text-align:center;padding:40px;">Tidak ada data raport</p>
    @endif

    <div class="footer">
        <p>Dicetak dari Sistem PKL &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
