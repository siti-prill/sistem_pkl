<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplatePenilaian;
use Illuminate\Http\Request;

class TemplatePenilaianController extends Controller
{
    public function index(Request $request)
    {
        $jurusan = $request->get('jurusan');

        if (!$jurusan) {
            // Halaman pilih jurusan: ambil semua data untuk keperluan hitungan
            $templates = TemplatePenilaian::with('children')->get();
        } else {
            // Jika jurusan dipilih, ambil data jurusan tersebut + data umum (null)
            $query = TemplatePenilaian::with('children');

            if ($jurusan === 'umum') {
                // (Opsional) Jika tetap ingin akses halaman umum, ambil hanya null
                $query->whereNull('jurusan');
            } else {
                $query->where(function ($q) use ($jurusan) {
                    $q->where('jurusan', $jurusan)->orWhereNull('jurusan');
                });
            }

            $templates = $query->orderBy('kategori')->orderBy('urutan')->get();
        }

        // Filter untuk tampilan
        $kejuruanRoot = $templates->where('kategori', 'kejuruan')->whereNull('parent_id')->sortBy('urutan');
        $sikapItems = $templates->where('kategori', 'sikap')->sortBy('urutan');

        $totalAspek = $templates->count();
        $aspekAktif = $templates->where('is_active', true)->count();

        $jurusanList = TemplatePenilaian::JURUSAN_TEMPLATE_LIST; // ['RPL','TKJ','DKV','PSPT']

        return view('admin.template-penilaian.index', compact(
            'templates',
            'kejuruanRoot',
            'sikapItems',
            'totalAspek',
            'aspekAktif',
            'jurusanList',
            'jurusan'
        ));
    }

    public function create()
    {
        $lastUrutan = TemplatePenilaian::max('urutan') ?? 0;
        $komponens = TemplatePenilaian::where('tipe', 'komponen')->orderBy('kategori')->orderBy('urutan')->get();
        $jurusanList = TemplatePenilaian::JURUSAN_TEMPLATE_LIST;
        return view('admin.template-penilaian.create', compact('lastUrutan', 'komponens', 'jurusanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_aspek' => 'required|string|max:100',
            'kategori' => 'required|in:kejuruan,sikap',
            'jurusan' => 'nullable|string|max:100',
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
            'nama_aspek',
            'kategori',
            'jurusan',
            'parent_id',
            'tipe',
            'deskripsi',
            'instruksi',
            'rentang_nilai_min',
            'rentang_nilai_max',
            'urutan'
        ]);
        // Ubah 'umum' menjadi null
        $data['jurusan'] = $request->jurusan == 'umum' ? null : $request->jurusan;
        $data['is_active'] = $request->boolean('is_active', true);

        if ($data['tipe'] === 'komponen') {
            $data['parent_id'] = null;
        }

        TemplatePenilaian::create($data);

        return redirect()->route('admin.template-penilaian.index', $data['jurusan'] ? ['jurusan' => $data['jurusan']] : [])
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
        $jurusanList = TemplatePenilaian::JURUSAN_TEMPLATE_LIST;
        return view('admin.template-penilaian.edit', ['template' => $templatePenilaian, 'komponens' => $komponens, 'jurusanList' => $jurusanList]);
    }

    public function update(Request $request,int $id)
    {
        // validasi
        $request->validate([
            'nama_aspek' => 'required|string|max:255',
        ]);

        $item = TemplatePenilaian::findOrFail($id);
        $item->nama_aspek = $request->nama_aspek;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil diperbarui!',
            'data' => $item
        ]);
    }

    public function destroy(TemplatePenilaian $templatePenilaian)
    {
        $jurusan = $templatePenilaian->jurusan;
        $templatePenilaian->children()->update(['parent_id' => null]);
        $templatePenilaian->delete();
        return redirect()->route('admin.template-penilaian.index', !is_null($jurusan) ? ['jurusan' => $jurusan] : [])
            ->with('success', 'Template penilaian berhasil dihapus.');
    }

    public function toggleActive(TemplatePenilaian $templatePenilaian)
    {
        $jurusan = $templatePenilaian->jurusan;
        $templatePenilaian->update(['is_active' => !$templatePenilaian->is_active]);
        return redirect()->route('admin.template-penilaian.index', !is_null($jurusan) ? ['jurusan' => $jurusan] : []);
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
            'jurusan' => $parent->jurusan, // warisi jurusan dari parent (bisa null)
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
            'jurusan' => 'nullable|string|max:100',
            'nama_aspek' => 'required|string|max:100',
        ]);

        // Ubah 'umum' menjadi null
        $jurusan = $request->jurusan == 'umum' ? null : $request->jurusan;

        $lastUrutan = TemplatePenilaian::where('kategori', $request->kategori)
            ->where('jurusan', $jurusan)
            ->whereNull('parent_id')->max('urutan') ?? 0;

        $item = TemplatePenilaian::create([
            'nama_aspek' => $request->nama_aspek,
            'kategori' => $request->kategori,
            'jurusan' => $jurusan,
            'parent_id' => null,
            'tipe' => 'item',
            'rentang_nilai_min' => 0,
            'rentang_nilai_max' => 100,
            'urutan' => $lastUrutan + 1,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'id' => $item->id, 'nama_aspek' => $item->nama_aspek]);
    }

    public function storeTable(Request $request)
    {
        $request->validate([
            'nama_tabel' => 'required|string|max:100',
            'kategori' => 'required|in:kejuruan,sikap',
            'jurusan' => 'nullable|string|max:100',
        ]);

        $jurusan = $request->jurusan == 'umum' ? null : $request->jurusan;

        $lastUrutan = TemplatePenilaian::where('jurusan', $jurusan)->max('urutan') ?? 0;

        TemplatePenilaian::create([
            'nama_aspek' => $request->nama_tabel,
            'kategori' => $request->kategori,
            'jurusan' => $jurusan,
            'parent_id' => null,
            'tipe' => 'komponen',
            'deskripsi' => null,
            'instruksi' => null,
            'rentang_nilai_min' => 0,
            'rentang_nilai_max' => 100,
            'urutan' => $lastUrutan + 1,
            'is_active' => true,
        ]);

        return redirect()->route('admin.template-penilaian.index', !is_null($jurusan) ? ['jurusan' => $jurusan] : [])
            ->with('success', 'Tabel baru berhasil ditambahkan.');
    }

    public function destroyTable(Request $request)
    {
        $request->validate([
            'tabel_id' => 'required|exists:template_penilaian,id',
        ]);

        $tabel = TemplatePenilaian::findOrFail($request->tabel_id);
        $jurusan = $tabel->jurusan;

        $tabel->children()->delete();
        $tabel->delete();

        return redirect()->route('admin.template-penilaian.index', !is_null($jurusan) ? ['jurusan' => $jurusan] : [])
            ->with('success', 'Tabel berhasil dihapus.');
    }
}
