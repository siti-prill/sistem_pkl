<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndustriRequest;
use App\Models\Industri;
use Illuminate\Http\Request;

class IndustriController extends Controller
{
    public function index(Request $request)
    {
        $query = Industri::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_perusahaan', 'like', "%{$search}%")
                    ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                    ->orWhere('bidang_usaha', 'like', "%{$search}%")
                    ->orWhere('penanggung_jawab', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $industris = $query->paginate(10);
        return view('admin.industri.index', compact('industris'));
    }

    public function create()
    {
        return view('admin.industri.create');
    }

    public function store(IndustriRequest $request)
    {
        Industri::create($request->validated());
        return redirect()->route('admin.industri.index')
            ->with('success', 'Data industri berhasil ditambahkan.');
    }

    public function show(Industri $industri)
    {
        return view('admin.industri.show', compact('industri'));
    }

    public function edit(Industri $industri)
    {
        return view('admin.industri.edit', compact('industri'));
    }

    public function update(IndustriRequest $request, Industri $industri)
    {
        $data = $request->validated();

        // Pastikan data yang diupdate hanya field yang diizinkan
        $industri->update($data);

        return redirect()->route('admin.industri.index')
            ->with('success', 'Data industri berhasil diupdate.');
    }

    public function destroy(Industri $industri)
    {
        $industri->delete();
        return redirect()->route('admin.industri.index')
            ->with('success', 'Data industri berhasil dihapus.');
    }
}
