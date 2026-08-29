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
        } elseif ($user->role == 'industri') {
            $industri = $user->industri;
            if ($industri) {
                $query->whereHas('penempatan', function ($q) use ($industri) {
                    $q->where('industri_id', $industri->id);
                });
            }
        }
        // Admin bisa melihat semua

        // Filter tanggal
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai != '') {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_selesai') && $request->tanggal_selesai != '') {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        // Filter siswa (untuk guru, admin, dan industri)
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
            ->when($user->role == 'industri', function ($q) use ($user) {
                $q->where('industri_id', $user->industri->id);
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
        } elseif ($user->role == 'industri') {
            $industri = $user->industri;
            if ($industri) {
                $query->whereHas('penempatan', function ($q) use ($industri) {
                    $q->where('industri_id', $industri->id);
                });
            }
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
            ->when($user->role == 'industri', function ($q) use ($user) {
                $q->where('industri_id', $user->industri->id);
            })
            ->get()
            ->pluck('siswa');

        return view('laporan.nilai', compact('nilais', 'siswas'));
    }

    public function pkl(Request $request)
    {
        $user = auth()->user();

        $query = PenempatanPkl::with(['siswa', 'industri', 'guru', 'monitoringNilai'])
            ->withCount(['monitoringNilai as jumlah_nilai_industri' => function ($q) {
                $q->where('role_penilai', 'industri');
            }]);

        if ($user->role == 'siswa') {
            if (!$user->siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
            }
            $query->where('siswa_id', $user->siswa->id);
        } elseif ($user->role == 'guru') {
            if (!$user->guru) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
            }
            $query->where('guru_id', $user->guru->id);
        } else {
            abort(403);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $penempatans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('laporan.pkl', compact('penempatans'));
    }

    public function pklShow($penempatan_id)
    {
        $user = auth()->user();

        $penempatan = PenempatanPkl::with(['siswa', 'industri', 'guru', 'kompetensi'])
            ->findOrFail($penempatan_id);

        if ($user->role == 'siswa') {
            if (!$user->siswa || $penempatan->siswa_id != $user->siswa->id) {
                abort(403);
            }
        } elseif ($user->role == 'guru') {
            if (!$user->guru || $penempatan->guru_id != $user->guru->id) {
                abort(403);
            }
        } else {
            abort(403);
        }

        $siswaJurusan = $penempatan->siswa->jurusan ?? null;

        $templates = \App\Models\TemplatePenilaian::active()
            ->forJurusan($siswaJurusan)
            ->orderBy('kategori')
            ->orderBy('urutan')
            ->get();

        $kejuruanRoot = $templates->where('kategori', 'kejuruan')->whereNull('parent_id')->sortBy('urutan');
        $sikapItems = $templates->where('kategori', 'sikap')->sortBy('urutan');

        $nilais = MonitoringNilai::where('penempatan_id', $penempatan->id)
            ->where('role_penilai', 'industri')
            ->get()
            ->keyBy('aspek_penilaian');

        return view('laporan.pkl_show', compact('penempatan', 'nilais', 'templates', 'kejuruanRoot', 'sikapItems'));
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
        } elseif ($user->role == 'industri') {
            $industri = $user->industri;
            if ($industri) {
                $query->whereHas('penempatan', function ($q) use ($industri) {
                    $q->where('industri_id', $industri->id);
                });
            }
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
        } elseif ($user->role == 'industri') {
            $industri = $user->industri;
            if ($industri) {
                $query->whereHas('penempatan', function ($q) use ($industri) {
                    $q->where('industri_id', $industri->id);
                });
            }
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

    public function raportPdf(Request $request)
    {
        $user = auth()->user();
        $showKesimpulan = in_array($user->role, ['admin', 'guru']);

        $query = PenempatanPkl::with(['siswa', 'guru', 'industri', 'kompetensi'])
            ->where('status', 'aktif');

        if ($user->role == 'siswa') {
            $query->where('siswa_id', $user->siswa->id);
        } elseif ($user->role == 'guru') {
            $query->where('guru_id', $user->guru->id);
        } elseif ($user->role == 'industri') {
            $industri = $user->industri;
            if ($industri) {
                $query->where('industri_id', $industri->id);
            }
        }

        if ($request->has('siswa_id') && $request->siswa_id != '') {
            $query->where('siswa_id', $request->siswa_id);
        }

        $penempatans = $query->get();

        $raportData = $penempatans->map(function ($penempatan) use ($showKesimpulan) {
            $nilaisGuru = MonitoringNilai::where('penempatan_id', $penempatan->id)
                ->where('role_penilai', 'guru')->get();
            $nilaisIndustri = MonitoringNilai::where('penempatan_id', $penempatan->id)
                ->where('role_penilai', 'industri')->get();
            $kesimpulan = null;
            if ($showKesimpulan) {
                $kesimpulan = \App\Models\NilaiKesimpulan::where('penempatan_id', $penempatan->id)->first();
            }

            return [
                'penempatan' => $penempatan,
                'nilaisGuru' => $nilaisGuru,
                'nilaisIndustri' => $nilaisIndustri,
                'rataGuru' => $nilaisGuru->avg('nilai'),
                'rataIndustri' => $nilaisIndustri->avg('nilai'),
                'kesimpulan' => $kesimpulan,
            ];
        });

        $pdf = Pdf::loadView('laporan.raport_pdf', compact('raportData', 'showKesimpulan', 'user'));
        return $pdf->download('raport_pkl.pdf');
    }

    public function nilaiCetak($penempatan_id)
    {
        $penempatan = PenempatanPkl::with(['siswa', 'guru', 'kompetensi', 'industri'])
            ->findOrFail($penempatan_id);

        $siswaJurusan = $penempatan->siswa->jurusan ?? null;
        $templates = \App\Models\TemplatePenilaian::active()
            ->forJurusan($siswaJurusan)
            ->orderBy('kategori')
            ->orderBy('urutan')
            ->get();
        $kejuruanRoot = $templates->where('kategori', 'kejuruan')->whereNull('parent_id')->sortBy('urutan');
        $sikapItems = $templates->where('kategori', 'sikap')->sortBy('urutan');

        $nilais = MonitoringNilai::where('penempatan_id', $penempatan->id)
            ->get()
            ->keyBy('aspek_penilaian');

        return view('laporan.nilai_cetak', compact('penempatan', 'nilais', 'kejuruanRoot', 'sikapItems'));
    }
}
