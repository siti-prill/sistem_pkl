<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\JurnalRequest;
use App\Models\JurnalHarian;
use App\Models\PenempatanPkl;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            return redirect()->route('home')->with('error', 'Data siswa tidak ditemukan.');
        }

        $penempatan = PenempatanPkl::where('siswa_id', $siswa->id)
            ->where('status', 'aktif')
            ->first();

        if (!$penempatan) {
            return view('siswa.jurnal.index', [
                'jurnals' => collect([]),
                'penempatan' => null
            ])->with('error', 'Anda belum memiliki penempatan PKL aktif.');
        }

        $query = JurnalHarian::with('komentarJurnal.guru')
            ->where('penempatan_id', $penempatan->id);

        // Filter by date
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $jurnals = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('siswa.jurnal.index', compact('jurnals', 'penempatan'));
    }

    public function create()
    {
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            return redirect()->route('home')->with('error', 'Data siswa tidak ditemukan.');
        }

        $penempatan = PenempatanPkl::where('siswa_id', $siswa->id)
            ->where('status', 'aktif')
            ->first();

        if (!$penempatan) {
            return redirect()->route('siswa.jurnal.index')
                ->with('error', 'Anda belum memiliki penempatan PKL aktif.');
        }

        return view('siswa.jurnal.create', compact('penempatan'));
    }

    public function store(JurnalRequest $request)
    {
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan.');
        }

        // Penempatan SELALU diambil dari akun siswa yang login,
        // bukan dari input request (mencegah IDOR).
        $penempatan = PenempatanPkl::where('siswa_id', $siswa->id)
            ->where('status', 'aktif')
            ->first();

        if (!$penempatan) {
            return redirect()->route('siswa.jurnal.index')
                ->with('error', 'Anda belum memiliki penempatan PKL aktif.');
        }

        $data = $request->validated();
        $data['penempatan_id'] = $penempatan->id;
        $data['status'] = 'draft';

        // Dokumentasi berupa link (mis. link file/foto di Google Drive)
        $data['dokumentasi'] = !empty($data['dokumentasi']) ? $data['dokumentasi'] : null;

        // Cek duplikat
        $exists = JurnalHarian::where('penempatan_id', $penempatan->id)
            ->whereDate('tanggal', $data['tanggal'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Jurnal untuk tanggal ini sudah ada.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                JurnalHarian::create($data);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // UNIQUE (penempatan_id, tanggal) menangkap race condition
            return back()->with('error', 'Jurnal untuk tanggal ini sudah ada.');
        }

        return redirect()->route('siswa.jurnal.index')
            ->with('success', 'Jurnal berhasil disimpan.');
    }

    public function show(JurnalHarian $jurnal)
    {
        $this->authorizeJurnal($jurnal);
        return view('siswa.jurnal.show', compact('jurnal'));
    }

    public function edit(JurnalHarian $jurnal)
    {
        $this->authorizeJurnal($jurnal);

        if ($jurnal->status == 'submitted') {
            return redirect()->route('siswa.jurnal.index')
                ->with('error', 'Jurnal yang sudah disubmit tidak dapat diedit.');
        }

        return view('siswa.jurnal.edit', compact('jurnal'));
    }

    public function update(JurnalRequest $request, JurnalHarian $jurnal)
    {
        $this->authorizeJurnal($jurnal);

        if ($jurnal->status == 'submitted') {
            return redirect()->route('siswa.jurnal.index')
                ->with('error', 'Jurnal yang sudah disubmit tidak dapat diupdate.');
        }

        // Buang field yang tidak boleh diubah dari input user
        // (penempatan_id tidak bisa dipindahkan ke penempatan orang lain)
        $data = $request->validated();
        unset($data['penempatan_id'], $data['status']);

        $data['dokumentasi'] = !empty($data['dokumentasi']) ? $data['dokumentasi'] : null;

        $jurnal->update($data);

        return redirect()->route('siswa.jurnal.index')
            ->with('success', 'Jurnal berhasil diupdate.');
    }

    public function destroy(JurnalHarian $jurnal)
    {
        $this->authorizeJurnal($jurnal);

        if ($jurnal->status == 'submitted') {
            return redirect()->route('siswa.jurnal.index')
                ->with('error', 'Jurnal yang sudah disubmit tidak dapat dihapus.');
        }

        $jurnal->delete();

        return redirect()->route('siswa.jurnal.index')
            ->with('success', 'Jurnal berhasil dihapus.');
    }

    public function submit(Request $request, JurnalHarian $jurnal)
    {
        $this->authorizeJurnal($jurnal);

        if ($jurnal->status == 'submitted') {
            return back()->with('error', 'Jurnal sudah disubmit.');
        }

        $jurnal->update(['status' => 'submitted']);

        return redirect()->route('siswa.jurnal.index')
            ->with('success', 'Jurnal berhasil disubmit.');
    }

    public function dokumentasi(JurnalHarian $jurnal)
    {
        // Akses via route siswa; delegasi ke controller umum agar perilaku konsisten (data lama)
        return app(\App\Http\Controllers\JurnalDokumentasiController::class)($jurnal);
    }

    private function authorizeJurnal($jurnal)
    {
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan.');
        }

        $penempatan = PenempatanPkl::where('siswa_id', $siswa->id)->first();

        if (!$penempatan) {
            abort(403, 'Penempatan PKL tidak ditemukan.');
        }

        if ($jurnal->penempatan_id != $penempatan->id) {
            abort(403, 'Anda tidak memiliki akses ke jurnal ini.');
        }
    }
}
