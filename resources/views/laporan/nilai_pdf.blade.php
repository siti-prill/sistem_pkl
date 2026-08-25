<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Nilai PKL</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th { background: #4f46e5; color: white; padding: 10px; text-align: left; }
        table td { padding: 8px 10px; border-bottom: 1px solid #ddd; }
        table tr:nth-child(even) { background: #f9fafb; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        .badge-aktif { background: #22c55e; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; }
        .badge-selesai { background: #3b82f6; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; }
        .summary-box { margin-top: 20px; padding: 15px; background: #f3f4f6; border-radius: 8px; }
        .summary-box table { margin-top: 0; }
        .summary-box table th { background: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN NILAI PKL</h1>
        <p>Tanggal Cetak: {{ now()->format('d F Y H:i') }}</p>
    </div>

    @php
        $grouped = $nilais->groupBy('penempatan_id');
    @endphp

    @foreach($grouped as $penempatanId => $items)
        @php
            $first = $items->first();
            $penempatan = $first->penempatan;
            $nilaisGuru = $items->where('role_penilai', 'guru');
            $nilaisIndustri = $items->where('role_penilai', 'industri');
            $rataGuru = $nilaisGuru->avg('nilai');
            $rataIndustri = $nilaisIndustri->avg('nilai');
            $rataRata = $items->avg('nilai');
            $grade = $rataRata >= 85 ? 'A' : ($rataRata >= 70 ? 'B' : ($rataRata >= 60 ? 'C' : 'D'));
        @endphp
        
        <div style="margin-bottom: 30px;">
            <div class="summary-box">
                <h3 style="margin:0 0 10px 0;">{{ $penempatan->siswa->nama_siswa }} ({{ $penempatan->siswa->nis }})</h3>
                <table style="width:auto;margin:0;">
                    <tr>
                        <th style="padding:5px 15px;background:#6b7280;color:white;">Industri</th>
                        <td style="padding:5px 15px;">{{ $penempatan->industri->nama_perusahaan }}</td>
                    </tr>
                    @if($rataGuru)
                    <tr>
                        <th style="padding:5px 15px;background:#6b7280;color:white;">Rata-rata Guru</th>
                        <td style="padding:5px 15px;font-weight:bold;color:#4f46e5;">{{ number_format($rataGuru, 1) }}</td>
                    </tr>
                    @endif
                    @if($rataIndustri)
                    <tr>
                        <th style="padding:5px 15px;background:#6b7280;color:white;">Rata-rata Industri</th>
                        <td style="padding:5px 15px;font-weight:bold;color:#0d9488;">{{ number_format($rataIndustri, 1) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th style="padding:5px 15px;background:#6b7280;color:white;">Rata-rata Total</th>
                        <td style="padding:5px 15px;font-weight:bold;color:#4f46e5;">{{ number_format($rataRata, 1) }}</td>
                    </tr>
                    <tr>
                        <th style="padding:5px 15px;background:#6b7280;color:white;">Grade</th>
                        <td style="padding:5px 15px;font-weight:bold;color:
                            {{ $grade == 'A' ? '#22c55e' : ($grade == 'B' ? '#3b82f6' : ($grade == 'C' ? '#eab308' : '#ef4444')) }}">
                            {{ $grade }}
                        </td>
                    </tr>
                </table>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Penilai</th>
                        <th>Aspek Penilaian</th>
                        <th>Nilai</th>
                        <th>Catatan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $nilai)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($nilai->role_penilai == 'industri')
                                    <span style="background:#0d9488;color:white;padding:2px 6px;border-radius:3px;font-size:11px;">Industri</span>
                                @else
                                    <span style="background:#4f46e5;color:white;padding:2px 6px;border-radius:3px;font-size:11px;">Guru</span>
                                @endif
                            </td>
                            <td>{{ $nilai->aspek_penilaian }}</td>
                            <td><strong>{{ $nilai->nilai }}</strong></td>
                            <td>{{ $nilai->catatan ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($nilai->tanggal_penilaian)->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    @if($nilais->isEmpty())
        <p style="text-align:center;padding:40px;">Tidak ada data nilai</p>
    @endif

    <div class="footer">
        <p>Dicetak dari Sistem PKL &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>