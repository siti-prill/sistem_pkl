<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PenempatanRequest;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Industri;
use App\Models\Kompetensi;
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

    public function store(PenempatanRequest $request)
    {
        PenempatanPkl::create($request->validated());
        return redirect()->route('admin.penempatan.index')
            ->with('success', 'Data penempatan PKL berhasil ditambahkan.');
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

    public function destroy(PenempatanPkl $penempatan)
    {
        $penempatan->delete();
        return redirect()->route('admin.penempatan.index')
            ->with('success', 'Data penempatan PKL berhasil dihapus.');
    }
}
