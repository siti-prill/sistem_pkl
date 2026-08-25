<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\MonitoringNilai;
use App\Models\NilaiKesimpulan;
use App\Models\PenempatanPkl;
use Illuminate\Http\Request;

class KesimpulanController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;

        $query = PenempatanPkl::with(['siswa', 'industri'])
            ->where('guru_id', $guru->id);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $penempatans = $query->paginate(10);
        return view('guru.kesimpulan.index', compact('penempatans'));
    }

    public function show($penempatan_id)
    {
        $guru = auth()->user()->guru;

        $penempatan = PenempatanPkl::with(['siswa', 'industri', 'kompetensi'])
            ->findOrFail($penempatan_id);

        if ($penempatan->guru_id != $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $nilaisGuru = MonitoringNilai::where('penempatan_id', $penempatan->id)
            ->where('role_penilai', 'guru')
            ->get();

        $nilaisIndustri = MonitoringNilai::where('penempatan_id', $penempatan->id)
            ->where('role_penilai', 'industri')
            ->get();

        $kesimpulan = NilaiKesimpulan::where('penempatan_id', $penempatan->id)
            ->where('guru_id', $guru->id)
            ->first();

        $rataGuru = $nilaisGuru->avg('nilai');
        $rataIndustri = $nilaisIndustri->avg('nilai');

        return view('guru.kesimpulan.show', compact(
            'penempatan', 'nilaisGuru', 'nilaisIndustri',
            'kesimpulan', 'rataGuru', 'rataIndustri'
        ));
    }

    public function store(Request $request, $penempatan_id)
    {
        $guru = auth()->user()->guru;

        $penempatan = PenempatanPkl::findOrFail($penempatan_id);

        if ($penempatan->guru_id != $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'nilai_kesimpulan' => 'required|numeric|min:0|max:100',
            'catatan_kesimpulan' => 'nullable|string',
        ], [
            'nilai_kesimpulan.required' => 'Nilai kesimpulan wajib diisi.',
            'nilai_kesimpulan.numeric' => 'Nilai kesimpulan harus berupa angka.',
            'nilai_kesimpulan.min' => 'Nilai kesimpulan minimal 0.',
            'nilai_kesimpulan.max' => 'Nilai kesimpulan maksimal 100.',
        ]);

        NilaiKesimpulan::updateOrCreate(
            [
                'penempatan_id' => $penempatan->id,
                'guru_id' => $guru->id,
            ],
            [
                'nilai_kesimpulan' => $request->nilai_kesimpulan,
                'catatan_kesimpulan' => $request->catatan_kesimpulan,
            ]
        );

        return redirect()->route('guru.kesimpulan.show', $penempatan->id)
            ->with('success', 'Nilai kesimpulan akhir berhasil disimpan.');
    }
}
