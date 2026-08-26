@extends('layouts.app')

@section('title', 'Template Penilaian')

@section('content')
<div class="animate-fadeIn">
    @if(!$jurusan)
        {{-- ============================================ --}}
        {{-- TAMPILAN AWAL: PILIH JURUSAN --}}
        {{-- ============================================ --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    <i class="fas fa-clipboard-list mr-2 text-indigo-500"></i> Template Penilaian
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih jurusan untuk melihat dan mengelola template penilaian</p>
            </div>
        </div>

        {{-- Kartu Jurusan --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $jurusanIcons = [
                    'RPL' => ['icon' => 'fas fa-code', 'color' => 'indigo', 'desc' => 'Rekayasa Perangkat Lunak'],
                    'TKJ' => ['icon' => 'fas fa-network-wired', 'color' => 'blue', 'desc' => 'Teknik Komputer & Jaringan'],
                    'DKV' => ['icon' => 'fas fa-palette', 'color' => 'purple', 'desc' => 'Desain Komunikasi Visual'],
                    'PSPT' => ['icon' => 'fas fa-broadcast-tower', 'color' => 'cyan', 'desc' => 'Pemanfaatan PSPT'],
                ];
                $counts = [
                    'RPL' => $templates->where('jurusan', 'RPL')->count(),
                    'TKJ' => $templates->where('jurusan', 'TKJ')->count(),
                    'DKV' => $templates->where('jurusan', 'DKV')->count(),
                    'PSPT' => $templates->where('jurusan', 'PSPT')->count(),
                ];
                $umumCount = $templates->filter(fn($t) => is_null($t->jurusan) || $t->jurusan === '')->count();
            @endphp

            @foreach($jurusanList as $j)
                @php $info = $jurusanIcons[$j]; @endphp
                <a href="{{ route('admin.template-penilaian.index', ['jurusan' => $j]) }}"
                   class="group bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-{{ $info['color'] }}-500 cursor-pointer">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-xl bg-{{ $info['color'] }}-100 dark:bg-{{ $info['color'] }}-900/40 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <i class="{{ $info['icon'] }} text-{{ $info['color'] }}-600 dark:text-{{ $info['color'] }}-300 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $j }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $info['desc'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Template</span>
                        <span class="text-lg font-bold text-{{ $info['color'] }}-600 dark:text-{{ $info['color'] }}-300">{{ $counts[$j] }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Template Umum --}}
        @if($umumCount > 0)
            <div class="mt-6">
                <a href="{{ route('admin.template-penilaian.index', ['jurusan' => 'umum']) }}"
                   class="block bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-gray-400 cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0">
                            <i class="fas fa-globe text-gray-500 dark:text-gray-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Umum (Semua Jurusan)</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Template yang berlaku untuk semua jurusan</p>
                        </div>
                        <div class="ml-auto">
                            <span class="text-lg font-bold text-gray-500 dark:text-gray-400">{{ $umumCount }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endif

    @else
        {{-- ============================================ --}}
        {{-- TAMPILAN TEMPLATE: SESUDAH PILIH JURUSAN --}}
        {{-- ============================================ --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('admin.template-penilaian.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" title="Kembali ke Pilihan Jurusan">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                        <i class="fas fa-clipboard-list mr-2 text-indigo-500"></i> Template: {{ strtoupper($jurusan) }}
                    </h2>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 ml-9">Kelola aspek penilaian untuk jurusan {{ strtoupper($jurusan) }}</p>
            </div>
        </div>

        {{-- Statistik --}}
        @php
            $totalKomponen = $templates->where('tipe', 'komponen')->count();
            $totalItem = $templates->where('tipe', 'item')->count();
            $aspekAktif = $templates->where('is_active', true)->count();
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center shrink-0">
                    <i class="fas fa-layer-group text-indigo-600 dark:text-indigo-300"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Komponen</p>
                    <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalKomponen }}</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center shrink-0">
                    <i class="fas fa-list-check text-blue-600 dark:text-blue-300"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Item</p>
                    <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalItem }}</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900 flex items-center justify-center shrink-0">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-300"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Aktif</p>
                    <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $aspekAktif }}</p>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- TABEL A: ASPEK KEJURUAN --}}
        {{-- ============================================ --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">A. Aspek Kejuruan</h3>
                <button onclick="showAddModal('kejuruan')" class="btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Item
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="table-fixed min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-12 text-center">No</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-left">Komponen Kompetensi Kejuruan</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-24 text-center">Status</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($kejuruanRoot as $komponen)
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center font-semibold">{{ $no++ }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 font-semibold">
                                    <span class="inline-edit cursor-pointer hover:bg-yellow-100 dark:hover:bg-yellow-900/30 px-2 py-1 rounded"
                                          data-id="{{ $komponen->id }}" data-field="nama_aspek"
                                          onclick="startEdit(this)">{{ $komponen->nama_aspek }}</span>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                    <form action="{{ route('admin.template-penilaian.toggle-active', $komponen) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs px-2 py-1 rounded {{ $komponen->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $komponen->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="showAddSubModal({{ $komponen->id }}, '{{ addslashes($komponen->nama_aspek) }}')" class="btn-info btn-sm" title="Tambah Sub-Item">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button onclick="showEditModal({{ $komponen->id }}, '{{ addslashes($komponen->nama_aspek) }}', '{{ addslashes($komponen->deskripsi ?? '') }}', '{{ addslashes($komponen->instruksi ?? '') }}', {{ $komponen->rentang_nilai_min }}, {{ $komponen->rentang_nilai_max }}, {{ $komponen->urutan }}, '{{ $komponen->kategori }}', '{{ $komponen->tipe }}')" class="btn-warning btn-sm" title="Edit Detail">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @foreach($komponen->children->sortBy('urutan') as $child)
                                <tr>
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center"></td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 pl-8">
                                        <span class="inline-edit cursor-pointer hover:bg-yellow-100 dark:hover:bg-yellow-900/30 px-2 py-1 rounded"
                                              data-id="{{ $child->id }}" data-field="nama_aspek"
                                              onclick="startEdit(this)">{{ $child->nama_aspek }}</span>
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                        <form action="{{ route('admin.template-penilaian.toggle-active', $child) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs px-2 py-1 rounded {{ $child->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $child->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button onclick="showEditModal({{ $child->id }}, '{{ addslashes($child->nama_aspek) }}', '{{ addslashes($child->deskripsi ?? '') }}', '{{ addslashes($child->instruksi ?? '') }}', {{ $child->rentang_nilai_min }}, {{ $child->rentang_nilai_max }}, {{ $child->urutan }}, '{{ $child->kategori }}', '{{ $child->tipe }}')" class="btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.template-penilaian.destroy', $child) }}" method="POST" class="inline" onsubmit="return confirm('Hapus aspek ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                    Belum ada komponen kejuruan. Klik "Tambah Item" untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- TABEL B: ASPEK SIKAP --}}
        {{-- ============================================ --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">B. Aspek Sikap</h3>
                <button onclick="showAddModal('sikap')" class="btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Item
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="table-fixed min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-12 text-center">No</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-left">Komponen Sikap</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-24 text-center">Status</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($sikapItems as $item)
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center font-semibold">{{ $no++ }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 font-semibold">
                                    <span class="inline-edit cursor-pointer hover:bg-yellow-100 dark:hover:bg-yellow-900/30 px-2 py-1 rounded"
                                          data-id="{{ $item->id }}" data-field="nama_aspek"
                                          onclick="startEdit(this)">{{ $item->nama_aspek }}</span>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                    <form action="{{ route('admin.template-penilaian.toggle-active', $item) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs px-2 py-1 rounded {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="showEditModal({{ $item->id }}, '{{ addslashes($item->nama_aspek) }}', '{{ addslashes($item->deskripsi ?? '') }}', '{{ addslashes($item->instruksi ?? '') }}', {{ $item->rentang_nilai_min }}, {{ $item->rentang_nilai_max }}, {{ $item->urutan }}, '{{ $item->kategori }}', '{{ $item->tipe }}')" class="btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.template-penilaian.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus aspek ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                    Belum ada aspek sikap. Klik "Tambah Item" untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- TOMBOL TAMBAH TABEL BARU --}}
        {{-- ============================================ --}}
        <div id="tambahTabelArea">
            <button onclick="showAddTabelModal()" class="w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-indigo-500 dark:hover:border-indigo-500 transition-all duration-300 cursor-pointer group">
                <div class="flex items-center justify-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-indigo-600 dark:text-indigo-300"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">+ Tambah Tabel Baru</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tambahkan kategori penilaian baru</p>
                    </div>
                </div>
            </button>
        </div>

        {{-- Tabel-tabel tambahan yang sudah ditambahkan --}}
        @if(isset($extraTables))
            @foreach($extraTables as $idx => $extraTable)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6 mt-6">
                    <div class="flex items-center justify-between mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $extraTable['label'] }}</h3>
                        <div class="flex gap-2">
                            <button onclick="showAddModalExtra({{ $idx }})" class="btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i> Tambah Item
                            </button>
                            <form action="{{ route('admin.template-penilaian.destroy-table') }}" method="POST" class="inline" onsubmit="return confirm('Hapus tabel ini dan semua isinya?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="tabel_id" value="{{ $extraTable['id'] }}">
                                <button type="submit" class="btn-danger btn-sm">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table-fixed min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-12 text-center">No</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-left">Nama Aspek</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-24 text-center">Status</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-28 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse($extraTable['items'] as $item)
                                    <tr>
                                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">{{ $no++ }}</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2">
                                            <span class="inline-edit cursor-pointer hover:bg-yellow-100 dark:hover:bg-yellow-900/30 px-2 py-1 rounded"
                                                  data-id="{{ $item->id }}" data-field="nama_aspek"
                                                  onclick="startEdit(this)">{{ $item->nama_aspek }}</span>
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                            <form action="{{ route('admin.template-penilaian.toggle-active', $item) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs px-2 py-1 rounded {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button onclick="showEditModal({{ $item->id }}, '{{ addslashes($item->nama_aspek) }}', '{{ addslashes($item->deskripsi ?? '') }}', '{{ addslashes($item->instruksi ?? '') }}', {{ $item->rentang_nilai_min }}, {{ $item->rentang_nilai_max }}, {{ $item->urutan }}, '{{ $item->kategori }}', '{{ $item->tipe }}')" class="btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.template-penilaian.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus aspek ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                            Belum ada item. Klik "Tambah Item" untuk menambahkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif

        {{-- Keterangan --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mt-6">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-2">Keterangan Nilai Angka dan Huruf (Otomatis)</h3>
            <table class="text-sm border border-gray-300 dark:border-gray-600">
                <tr>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">90 - 100</td>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1 font-semibold">A</td>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">( Sangat Kompeten )</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">80 - 89</td>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1 font-semibold">B</td>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">( Kompeten )</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">70 - 79</td>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1 font-semibold">C</td>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">( Cukup Kompeten )</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">&lt; 70</td>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1 font-semibold">D</td>
                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">( Kurang Kompeten )</td>
                </tr>
            </table>
        </div>
    @endif
</div>

{{-- ============================================ --}}
{{-- MODAL: TAMBAH ITEM BARU --}}
{{-- ============================================ --}}
<div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4" id="addModalTitle">Tambah Aspek Baru</h3>
        <input type="hidden" id="addModalKategori">
        <input type="hidden" id="addModalParentId">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Aspek</label>
            <input type="text" id="addModalInput" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Contoh: Kompetensi Dasar 1">
        </div>
        <div class="mb-4" id="addModalJurusanWrapper">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jurusan</label>
            <select id="addModalJurusan" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                <option value="">Umum (Semua Jurusan)</option>
                @foreach($jurusanList as $j)
                    <option value="{{ $j }}">{{ $j }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-3">
            <button onclick="submitAdd()" class="flex-1 font-medium py-2 px-4 rounded-lg transition" style="background:#22c55e;color:#000;font-weight:600;">Simpan</button>
            <button onclick="closeAddModal()" class="flex-1 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded-lg transition">Batal</button>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- MODAL: EDIT ITEM --}}
{{-- ============================================ --}}
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 w-full max-w-lg mx-4">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Edit Aspek Penilaian</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Aspek <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_aspek" id="editNamaAspek" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="editDeskripsi" rows="2" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instruksi Pengisian</label>
                    <textarea name="instruksi" id="editInstruksi" rows="2" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rentang Nilai Min <span class="text-red-500">*</span></label>
                        <input type="number" name="rentang_nilai_min" id="editMin" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" min="0" max="100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rentang Nilai Max <span class="text-red-500">*</span></label>
                        <input type="number" name="rentang_nilai_max" id="editMax" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" min="0" max="100" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Urutan <span class="text-red-500">*</span></label>
                        <input type="number" name="urutan" id="editUrutan" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" min="0" required>
                    </div>
                </div>
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="flex-1 font-medium py-2 px-4 rounded-lg transition" style="background:#22c55e;color:#000;font-weight:600;">Update</button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded-lg transition">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ============================================ --}}
{{-- MODAL: TAMBAH TABEL BARU --}}
{{-- ============================================ --}}
<div id="addTabelModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Tambah Tabel Baru</h3>
        <form id="addTabelForm" method="POST" action="{{ route('admin.template-penilaian.store-table') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Tabel <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_tabel" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Contoh: C. Aspek Disiplin" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                    <select name="kategori" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        <option value="kejuruan">Kejuruan</option>
                        <option value="sikap">Sikap</option>
                    </select>
                </div>
                <input type="hidden" name="jurusan" value="{{ $jurusan }}">
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition">Simpan</button>
                    <button type="button" onclick="closeAddTabelModal()" class="flex-1 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded-lg transition">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================
// INLINE EDIT (nama_aspek langsung di tabel)
// ============================================
function startEdit(el) {
    const id = el.dataset.id;
    const currentText = el.textContent.trim();
    const input = document.createElement('input');
    input.type = 'text';
    input.value = currentText;
    input.className = 'w-full border border-indigo-400 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-500';
    input.onblur = function() { saveEdit(el, id, this); };
    input.onkeydown = function(e) {
        if (e.key === 'Enter') this.blur();
        if (e.key === 'Escape') { el.textContent = currentText; el.style.display = ''; }
    };
    el.style.display = 'none';
    el.parentNode.insertBefore(input, el);
    input.focus();
    input.select();
}

function saveEdit(el, id, input) {
    const newVal = input.value.trim();
    input.remove();
    if (newVal === '' || newVal === el.textContent.trim()) {
        el.style.display = '';
        return;
    }
    fetch(`/admin/template-penilaian/${id}/inline`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ nama_aspek: newVal })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            el.textContent = data.nama_aspek;
            showToast('Berhasil diupdate!');
        }
    })
    .catch(() => { showToast('Gagal update', 'error'); });
    el.style.display = '';
}

