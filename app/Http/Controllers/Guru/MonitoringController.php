<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JurnalHarian;
use App\Models\KomentarJurnal;
use App\Models\PenempatanPkl;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;

        $query = PenempatanPkl::with(['siswa', 'industri', 'kompetensi'])
            ->where('guru_id', $guru->id)
            ->where('status', 'aktif');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $penempatans = $query->paginate(10);

        // Statistik
        $totalSiswa = PenempatanPkl::where('guru_id', $guru->id)
            ->where('status', 'aktif')
            ->count();

        $totalJurnalHariIni = JurnalHarian::whereHas('penempatan', function ($q) use ($guru) {
            $q->where('guru_id', $guru->id);
        })->whereDate('tanggal', today())->count();

        $siswaBelumJurnal = PenempatanPkl::with('siswa')
            ->where('guru_id', $guru->id)
            ->where('status', 'aktif')
            ->whereDoesntHave('jurnalHarian', function ($q) {
                $q->whereDate('tanggal', today());
            })
            ->get();

        return view('guru.monitoring.index', compact(
            'penempatans',
            'totalSiswa',
            'totalJurnalHariIni',
            'siswaBelumJurnal'
        ));
    }

    public function show($id)
    {
        $penempatan = PenempatanPkl::with(['siswa', 'industri', 'guru', 'kompetensi'])
            ->findOrFail($id);

        // Cek apakah guru ini bertanggung jawab
        if ($penempatan->guru_id != auth()->user()->guru->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $jurnals = JurnalHarian::where('penempatan_id', $penempatan->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('guru.monitoring.show', compact('penempatan', 'jurnals'));
    }

    public function storeKomentar(Request $request, $jurnal_id)
    {
        $request->validate([
            'komentar' => 'required|string',
        ]);

        $jurnal = JurnalHarian::findOrFail($jurnal_id);
        $guru = auth()->user()->guru;

        // Cek apakah guru ini bertanggung jawab
        if ($jurnal->penempatan->guru_id != $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke jurnal ini.');
        }

        KomentarJurnal::create([
            'jurnal_id' => $jurnal_id,
            'guru_id' => $guru->id,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
