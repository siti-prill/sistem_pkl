<?php

namespace App\Http\Controllers;

use App\Models\JurnalHarian;
use App\Models\MonitoringNilai;
use App\Models\PenempatanPkl;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function jurnal(Request $request)
    {
        $user = auth()->user();
        $query = JurnalHarian::with(['penempatan.siswa', 'penempatan.guru', 'penempatan.industri']);

        // Filter berdasarkan role
        if ($user->role == 'siswa') {
            $penempatan = PenempatanPkl::where('siswa_id', $user->siswa->id)->first();
            if ($penempatan) {
                $query->where('penempatan_id', $penempatan->id);
            } else {
                return redirect()->back()->with('error', 'Anda belum memiliki penempatan PKL.');
            }
        } elseif ($user->role == 'guru') {
            $query->whereHas('penempatan', function ($q) use ($user) {
                $q->where('guru_id', $user->guru->id);
            });
        }
        // Admin bisa melihat semua

        // Filter tanggal
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai != '') {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_selesai') && $request->tanggal_selesai != '') {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        // Filter siswa (untuk guru dan admin)
        if ($request->has('siswa_id') && $request->siswa_id != '') {
            $query->whereHas('penempatan', function ($q) use ($request) {
                $q->where('siswa_id', $request->siswa_id);
            });
        }

        $jurnals = $query->orderBy('tanggal', 'desc')->paginate(15);

        // Data untuk filter
        $siswas = PenempatanPkl::with('siswa')
            ->when($user->role == 'guru', function ($q) use ($user) {
                $q->where('guru_id', $user->guru->id);
            })
            ->get()
            ->pluck('siswa');

        return view('laporan.jurnal', compact('jurnals', 'siswas'));
    }

    public function nilai(Request $request)
    {
        $user = auth()->user();
        $query = MonitoringNilai::with(['penempatan.siswa', 'penempatan.guru']);

        // Filter berdasarkan role
        if ($user->role == 'siswa') {
            $penempatan = PenempatanPkl::where('siswa_id', $user->siswa->id)->first();
            if ($penempatan) {
                $query->where('penempatan_id', $penempatan->id);
            } else {
                return redirect()->back()->with('error', 'Anda belum memiliki penempatan PKL.');
            }
        } elseif ($user->role == 'guru') {
            $query->whereHas('penempatan', function ($q) use ($user) {
                $q->where('guru_id', $user->guru->id);
            });
        }
        // Admin bisa melihat semua

        // Filter siswa
        if ($request->has('siswa_id') && $request->siswa_id != '') {
            $query->whereHas('penempatan', function ($q) use ($request) {
                $q->where('siswa_id', $request->siswa_id);
            });
        }

        $nilais = $query->orderBy('tanggal_penilaian', 'desc')->paginate(15);

        // Data untuk filter
        $siswas = PenempatanPkl::with('siswa')
            ->when($user->role == 'guru', function ($q) use ($user) {
                $q->where('guru_id', $user->guru->id);
            })
            ->get()
            ->pluck('siswa');

        return view('laporan.nilai', compact('nilais', 'siswas'));
    }

    public function jurnalPdf(Request $request)
    {
        $user = auth()->user();
        $query = JurnalHarian::with(['penempatan.siswa', 'penempatan.guru', 'penempatan.industri']);

        // Filter sama seperti di method jurnal()
        if ($user->role == 'siswa') {
            $penempatan = PenempatanPkl::where('siswa_id', $user->siswa->id)->first();
            if ($penempatan) {
                $query->where('penempatan_id', $penempatan->id);
            }
        } elseif ($user->role == 'guru') {
            $query->whereHas('penempatan', function ($q) use ($user) {
                $q->where('guru_id', $user->guru->id);
            });
        }

        if ($request->has('tanggal_mulai') && $request->tanggal_mulai != '') {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_selesai') && $request->tanggal_selesai != '') {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        if ($request->has('siswa_id') && $request->siswa_id != '') {
            $query->whereHas('penempatan', function ($q) use ($request) {
                $q->where('siswa_id', $request->siswa_id);
            });
        }

        $jurnals = $query->orderBy('tanggal', 'desc')->get();

        $pdf = Pdf::loadView('laporan.jurnal_pdf', compact('jurnals'));
        return $pdf->download('laporan_jurnal_pkl.pdf');
    }

    public function nilaiPdf(Request $request)
    {
        $user = auth()->user();
        $query = MonitoringNilai::with(['penempatan.siswa', 'penempatan.guru']);

        if ($user->role == 'siswa') {
            $penempatan = PenempatanPkl::where('siswa_id', $user->siswa->id)->first();
            if ($penempatan) {
                $query->where('penempatan_id', $penempatan->id);
            }
        } elseif ($user->role == 'guru') {
            $query->whereHas('penempatan', function ($q) use ($user) {
                $q->where('guru_id', $user->guru->id);
            });
        }

        if ($request->has('siswa_id') && $request->siswa_id != '') {
            $query->whereHas('penempatan', function ($q) use ($request) {
                $q->where('siswa_id', $request->siswa_id);
            });
        }

        $nilais = $query->orderBy('tanggal_penilaian', 'desc')->get();

        $pdf = Pdf::loadView('laporan.nilai_pdf', compact('nilais'));
        return $pdf->download('laporan_nilai_pkl.pdf');
    }
}
