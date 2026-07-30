<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use App\Models\Siswa;
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
        // Cek apakah sudah punya pengajuan
        $existing = PengajuanPkl::where('siswa_id', $siswa->id)->first();
        if ($existing) {
            return redirect()->route('siswa.pengajuan.index')->with('info', 'Anda sudah mengajukan permohonan PKL.');
        }
        return view('siswa.pengajuan.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pilihan_1' => 'required|string|max:100',
            'pilihan_2' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
            'penghasilan_ortu' => 'nullable|numeric|min:0',
        ]);

        $siswa = Auth::user()->siswa;

        PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'pilihan_1' => $request->pilihan_1,
            'pilihan_2' => $request->pilihan_2,
            'jurusan' => $request->jurusan,
            'penghasilan_ortu' => $request->penghasilan_ortu,
            'status' => 'pending',
        ]);

        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan PKL berhasil dikirim!');
    }
}
