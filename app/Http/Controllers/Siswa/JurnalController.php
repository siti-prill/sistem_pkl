<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\JurnalRequest;
use App\Models\JurnalHarian;
use App\Models\PenempatanPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $query = JurnalHarian::where('penempatan_id', $penempatan->id);

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
        $data = $request->validated();

        // Upload file jika ada
        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dokumentasi', $filename, 'public');
            $data['dokumentasi'] = $path;
        }

        // Cek duplikat
        $exists = JurnalHarian::where('penempatan_id', $data['penempatan_id'])
            ->whereDate('tanggal', $data['tanggal'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Jurnal untuk tanggal ini sudah ada.');
        }

        JurnalHarian::create($data);

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

        $data = $request->validated();

        if ($request->hasFile('dokumentasi')) {
            // Hapus file lama
            if ($jurnal->dokumentasi) {
                Storage::disk('public')->delete($jurnal->dokumentasi);
            }

            $file = $request->file('dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dokumentasi', $filename, 'public');
            $data['dokumentasi'] = $path;
        }

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

        if ($jurnal->dokumentasi) {
            Storage::disk('public')->delete($jurnal->dokumentasi);
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