// ============================================
// MODAL: TAMBAH ITEM
// ============================================
function showAddModal(kategori) {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
    document.getElementById('addModalKategori').value = kategori;
    document.getElementById('addModalParentId').value = '';
    document.getElementById('addModalTitle').textContent = kategori === 'kejuruan' ? 'Tambah Komponen Kejuruan Baru' : 'Tambah Aspek Sikap Baru';
    document.getElementById('addModalInput').value = '';
    document.getElementById('addModalJurusan').value = '{{ $jurusan }}';
    document.getElementById('addModalJurusanWrapper').classList.add('hidden');
    document.getElementById('addModalInput').focus();
}

function showAddSubModal(parentId, parentName) {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
    document.getElementById('addModalParentId').value = parentId;
    document.getElementById('addModalTitle').textContent = 'Tambah Sub-Item: ' + parentName;
    document.getElementById('addModalInput').value = '';
    document.getElementById('addModalJurusanWrapper').classList.add('hidden');
    document.getElementById('addModalInput').focus();
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addModal').classList.remove('flex');
}

function submitAdd() {
    const name = document.getElementById('addModalInput').value.trim();
    if (!name) return;
    const parentId = document.getElementById('addModalParentId').value;
    const kategori = document.getElementById('addModalKategori').value;
    const jurusan = document.getElementById('addModalJurusan').value;

    let url, body;
    if (parentId) {
        url = '/admin/template-penilaian/add-sub-item';
        body = { parent_id: parentId, nama_aspek: name };
    } else {
        url = '/admin/template-penilaian/add-item';
        body = { kategori: kategori, nama_aspek: name, jurusan: jurusan };
    }

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { location.reload(); }
    })
    .catch(() => showToast('Gagal menambahkan', 'error'));
}

