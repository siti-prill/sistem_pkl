<?php

namespace App\Http\Controllers\Industri;

use App\Http\Controllers\Controller;
use App\Models\MonitoringNilai;
use App\Models\PenempatanPkl;
use App\Models\TemplatePenilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $industri = $user->industri;

        if (!$industri) {
            return redirect()->back()->with('error', 'Data industri tidak ditemukan.');
        }

        $query = PenempatanPkl::with(['siswa', 'guru', 'kompetensi'])
            ->where('industri_id', $industri->id)
            ->where('status', 'aktif');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $penempatans = $query->paginate(10);

        return view('industri.penilaian.index', compact('penempatans', 'industri'));
    }

    public function show($penempatan_id)
    {
        $user = auth()->user();
        $industri = $user->industri;

        $penempatan = PenempatanPkl::with(['siswa', 'guru', 'kompetensi', 'industri'])
            ->where('industri_id', $industri->id)
            ->findOrFail($penempatan_id);

        $templates = TemplatePenilaian::active()->orderBy('kategori')->orderBy('urutan')->get();

        $kejuruanRoot = $templates->where('kategori', 'kejuruan')->whereNull('parent_id')->sortBy('urutan');
        $sikapItems = $templates->where('kategori', 'sikap')->sortBy('urutan');

        $existingNilais = MonitoringNilai::where('penempatan_id', $penempatan->id)
            ->where('role_penilai', 'industri')
            ->get()
            ->keyBy('aspek_penilaian');

        return view('industri.penilaian.show', compact(
            'penempatan', 'templates', 'existingNilais', 'kejuruanRoot', 'sikapItems'
        ));
    }

    public function store(Request $request, $penempatan_id)
    {
        $user = auth()->user();
        $industri = $user->industri;

        $penempatan = PenempatanPkl::where('industri_id', $industri->id)
            ->where('status', 'aktif')
            ->findOrFail($penempatan_id);

        $templates = TemplatePenilaian::active()->where('tipe', 'item')->get();
        $saved = 0;
        $hasAny = false;

        foreach ($templates as $template) {
            $nilaiKey = 'nilai_' . $template->id;
            if ($request->has($nilaiKey) && $request->input($nilaiKey) !== null && $request->input($nilaiKey) !== '') {
                $nilaiVal = (int) $request->input($nilaiKey);

                $request->validate([
                    $nilaiKey => 'required|integer|min:0|max:100',
                ]);

                $hasAny = true;
                $catatanKey = 'catatan_' . $template->id;
                $catatan = $request->input($catatanKey, null);

                MonitoringNilai::updateOrCreate(
                    [
                        'penempatan_id' => $penempatan->id,
                        'aspek_penilaian' => $template->nama_aspek,
                        'role_penilai' => 'industri',
                    ],
                    [
                        'guru_id' => $penempatan->guru_id,
                        'nilai' => $nilaiVal,
                        'catatan' => $catatan,
                        'is_hidden_from_siswa' => false,
                        'tanggal_penilaian' => now()->toDateString(),
                    ]
                );
                $saved++;
            }
        }

        if (!$hasAny) {
            return back()->with('error', 'Minimal satu nilai harus diisi.');
        }

        if ($saved > 0) {
            return redirect()->route('industri.penilaian.show', $penempatan->id)
                ->with('success', "Berhasil menyimpan {$saved} penilaian.");
        }

        return back()->with('error', 'Tidak ada nilai yang disimpan.');
    }
}
