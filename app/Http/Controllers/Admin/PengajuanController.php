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
        $pengajuan = PengajuanPkl::with('siswa')->findOrFail($id);
        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak,pending',
            'tempat_diterima' => 'nullable|string|max:255',
            'catatan_admin' => 'nullable|string',
        ]);

        $pengajuan = PengajuanPkl::findOrFail($id);
        $pengajuan->update([
            'status' => $request->status,
            'tempat_diterima' => $request->tempat_diterima,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('admin.pengajuan.index')->with('success', 'Status pengajuan berhasil diperbarui!');
    }
}
