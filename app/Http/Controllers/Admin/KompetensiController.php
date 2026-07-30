<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KompetensiRequest;
use App\Models\Kompetensi;
use Illuminate\Http\Request;

class KompetensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Kompetensi::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_kompetensi', 'like', "%{$search}%")
                    ->orWhere('nama_kompetensi', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $kompetensis = $query->paginate(10);
        return view('admin.kompetensi.index', compact('kompetensis'));
    }

    public function create()
    {
        return view('admin.kompetensi.create');
    }

    public function store(KompetensiRequest $request)
    {
        Kompetensi::create($request->validated());
        return redirect()->route('admin.kompetensi.index')
            ->with('success', 'Data kompetensi berhasil ditambahkan.');
    }

    public function show(Kompetensi $kompetensi)
    {
        return view('admin.kompetensi.show', compact('kompetensi'));
    }

    public function edit(Kompetensi $kompetensi)
    {
        return view('admin.kompetensi.edit', compact('kompetensi'));
    }

    public function update(KompetensiRequest $request, Kompetensi $kompetensi)
    {
        $data = $request->validated();
        $kompetensi->update($data);

        return redirect()->route('admin.kompetensi.index')
            ->with('success', 'Data kompetensi berhasil diupdate.');
    }

    public function destroy(Kompetensi $kompetensi)
    {
        $kompetensi->delete();
        return redirect()->route('admin.kompetensi.index')
            ->with('success', 'Data kompetensi berhasil dihapus.');
    }
}
