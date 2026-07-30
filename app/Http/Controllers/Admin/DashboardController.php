<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Industri;
use App\Models\PenempatanPkl;
use App\Models\JurnalHarian;
use App\Models\Guru;
use App\Models\Kompetensi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalIndustri = Industri::count();
        $totalKompetensi = Kompetensi::count();

        // Statistik PKL
        $totalAktif = PenempatanPkl::where('status', 'aktif')->count();
        $totalSelesai = PenempatanPkl::where('status', 'selesai')->count();
        $totalBatal = PenempatanPkl::where('status', 'batal')->count();

        // Statistik Jurnal
        $totalJurnalHariIni = JurnalHarian::whereDate('tanggal', today())->count();
        $totalJurnalMingguIni = JurnalHarian::whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $totalJurnalBulanIni = JurnalHarian::whereMonth('tanggal', now()->month)->count();

        // Siswa Belum Jurnal Hari Ini
        $siswaBelumJurnal = PenempatanPkl::with(['siswa', 'industri'])
            ->where('status', 'aktif')
            ->whereDoesntHave('jurnalHarian', function ($query) {
                $query->whereDate('tanggal', today());
            })
            ->get();

        // Data untuk Chart Status
        $statusData = [
            'aktif' => $totalAktif,
            'selesai' => $totalSelesai,
            'batal' => $totalBatal
        ];

        // Data untuk Chart Jurnal Per Minggu
        $jurnalPerMinggu = [
            'labels' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            'data' => []
        ];

        for ($i = 0; $i < 7; $i++) {
            $date = now()->startOfWeek()->addDays($i);
            $count = JurnalHarian::whereDate('tanggal', $date)->count();
            $jurnalPerMinggu['data'][] = $count;
        }

        // Data untuk Chart Perusahaan Teratas
        $topIndustri = Industri::withCount('penempatan')
            ->orderBy('penempatan_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalIndustri',
            'totalKompetensi',
            'totalAktif',
            'totalSelesai',
            'totalBatal',
            'totalJurnalHariIni',
            'totalJurnalMingguIni',
            'totalJurnalBulanIni',
            'siswaBelumJurnal',
            'statusData',
            'jurnalPerMinggu',
            'topIndustri'
        ));
    }
}
