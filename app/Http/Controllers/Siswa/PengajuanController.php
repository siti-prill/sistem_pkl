<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Industri;
use App\Models\Kompetensi;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        $pengajuan = PengajuanPkl::where('siswa_id', $siswa->id)->first();

        return view('siswa.pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        $siswa = Auth::user()->siswa;
        // Cek apakah sudah punya pengajuan (kecuali yang ditolak, boleh ajukan ulang)
        $existing = PengajuanPkl::where('siswa_id', $siswa->id)->first();
        if ($existing && $existing->status != 'ditolak') {
            return redirect()->route('siswa.pengajuan.index')->with('info', 'Anda sudah mengajukan permohonan PKL.');
        }
        $jurusanList = Kompetensi::orderBy('nama_kompetensi')->pluck('nama_kompetensi');
        $industriByLokasi = Industri::where('status', 'aktif')
            ->orderBy('nama_perusahaan')
            ->get()
            ->groupBy('lokasi');

        return view('siswa.pengajuan.create', compact('siswa', 'jurusanList', 'industriByLokasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pilihan_1' => 'required|string|max:100',
            'pilihan_2' => 'required|string|max:100',
            'pekerjaan_orang_tua' => 'required|string|max:100',
            'penghasilan_ortu' => 'required|string|max:100',
            'alamat' => 'required|string|max:100',
            'industri_1' => 'nullable|string|max:255',
            'industri_2' => 'nullable|string|max:255',
        ]);

        $siswa = Auth::user()->siswa;

        $data = [
            'pilihan_1' => $request->pilihan_1,
            'pilihan_2' => $request->pilihan_2,
            'industri_1' => $request->industri_1,
            'industri_2' => $request->industri_2,
            'jurusan' => $siswa->jurusan,
            'pekerjaan_orang_tua' => $request->pekerjaan_orang_tua,
            'penghasilan_ortu' => $request->penghasilan_ortu,
            'alamat' => $request->alamat,
            'status' => 'pending',
        ];

        // Kalau pengajuan sebelumnya ditolak, perbarui saja yang lama
        $existing = PengajuanPkl::where('siswa_id', $siswa->id)->where('status', 'ditolak')->first();
        if ($existing) {
            $existing->update($data);
        } else {
            PengajuanPkl::create(array_merge($data, ['siswa_id' => $siswa->id]));
        }

        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan PKL berhasil dikirim!');
    }
}
