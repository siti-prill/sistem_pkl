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
        $siswas = Siswa::all();
        $gurus = Guru::all();
        $industris = Industri::where('status', 'aktif')->get();
        $kompetensis = Kompetensi::all();

        return view('admin.penempatan.create', compact('siswas', 'gurus', 'industris', 'kompetensis'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuan_pkl,id',
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|exists:industris,id',
            'guru_id' => 'required|exists:gurus,id',
            'kompetensi_id' => 'required|exists:kompetensis,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:aktif,selesai,batal',
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

        // CEK 5: Jika status = 'ditolak', pastikan industri BUKAN pilihan 1 atau 2
        if ($pengajuan->status == 'ditolak') {
            $industri = Industri::findOrFail($request->industri_id);
            if (
                $industri->nama_perusahaan == $pengajuan->pilihan_1 ||
                $industri->nama_perusahaan == $pengajuan->pilihan_2
            ) {
                return back()->with('error', 'Untuk pengajuan ditolak, tidak boleh memilih industri pilihan 1 atau 2.');
            }
        }

        // CEK 6: Jika status = 'diterima', pastikan industri sesuai dengan tempat_diterima
        if ($pengajuan->status == 'diterima') {
            $industri = Industri::findOrFail($request->industri_id);
            if ($industri->nama_perusahaan != $pengajuan->tempat_diterima) {
                return back()->with('error', 'Industri yang dipilih harus sesuai dengan tempat diterima: ' . $pengajuan->tempat_diterima);
            }
        }

        // CEK 7: Cek kuota industri
        $penempatanAktif = PenempatanPkl::where('industri_id', $request->industri_id)
            ->where('status', 'aktif')
            ->count();

        if ($penempatanAktif >= $industri->kuota) {
            return back()->with('error', 'Kuota industri sudah penuh. Sisa kuota: 0');
        }

        // Create penempatan
        $penempatan = PenempatanPkl::create([
            'siswa_id' => $request->siswa_id,
            'industri_id' => $request->industri_id,
            'guru_id' => $request->guru_id,
            'kompetensi_id' => $request->kompetensi_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ]);

        // Update pengajuan dengan penempatan_id
        $pengajuan->penempatan_id = $penempatan->id;
        $pengajuan->save();

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

        if ($pengajuan) {
            // Update pengajuan: hapus penempatan_id dan ubah status ke pending
            $pengajuan->penempatan_id = null;
            $pengajuan->status = 'pending';
            $pengajuan->save();
        }

        // Hapus penempatan
        $penempatan->delete();

        // Cek dari mana request berasal
        if ($pengajuan) {
            return redirect()->route('admin.pengajuan.show', $pengajuan->id)
                ->with('success', 'Penempatan berhasil dihapus. Status pengajuan dikembalikan ke Pending.');
        }

        return redirect()->route('admin.penempatan.index')
            ->with('success', 'Penempatan berhasil dihapus.');
    }
}
