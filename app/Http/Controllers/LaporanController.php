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

    public function pklExport($penempatan_id)
    {
        $user = auth()->user();

        $penempatan = PenempatanPkl::with(['siswa', 'industri', 'guru', 'kompetensi', 'monitoringNilai'])
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

        $nilais = $penempatan->monitoringNilai
            ->where('role_penilai', 'industri')
            ->keyBy('aspek_penilaian');

        $rows = [];
        $styles = [];
        $merges = [];
        $rowNo = 1;

        $add = function (array $values, array $rowStyles = [], ?string $merge = null) use (&$rows, &$styles, &$merges, &$rowNo) {
            $rows[$rowNo] = $values;
            $styles[$rowNo] = array_pad($rowStyles, count($values), 0);
            if ($merge) {
                $merges[] = $merge;
            }
            $rowNo++;
        };

        // Judul
        $add(['DAFTAR NILAI PRAKTIK KERJA LAPANGAN', '', '', ''], [2, 0, 0, 0], 'A1:D1');
        $add(['', '', '', '']);

        // Info siswa
        $infoRows = [
            ['Nama', ': ' . $penempatan->siswa->nama_siswa],
            ['NIS', ': ' . $penempatan->siswa->nis],
            ['Kompetensi Keahlian', ': ' . ($penempatan->kompetensi->nama_kompetensi ?? '-')],
            ['Program Keahlian', ': ' . ($penempatan->siswa->jurusan ?? '-')],
            ['Tempat PKL', ': ' . ($penempatan->industri->nama_perusahaan ?? '-')],
            ['Guru Pembimbing', ': ' . ($penempatan->guru->nama_guru ?? '-')],
        ];
        foreach ($infoRows as $info) {
            $add([$info[0], $info[1], '', ''], [1, 0, 0, 0], 'B' . $rowNo . ':D' . $rowNo);
        }
        $add(['', '', '', '']);

        // A. Aspek Kejuruan
        $add(['A. ASPEK KEJURUAN', '', '', ''], [3, 3, 3, 3], 'A' . $rowNo . ':D' . $rowNo);
        $add(['No', 'Komponen Kompetensi', 'Angka', 'Huruf'], [3, 3, 3, 3]);

        $no = 1;
        $allKejuruanNilai = [];
        foreach ($kejuruanRoot as $komponen) {
            $add([$no++, $komponen->nama_aspek, '', ''], [1, 1, 0, 0]);
            foreach ($komponen->children->where('is_active', true) as $child) {
                $existing = $nilais->get($child->nama_aspek);
                if ($existing) {
                    $allKejuruanNilai[] = $existing->nilai;
                }
                $add(['', $child->nama_aspek,
                    $existing ? $existing->nilai : '',
                    $existing ? \App\Models\TemplatePenilaian::nilaiToHuruf($existing->nilai) : '']);
            }
        }
        $add(['', 'Jumlah', count($allKejuruanNilai) > 0 ? array_sum($allKejuruanNilai) : '', ''], [0, 1, 1, 0]);
        $add(['', 'Rata-rata',
            count($allKejuruanNilai) > 0 ? round(array_sum($allKejuruanNilai) / count($allKejuruanNilai), 1) : '',
            count($allKejuruanNilai) > 0 ? \App\Models\TemplatePenilaian::nilaiToHuruf((int) (array_sum($allKejuruanNilai) / count($allKejuruanNilai))) : ''],
            [0, 1, 1, 0]);
        $add(['', '', '', '']);

        // B. Aspek Sikap
        $add(['B. ASPEK SIKAP', '', '', ''], [3, 3, 3, 3], 'A' . $rowNo . ':D' . $rowNo);
        $add(['No', 'Komponen Sikap', 'Angka', 'Huruf'], [3, 3, 3, 3]);

        $no = 1;
        $allSikapNilai = [];
        foreach ($sikapItems as $item) {
            $existing = $nilais->get($item->nama_aspek);
            if ($existing) {
                $allSikapNilai[] = $existing->nilai;
            }
            $add([$no++, $item->nama_aspek,
                $existing ? $existing->nilai : '',
                $existing ? \App\Models\TemplatePenilaian::nilaiToHuruf($existing->nilai) : '']);
        }
        $add(['', 'Jumlah', count($allSikapNilai) > 0 ? array_sum($allSikapNilai) : '', ''], [0, 1, 1, 0]);
        $add(['', 'Rata-rata',
            count($allSikapNilai) > 0 ? round(array_sum($allSikapNilai) / count($allSikapNilai), 1) : '',
            count($allSikapNilai) > 0 ? \App\Models\TemplatePenilaian::nilaiToHuruf((int) (array_sum($allSikapNilai) / count($allSikapNilai))) : ''],
            [0, 1, 1, 0]);

        $filename = 'laporan-pkl-' . ($penempatan->siswa->nis ?? 'siswa') . '-' . now()->format('Y-m-d') . '.xlsx';

        return \App\Support\SimpleXlsx::download($filename, [
            'widths' => [6, 55, 12, 12],
            'merges' => $merges,
            'rows' => $rows,
            'styles' => $styles,
        ]);
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
