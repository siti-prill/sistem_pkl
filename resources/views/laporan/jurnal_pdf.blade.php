<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal PKL</title>
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
        .badge-submitted { background: #22c55e; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; }
        .badge-draft { background: #eab308; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN JURNAL PKL</h1>
        <p>Periode: {{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') : 'Awal' }} - 
           {{ request('tanggal_selesai') ? \Carbon\Carbon::parse(request('tanggal_selesai'))->format('d/m/Y') : 'Akhir' }}</p>
        <p>Tanggal Cetak: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Siswa</th>
                <th>NIS</th>
                <th>Industri</th>
                <th>Aktivitas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnals as $index => $jurnal)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $jurnal->penempatan->siswa->nama_siswa }}</td>
                    <td>{{ $jurnal->penempatan->siswa->nis }}</td>
                    <td>{{ $jurnal->penempatan->industri->nama_perusahaan }}</td>
                    <td>{{ $jurnal->aktivitas }}</td>
                    <td>
                        @if($jurnal->status == 'submitted')
                            <span class="badge-submitted">Submitted</span>
                        @else
                            <span class="badge-draft">Draft</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak dari Sistem PKL &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>