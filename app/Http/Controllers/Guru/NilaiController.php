<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\MonitoringNilaiRequest;
use App\Models\MonitoringNilai;
use App\Models\PenempatanPkl;
use Illuminate\Http\Request;

class NilaiController extends Controller
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
        return view('guru.nilai.index', compact('penempatans'));
    }

    // PERBAIKAN: create menerima Request, ambil penempatan_id dari query string
    public function create(Request $request)
    {
        $penempatan_id = $request->query('penempatan_id');

        if (!$penempatan_id) {
            return redirect()->route('guru.nilai.index')
                ->with('error', 'Penempatan tidak ditemukan.');
        }

        $penempatan = PenempatanPkl::with(['siswa', 'kompetensi'])
            ->findOrFail($penempatan_id);

        // Cek apakah guru ini bertanggung jawab
        if ($penempatan->guru_id != auth()->user()->guru->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('guru.nilai.create', compact('penempatan'));
    }

    public function store(MonitoringNilaiRequest $request)
    {
        $data = $request->validated();
        $data['guru_id'] = auth()->user()->guru->id;

        // Cek duplikat
        $exists = MonitoringNilai::where('penempatan_id', $data['penempatan_id'])
            ->where('aspek_penilaian', $data['aspek_penilaian'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Nilai untuk aspek ini sudah ada.');
        }

        MonitoringNilai::create($data);

        return redirect()->route('guru.nilai.index')
            ->with('success', 'Nilai berhasil ditambahkan.');
    }

    public function show($penempatan_id)
    {
        $penempatan = PenempatanPkl::with(['siswa', 'kompetensi'])
            ->findOrFail($penempatan_id);

        if ($penempatan->guru_id != auth()->user()->guru->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $nilais = MonitoringNilai::where('penempatan_id', $penempatan_id)->get();

        return view('guru.nilai.show', compact('penempatan', 'nilais'));
    }

    public function edit($id)
    {
        $nilai = MonitoringNilai::with('penempatan.siswa')->findOrFail($id);

        if ($nilai->guru_id != auth()->user()->guru->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('guru.nilai.edit', compact('nilai'));
    }

    public function update(MonitoringNilaiRequest $request, $id)
    {
        $nilai = MonitoringNilai::findOrFail($id);

        if ($nilai->guru_id != auth()->user()->guru->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $nilai->update($request->validated());

        return redirect()->route('guru.nilai.index')
            ->with('success', 'Nilai berhasil diupdate.');
    }

    public function destroy($id)
    {
        $nilai = MonitoringNilai::findOrFail($id);

        if ($nilai->guru_id != auth()->user()->guru->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $nilai->delete();

        return redirect()->route('guru.nilai.index')
            ->with('success', 'Nilai berhasil dihapus.');
    }
}
