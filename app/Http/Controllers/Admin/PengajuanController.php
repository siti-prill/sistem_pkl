<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = PengajuanPkl::with('siswa')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    public function show(int $id)
    {
        $pengajuan = PengajuanPkl::with(['siswa', 'penempatan.industri', 'penempatan.guru', 'penempatan.kompetensi'])->findOrFail($id);

        // Ambil semua data untuk dropdown
        $industris = \App\Models\Industri::all();
        $gurus = \App\Models\Guru::all();
        $kompetensis = \App\Models\Kompetensi::all();

        return view('admin.pengajuan.show', compact('pengajuan', 'industris', 'gurus', 'kompetensis'));
    }

    public function update(Request $request, int $id)
    {
        $pengajuan = PengajuanPkl::findOrFail($id);

        // Validasi dasar
        $rules = [
            'status' => 'required|in:pending,diterima,ditolak',
            'catatan_admin' => 'nullable|string',
        ];

        // Validasi berdasarkan status
        if ($request->status == 'diterima') {
            // Jika diterima, tempat_diterima wajib dari pilihan 1 atau 2
            $rules['tempat_diterima_select'] = 'required|string|max:255';
            $request->validate($rules);

            // Pastikan tempat_diterima_select ada di pilihan_1 atau pilihan_2
            $pilihan = [$pengajuan->pilihan_1, $pengajuan->pilihan_2];
            if (!in_array($request->tempat_diterima_select, $pilihan)) {
                return back()->with('error', 'Tempat diterima harus sesuai dengan pilihan 1 atau pilihan 2.');
            }

            $tempat_diterima = $request->tempat_diterima_select;
        } elseif ($request->status == 'ditolak') {
            // Jika ditolak, tempat_diterima boleh diisi atau tidak (opsional)
            $request->validate($rules);
            $tempat_diterima = $request->tempat_diterima_input ?? null;
        } else {
            // Pending - tidak perlu validasi tempat_diterima
            $request->validate($rules);
            $tempat_diterima = null;
        }

        // CEK: Jika status diubah dari 'diterima' ke 'pending' atau 'ditolak'
        if ($pengajuan->status == 'diterima' && in_array($request->status, ['pending', 'ditolak'])) {
            if ($pengajuan->penempatan_id) {
                return back()->with('error', 'Tidak dapat mengubah status karena sudah memiliki penempatan. Hapus penempatan terlebih dahulu.');
            }
        }

        // Update data
        $pengajuan->status = $request->status;
        $pengajuan->tempat_diterima = $tempat_diterima;
        $pengajuan->catatan_admin = $request->catatan_admin;
        $pengajuan->save();

        return redirect()->route('admin.pengajuan.show', $pengajuan->id)
            ->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}