// ============================================
// MODAL: EDIT ITEM (LENGKAP)
// ============================================
function showEditModal(id, nama, deskripsi, instruksi, min, max, urutan, kategori, tipe) {
    const form = document.getElementById('editForm');
    form.action = `/admin/template-penilaian/${id}`;
    document.getElementById('editNamaAspek').value = nama;
    document.getElementById('editDeskripsi').value = deskripsi;
    document.getElementById('editInstruksi').value = instruksi;
    document.getElementById('editMin').value = min;
    document.getElementById('editMax').value = max;
    document.getElementById('editUrutan').value = urutan;

    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    document.getElementById('editNamaAspek').focus();
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

// ============================================
// MODAL: TAMBAH TABEL BARU
// ============================================
function showAddTabelModal() {
    document.getElementById('addTabelModal').classList.remove('hidden');
    document.getElementById('addTabelModal').classList.add('flex');
    document.getElementById('addTabelModal').querySelector('input[name="nama_tabel"]').focus();
}

function closeAddTabelModal() {
    document.getElementById('addTabelModal').classList.add('hidden');
    document.getElementById('addTabelModal').classList.remove('flex');
}

// ============================================
// TOAST
// ============================================
function showToast(msg, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white text-sm font-medium shadow-lg ${type === 'error' ? 'bg-red-500' : 'bg-green-500'}`;
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}

// Close modals on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddModal();
        closeEditModal();
        closeAddTabelModal();
    }
});

// Close modals on backdrop click
document.querySelectorAll('#addModal, #editModal, #addTabelModal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });
});
</script>
@endsection
