<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PenempatanRequest;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Industri;
use App\Models\Kompetensi;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;

class PenempatanController extends Controller
{
    public function index(Request $request)
    {
        $query = PenempatanPkl::with(['siswa', 'industri', 'guru', 'kompetensi']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama_siswa', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                })->orWhereHas('industri', function ($q) use ($search) {
                    $q->where('nama_perusahaan', 'like', "%{$search}%");
                })->orWhereHas('guru', function ($q) use ($search) {
                    $q->where('nama_guru', 'like', "%{$search}%");
                });
            });
        }

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $penempatans = $query->paginate(10);
        return view('admin.penempatan.index', compact('penempatans'));
    }

    public function create()
    {
        // Hanya pengajuan berstatus diterima/ditolak dan belum punya penempatan
        // yang boleh dibuatkan penempatan (sesuai aturan di store())
        $pengajuans = PengajuanPkl::with('siswa')
            ->whereIn('status', ['diterima', 'ditolak'])
            ->whereNull('penempatan_id')
            ->latest()
            ->get();
        $gurus = Guru::all();
        $industris = Industri::where('status', 'aktif')->get();
        $kompetensis = Kompetensi::all();

        return view('admin.penempatan.create', compact('pengajuans', 'gurus', 'industris', 'kompetensis'));
    }

    public function store(Request $request)
    {
        // Form create mandiri hanya mengirim pengajuan_id (tanpa siswa_id),
        // jadi isi otomatis siswa_id dari data pengajuan sebelum validasi.
        if (! $request->filled('siswa_id') && $request->filled('pengajuan_id')) {
            $request->merge([
                'siswa_id' => PengajuanPkl::find($request->pengajuan_id)?->siswa_id,
            ]);
        }

        // Validasi input
        // Catatan: industri_id boleh berisi id industri ATAU string "lainnya"
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuan_pkl,id',
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|string',
            'industri_lainnya' => 'nullable|string|max:255',
            'guru_id' => 'required|exists:gurus,id',
            'kompetensi_id' => 'required|exists:kompetensis,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:aktif,selesai,batal',
        ], [
            'industri_id.required' => 'Industri wajib dipilih.',
        ]);

        // CEK 1: Ambil data pengajuan
        $pengajuan = PengajuanPkl::findOrFail($request->pengajuan_id);

        // CEK 2: Pastikan status pengajuan = 'diterima' ATAU 'ditolak'
        if ($pengajuan->status != 'diterima' && $pengajuan->status != 'ditolak') {
            return back()->with('error', 'Pengajuan harus berstatus Diterima atau Ditolak untuk membuat penempatan.');
        }

        // CEK 3: Pastikan pengajuan belum punya penempatan
        if ($pengajuan->penempatan_id) {
            return back()->with('error', 'Pengajuan ini sudah memiliki penempatan.');
        }

        // CEK 4: Pastikan siswa_id sesuai dengan pengajuan
        if ($pengajuan->siswa_id != $request->siswa_id) {
            return back()->with('error', 'Data siswa tidak sesuai dengan pengajuan.');
        }

        try {
            $penempatan = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $pengajuan) {
                // Handle industri "Lainnya" - buat record industri baru
                if ($request->industri_id === 'lainnya') {
                    $request->validate([
                        'industri_lainnya' => 'required|string|max:255',
                    ], [
                        'industri_lainnya.required' => 'Nama kota/daerah industri lainnya wajib diisi.',
                    ]);

                    $kategori = $pengajuan->siswa->kategori_jurusan ?? 'Semua';

                    $industri = Industri::create([
                        'kode_perusahaan' => 'LAINNYA-' . strtoupper(uniqid()),
                        'nama_perusahaan' => 'Industri di ' . $request->industri_lainnya,
                        'lokasi' => $request->industri_lainnya,
                        'alamat' => $request->industri_lainnya,
                        'no_telepon' => '-',
                        'email' => '-',
                        'bidang_usaha' => '-',
                        'jurusan' => $kategori,
                        'kategori' => $kategori,
                        'penanggung_jawab' => '-',
                        'kuota' => 1,
                        'status' => 'aktif',
                    ]);
                } else {
                    // Pastikan id industri valid
                    $request->validate([
                        'industri_id' => 'exists:industris,id',
                    ], [
                        'industri_id.exists' => 'Industri tidak valid.',
                    ]);

                    // Lock baris industri selama transaksi agar dua admin yang
                    // submit bersamaan tidak bisa melewati kuota (race condition)
                    $industri = Industri::whereKey($request->industri_id)->lockForUpdate()->firstOrFail();

                    // CEK 5: Cek kuota industri (di dalam lock)
                    $penempatanAktif = PenempatanPkl::where('industri_id', $industri->id)
                        ->where('status', 'aktif')
                        ->count();

                    if ($penempatanAktif >= $industri->kuota) {
                        throw new \App\Exceptions\KuotaPenuhException('Kuota industri sudah penuh. Sisa kuota: 0');
                    }
                }

                // Create penempatan
                $penempatan = PenempatanPkl::create([
                    'siswa_id' => $request->siswa_id,
                    'industri_id' => $industri->id,
                    'guru_id' => $request->guru_id,
                    'kompetensi_id' => $request->kompetensi_id,
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                    'status' => $request->status,
                ]);

                // Update pengajuan dengan penempatan_id
                $pengajuan->penempatan_id = $penempatan->id;

                // Tempat diterima otomatis mengikuti wilayah (lokasi) industri penempatan.
                // Wilayah industri sudah ditentukan saat data industri dibuat.
                $pengajuan->tempat_diterima = $industri->lokasi;

                $pengajuan->save();

                return $penempatan;
            });
        } catch (\App\Exceptions\KuotaPenuhException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.pengajuan.show', $pengajuan->id)
            ->with('success', 'Penempatan berhasil dibuat untuk siswa ini.');
    }

    public function show(PenempatanPkl $penempatan)
    {
        return view('admin.penempatan.show', compact('penempatan'));
    }

    public function edit(PenempatanPkl $penempatan)
    {
        $siswas = Siswa::all();
        $gurus = Guru::all();
        $industris = Industri::where('status', 'aktif')->get();
        $kompetensis = Kompetensi::all();

        return view('admin.penempatan.edit', compact('penempatan', 'siswas', 'gurus', 'industris', 'kompetensis'));
    }

    public function update(PenempatanRequest $request, PenempatanPkl $penempatan)
    {
        $penempatan->update($request->validated());
        return redirect()->route('admin.penempatan.index')
            ->with('success', 'Data penempatan PKL berhasil diupdate.');
    }

    public function destroy(int $id)
    {
        $penempatan = PenempatanPkl::findOrFail($id);

        // Cari pengajuan yang memiliki penempatan ini
        $pengajuan = PengajuanPkl::where('penempatan_id', $id)->first();

        \Illuminate\Support\Facades\DB::transaction(function () use ($penempatan, $pengajuan, $id) {
            if ($pengajuan) {
                // Update pengajuan: hapus penempatan_id dan ubah status ke pending
                $pengajuan->penempatan_id = null;
                $pengajuan->status = 'pending';
                $pengajuan->tempat_diterima = null;
                $pengajuan->save();
            }

            // Hapus penempatan (jurnal & nilai terkait ikut terhapus via cascade)
            $penempatan->delete();
        });

        // Cek dari mana request berasal
        if ($pengajuan) {
            return redirect()->route('admin.pengajuan.show', $pengajuan->id)
                ->with('success', 'Penempatan berhasil dihapus. Status pengajuan dikembalikan ke Pending.');
        }

        return redirect()->route('admin.penempatan.index')
            ->with('success', 'Penempatan berhasil dihapus.');
    }
}
