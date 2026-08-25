<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplatePenilaian;
use Illuminate\Http\Request;

class TemplatePenilaianController extends Controller
{
    public function index(Request $request)
    {
        $templates = TemplatePenilaian::with('children')->orderBy('kategori')->orderBy('urutan')->get();

        $kejuruanRoot = $templates->where('kategori', 'kejuruan')->whereNull('parent_id')->sortBy('urutan');
        $sikapItems = $templates->where('kategori', 'sikap')->sortBy('urutan');

        $totalAspek = $templates->count();
        $aspekAktif = $templates->where('is_active', true)->count();

        return view('admin.template-penilaian.index', compact('templates', 'kejuruanRoot', 'sikapItems', 'totalAspek', 'aspekAktif'));
    }

    public function create()
    {
        $lastUrutan = TemplatePenilaian::max('urutan') ?? 0;
        $komponens = TemplatePenilaian::where('tipe', 'komponen')->orderBy('kategori')->orderBy('urutan')->get();
        return view('admin.template-penilaian.create', compact('lastUrutan', 'komponens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_aspek' => 'required|string|max:100',
            'kategori' => 'required|in:kejuruan,sikap',
            'tipe' => 'required|in:komponen,item',
            'parent_id' => 'nullable|exists:template_penilaian,id',
            'deskripsi' => 'nullable|string',
            'instruksi' => 'nullable|string',
            'rentang_nilai_min' => 'required|integer|min:0|max:100',
            'rentang_nilai_max' => 'required|integer|min:0|max:100|gte:rentang_nilai_min',
            'urutan' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'nama_aspek.required' => 'Nama aspek wajib diisi.',
            'rentang_nilai_min.required' => 'Rentang nilai minimal wajib diisi.',
            'rentang_nilai_max.required' => 'Rentang nilai maksimal wajib diisi.',
            'rentang_nilai_max.gte' => 'Rentang nilai maksimal harus lebih besar atau sama dengan minimal.',
        ]);

        $data = $request->only([
            'nama_aspek', 'kategori', 'parent_id', 'tipe',
            'deskripsi', 'instruksi',
            'rentang_nilai_min', 'rentang_nilai_max', 'urutan'
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($data['tipe'] === 'komponen') {
            $data['parent_id'] = null;
        }

        TemplatePenilaian::create($data);

        return redirect()->route('admin.template-penilaian.index')
            ->with('success', 'Template penilaian berhasil ditambahkan.');
    }

    public function show(TemplatePenilaian $templatePenilaian)
    {
        $templatePenilaian->load('children');
        return view('admin.template-penilaian.show', ['template' => $templatePenilaian]);
    }

    public function edit(TemplatePenilaian $templatePenilaian)
    {
        $komponens = TemplatePenilaian::where('tipe', 'komponen')
            ->where('id', '!=', $templatePenilaian->id)
            ->orderBy('kategori')->orderBy('urutan')->get();
        return view('admin.template-penilaian.edit', ['template' => $templatePenilaian, 'komponens' => $komponens]);
    }

    public function update(Request $request, TemplatePenilaian $templatePenilaian)
    {
        $request->validate([
            'nama_aspek' => 'required|string|max:100',
            'kategori' => 'required|in:kejuruan,sikap',
            'tipe' => 'required|in:komponen,item',
            'parent_id' => 'nullable|exists:template_penilaian,id',
            'deskripsi' => 'nullable|string',
            'instruksi' => 'nullable|string',
            'rentang_nilai_min' => 'required|integer|min:0|max:100',
            'rentang_nilai_max' => 'required|integer|min:0|max:100|gte:rentang_nilai_min',
            'urutan' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->only([
            'nama_aspek', 'kategori', 'parent_id', 'tipe',
            'deskripsi', 'instruksi',
            'rentang_nilai_min', 'rentang_nilai_max', 'urutan'
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($data['tipe'] === 'komponen') {
            $data['parent_id'] = null;
        }

        $templatePenilaian->update($data);

        return redirect()->route('admin.template-penilaian.index')
            ->with('success', 'Template penilaian berhasil diupdate.');
    }

    public function destroy(TemplatePenilaian $templatePenilaian)
    {
        $templatePenilaian->children()->update(['parent_id' => null]);
        $templatePenilaian->delete();
        return redirect()->route('admin.template-penilaian.index')
            ->with('success', 'Template penilaian berhasil dihapus.');
    }

    public function toggleActive(TemplatePenilaian $templatePenilaian)
    {
        $templatePenilaian->update(['is_active' => !$templatePenilaian->is_active]);
        return back()->with('success', 'Status template penilaian berhasil diubah.');
    }

    public function updateInline(Request $request, TemplatePenilaian $templatePenilaian)
    {
        $request->validate([
            'nama_aspek' => 'required|string|max:100',
        ]);

        $templatePenilaian->update(['nama_aspek' => $request->nama_aspek]);

        return response()->json(['success' => true, 'nama_aspek' => $templatePenilaian->nama_aspek]);
    }

    public function addSubItem(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:template_penilaian,id',
            'nama_aspek' => 'required|string|max:100',
        ]);

        $parent = TemplatePenilaian::findOrFail($request->parent_id);

        $lastUrutan = TemplatePenilaian::where('parent_id', $parent->id)->max('urutan') ?? $parent->urutan;

        $item = TemplatePenilaian::create([
            'nama_aspek' => $request->nama_aspek,
            'kategori' => $parent->kategori,
            'parent_id' => $parent->id,
            'tipe' => 'item',
            'rentang_nilai_min' => 0,
            'rentang_nilai_max' => 100,
            'urutan' => $lastUrutan + 1,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'id' => $item->id, 'nama_aspek' => $item->nama_aspek]);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:kejuruan,sikap',
            'nama_aspek' => 'required|string|max:100',
        ]);

        $lastUrutan = TemplatePenilaian::where('kategori', $request->kategori)->whereNull('parent_id')->max('urutan') ?? 0;

        $item = TemplatePenilaian::create([
            'nama_aspek' => $request->nama_aspek,
            'kategori' => $request->kategori,
            'parent_id' => null,
            'tipe' => 'item',
            'rentang_nilai_min' => 0,
            'rentang_nilai_max' => 100,
            'urutan' => $lastUrutan + 1,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'id' => $item->id, 'nama_aspek' => $item->nama_aspek]);
    }
}
