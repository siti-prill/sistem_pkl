@extends('layouts.app')

@section('title', 'Template Penilaian')

@section('content')
    @php
        if (!isset($komponens)) {
            $komponens = collect();
        }
        if (!isset($lastUrutan)) {
            $lastUrutan = 0;
        }
    @endphp

    <div class="animate-fadeIn">
        @if (!$jurusan)
            {{-- Pilih Jurusan --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                        <i class="fas fa-clipboard-list mr-2 text-indigo-500"></i> Template Penilaian
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih jurusan untuk melihat dan mengelola
                        template penilaian</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $jurusanIcons = [
                        'RPL' => ['icon' => 'fas fa-code', 'color' => 'indigo', 'desc' => 'Rekayasa Perangkat Lunak'],
                        'TKJ' => [
                            'icon' => 'fas fa-network-wired',
                            'color' => 'blue',
                            'desc' => 'Teknik Komputer & Jaringan',
                        ],
                        'DKV' => [
                            'icon' => 'fas fa-palette',
                            'color' => 'purple',
                            'desc' => 'Desain Komunikasi Visual',
                        ],
                        'PSPT' => ['icon' => 'fas fa-broadcast-tower', 'color' => 'cyan', 'desc' => 'Produksi dan Siaran Program Televisi'],
                    ];
                    $counts = [
                        'RPL' => $templates->where('jurusan', 'RPL')->count(),
                        'TKJ' => $templates->where('jurusan', 'TKJ')->count(),
                        'DKV' => $templates->where('jurusan', 'DKV')->count(),
                        'PSPT' => $templates->where('jurusan', 'PSPT')->count(),
                    ];
                    $umumCount = $templates->filter(fn($t) => is_null($t->jurusan) || $t->jurusan === '')->count();
                @endphp

                @foreach ($jurusanList as $j)
                    @php $info = $jurusanIcons[$j]; @endphp
                    <a href="{{ route('admin.template-penilaian.index', ['jurusan' => $j]) }}"
                        class="group bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-{{ $info['color'] }}-500 cursor-pointer">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-14 h-14 rounded-xl bg-{{ $info['color'] }}-100 dark:bg-{{ $info['color'] }}-900/40 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                <i
                                    class="{{ $info['icon'] }} text-{{ $info['color'] }}-600 dark:text-{{ $info['color'] }}-300 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $j }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $info['desc'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Template</span>
                            <span
                                class="text-lg font-bold text-{{ $info['color'] }}-600 dark:text-{{ $info['color'] }}-300">{{ $counts[$j] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($umumCount > 0)
                <div class="mt-6">
                    <a href="{{ route('admin.template-penilaian.index', ['jurusan' => 'umum']) }}"
                        class="block bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-gray-400 cursor-pointer">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0">
                                <i class="fas fa-globe text-gray-500 dark:text-gray-400 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Umum (Semua Jurusan)</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Template yang berlaku untuk semua
                                    jurusan</p>
                            </div>
                            <div class="ml-auto">
                                <span class="text-lg font-bold text-gray-500 dark:text-gray-400">{{ $umumCount }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @else
            {{-- Tampilan Template --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <a href="{{ route('admin.template-penilaian.index') }}"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
                            title="Kembali ke Pilihan Jurusan">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </a>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                            <i class="fas fa-clipboard-list mr-2 text-indigo-500"></i> Template: {{ strtoupper($jurusan) }}
                        </h2>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 ml-9">Kelola aspek penilaian untuk jurusan
                        {{ strtoupper($jurusan) }}</p>
                </div>
            </div>

            {{-- Statistik --}}
            @php
                $totalKomponen = $templates->where('tipe', 'komponen')->count();
                $totalItem = $templates->where('tipe', 'item')->count();
            @endphp
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center shrink-0">
                        <i class="fas fa-layer-group text-indigo-600 dark:text-indigo-300"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Komponen</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalKomponen }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center shrink-0">
                        <i class="fas fa-list-check text-blue-600 dark:text-blue-300"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Item</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalItem }}</p>
                    </div>
                </div>
            </div>

            {{-- TABEL A: ASPEK KEJURUAN --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6 overflow-x-auto">
                <div class="flex flex-wrap items-center justify-between mb-4 border-b pb-2 gap-2">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">A. Aspek Kejuruan</h3>
                    <button onclick="toggleAddRoot('kejuruan')" class="btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Baris
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-fixed min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-12 text-center">
                                    No
                                </th>

                                <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-left">
                                    Nama Aspek
                                </th>

                                <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-40 text-center">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($kejuruanRoot as $komponen)
                                {{-- Baris data --}}
                                <tr class="bg-gray-50 dark:bg-gray-700 data-row" data-id="{{ $komponen->id }}">
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center font-semibold">
                                        {{ $no++ }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 font-semibold">
                                        <span class="display-name"
                                            data-id="{{ $komponen->id }}">{{ $komponen->nama_aspek }}</span>
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                        <div class="flex justify-center gap-1 sm:gap-2 flex-wrap">
                                            <button onclick="toggleEditItem({{ $komponen->id }})"
                                                class="btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.template-penilaian.destroy', $komponen) }}"
                                                method="POST" class="inline" onsubmit="return confirm('Hapus aspek ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <button onclick="toggleAddSub({{ $komponen->id }})" class="btn-info btn-sm"
                                                title="Tambah Sub-Item">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Baris edit (hanya nama_aspek) --}}
                                <tr id="edit-row-{{ $komponen->id }}" class="hidden edit-row">
                                    <td colspan="3"
                                        class="border border-gray-300 dark:border-gray-600 px-3 py-2 bg-yellow-50 dark:bg-yellow-900/20">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-sm font-semibold text-gray-500 dark:text-gray-400 w-12 text-center">✎</span>
                                            <input type="text" id="edit-input-{{ $komponen->id }}"
                                                value="{{ $komponen->nama_aspek }}"
                                                class="flex-1 border rounded px-3 py-1 text-sm">
                                            <button onclick="submitEdit({{ $komponen->id }})"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button onclick="cancelEdit({{ $komponen->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Baris tambah sub --}}
                                <tr id="add-sub-row-{{ $komponen->id }}" class="hidden">
                                    <td colspan="3"
                                        class="border border-gray-300 dark:border-gray-600 px-3 py-2 bg-blue-50 dark:bg-blue-900/20">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-500 dark:text-gray-400 ml-6">↳</span>
                                            <input type="text" id="sub-input-{{ $komponen->id }}"
                                                placeholder="Nama sub-item..."
                                                class="flex-1 border rounded px-3 py-1 text-sm">
                                            <button onclick="submitSub({{ $komponen->id }})"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm"><i
                                                    class="fas fa-save"></i></button>
                                            <button onclick="hideAddSub({{ $komponen->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"><i
                                                    class="fas fa-times"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Sub-items --}}
                                @foreach ($komponen->children->sortBy('urutan') as $child)
                                    <tr class="data-row" data-id="{{ $child->id }}">
                                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center"></td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 pl-8">
                                            <span class="display-name"
                                                data-id="{{ $child->id }}">{{ $child->nama_aspek }}</span>
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                            <div class="flex justify-center gap-1 sm:gap-2 flex-wrap">
                                                <button onclick="toggleEditItem({{ $child->id }})"
                                                    class="btn-warning btn-sm" title="Edit"><i
                                                        class="fas fa-edit"></i></button>
                                                <form action="{{ route('admin.template-penilaian.destroy', $child) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Hapus aspek ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-danger btn-sm" title="Hapus"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Baris edit sub --}}
                                    <tr id="edit-row-{{ $child->id }}" class="hidden edit-row">
                                        <td colspan="3"
                                            class="border border-gray-300 dark:border-gray-600 px-3 py-2 bg-yellow-50 dark:bg-yellow-900/20">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 w-12 text-center">✎</span>
                                                <input type="text" id="edit-input-{{ $child->id }}"
                                                    value="{{ $child->nama_aspek }}"
                                                    class="flex-1 border rounded px-3 py-1 text-sm">
                                                <button onclick="submitEdit({{ $child->id }})"
                                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm"><i
                                                        class="fas fa-save"></i></button>
                                                <button onclick="cancelEdit({{ $child->id }})"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-6 text-gray-500 dark:text-gray-400">Belum ada
                                        komponen kejuruan.</td>
                                </tr>
                            @endforelse

                            {{-- Baris tambah root --}}
                            <tr id="add-root-kejuruan" class="hidden">
                                <td colspan="3"
                                    class="border border-gray-300 dark:border-gray-600 px-3 py-2 bg-blue-50 dark:bg-blue-900/20">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-sm font-semibold text-gray-500 dark:text-gray-400 w-12 text-center">+</span>
                                        <input type="text" id="root-input-kejuruan" placeholder="Nama komponen..."
                                            class="flex-1 border rounded px-3 py-1 text-sm">
                                        <button onclick="submitRoot('kejuruan')"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm"><i
                                                class="fas fa-save"></i></button>
                                        <button onclick="hideAddRoot('kejuruan')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"><i
                                                class="fas fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TABEL B: ASPEK SIKAP --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6 overflow-x-auto">
                <div class="flex flex-wrap items-center justify-between mb-4 border-b pb-2 gap-2">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">B. Aspek Sikap</h3>
                    <button onclick="toggleAddRoot('sikap')" class="btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Baris
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-fixed min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-12 text-center">
                                    No
                                </th>

                                <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-left">
                                    Nama Aspek
                                </th>

                                <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-40 text-center">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($sikapItems as $item)
                                <tr class="bg-gray-50 dark:bg-gray-700 data-row" data-id="{{ $item->id }}">
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center font-semibold">
                                        {{ $no++ }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 font-semibold">
                                        <span class="display-name"
                                            data-id="{{ $item->id }}">{{ $item->nama_aspek }}</span>
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                        <div class="flex justify-center gap-1 sm:gap-2 flex-wrap">
                                            <button onclick="toggleEditItem({{ $item->id }})"
                                                class="btn-warning btn-sm" title="Edit"><i
                                                    class="fas fa-edit"></i></button>
                                            <form action="{{ route('admin.template-penilaian.destroy', $item) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Hapus aspek ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger btn-sm" title="Hapus"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                            {{-- <button onclick="toggleAddSub({{ $item->id }})" class="btn-info btn-sm"
                                                title="Tambah Sub-Item"><i class="fas fa-plus"></i></button> --}}
                                        </div>
                                    </td>
                                </tr>

                                {{-- Baris edit --}}
                                <tr id="edit-row-{{ $item->id }}" class="hidden edit-row">
                                    <td colspan="3"
                                        class="border border-gray-300 dark:border-gray-600 px-3 py-2 bg-yellow-50 dark:bg-yellow-900/20">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-sm font-semibold text-gray-500 dark:text-gray-400 w-12 text-center">✎</span>
                                            <input type="text" id="edit-input-{{ $item->id }}"
                                                value="{{ $item->nama_aspek }}"
                                                class="flex-1 border rounded px-3 py-1 text-sm">
                                            <button onclick="submitEdit({{ $item->id }})"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm"><i
                                                    class="fas fa-save"></i></button>
                                            <button onclick="cancelEdit({{ $item->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"><i
                                                    class="fas fa-times"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Baris tambah sub --}}
                                <tr id="add-sub-row-{{ $item->id }}" class="hidden">
                                    <td colspan="3"
                                        class="border border-gray-300 dark:border-gray-600 px-3 py-2 bg-blue-50 dark:bg-blue-900/20">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-500 dark:text-gray-400 ml-6">↳</span>
                                            <input type="text" id="sub-input-{{ $item->id }}"
                                                placeholder="Nama sub-item..."
                                                class="flex-1 border rounded px-3 py-1 text-sm">
                                            <button onclick="submitSub({{ $item->id }})"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm"><i
                                                    class="fas fa-save"></i></button>
                                            <button onclick="hideAddSub({{ $item->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"><i
                                                    class="fas fa-times"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                @foreach ($item->children->sortBy('urutan') as $child)
                                    <tr class="data-row" data-id="{{ $child->id }}">
                                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center"></td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 pl-8">
                                            <span class="display-name"
                                                data-id="{{ $child->id }}">{{ $child->nama_aspek }}</span>
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                            <div class="flex justify-center gap-1 sm:gap-2 flex-wrap">
                                                <button onclick="toggleEditItem({{ $child->id }})"
                                                    class="btn-warning btn-sm" title="Edit"><i
                                                        class="fas fa-edit"></i></button>
                                                <form action="{{ route('admin.template-penilaian.destroy', $child) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Hapus aspek ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-danger btn-sm" title="Hapus"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Baris edit sub --}}
                                    <tr id="edit-row-{{ $child->id }}" class="hidden edit-row">
                                        <td colspan="3"
                                            class="border border-gray-300 dark:border-gray-600 px-3 py-2 bg-yellow-50 dark:bg-yellow-900/20">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 w-12 text-center">✎</span>
                                                <input type="text" id="edit-input-{{ $child->id }}"
                                                    value="{{ $child->nama_aspek }}"
                                                    class="flex-1 border rounded px-3 py-1 text-sm">
                                                <button onclick="submitEdit({{ $child->id }})"
                                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm"><i
                                                        class="fas fa-save"></i></button>
                                                <button onclick="cancelEdit({{ $child->id }})"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-6 text-gray-500 dark:text-gray-400">Belum ada
                                        aspek sikap.</td>
                                </tr>
                            @endforelse

                            {{-- Baris tambah root --}}
                            <tr id="add-root-sikap" class="hidden">
                                <td colspan="3"
                                    class="border border-gray-300 dark:border-gray-600 px-3 py-2 bg-blue-50 dark:bg-blue-900/20">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-sm font-semibold text-gray-500 dark:text-gray-400 w-12 text-center">+</span>
                                        <input type="text" id="root-input-sikap" placeholder="Nama komponen..."
                                            class="flex-1 border rounded px-3 py-1 text-sm">
                                        <button onclick="submitRoot('sikap')"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm"><i
                                                class="fas fa-save"></i></button>
                                        <button onclick="hideAddRoot('sikap')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"><i
                                                class="fas fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mt-6 overflow-x-auto">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-2">Keterangan Nilai Angka dan Huruf
                    (Otomatis)</h3>
                <table class="text-sm border border-gray-300 dark:border-gray-600">
                    <tr>
                        <td class="border px-3 py-1">90 - 100</td>
                        <td class="border px-3 py-1 font-semibold">A</td>
                        <td class="border px-3 py-1">( Sangat Kompeten )</td>
                    </tr>
                    <tr>
                        <td class="border px-3 py-1">80 - 89</td>
                        <td class="border px-3 py-1 font-semibold">B</td>
                        <td class="border px-3 py-1">( Kompeten )</td>
                    </tr>
                    <tr>
                        <td class="border px-3 py-1">70 - 79</td>
                        <td class="border px-3 py-1 font-semibold">C</td>
                        <td class="border px-3 py-1">( Cukup Kompeten )</td>
                    </tr>
                    <tr>
                        <td class="border px-3 py-1">&lt; 70</td>
                        <td class="border px-3 py-1 font-semibold">D</td>
                        <td class="border px-3 py-1">( Kurang Kompeten )</td>
                    </tr>
                </table>
            </div>
        @endif
    </div>

    <script>
        // ============================================
        // TOGGLE EDIT ITEM (hanya nama_aspek)
        // ============================================
        function toggleEditItem(id) {
            const editRow = document.getElementById(`edit-row-${id}`);
            const dataRow = document.querySelector(`.data-row[data-id="${id}"]`);
            if (editRow.classList.contains('hidden')) {
                // Sembunyikan semua baris edit lainnya
                document.querySelectorAll('.edit-row').forEach(row => row.classList.add('hidden'));
                editRow.classList.remove('hidden');
                if (dataRow) dataRow.style.display = 'none';
                document.getElementById(`edit-input-${id}`).focus();
            } else {
                cancelEdit(id);
            }
        }

        function cancelEdit(id) {
            const editRow = document.getElementById(`edit-row-${id}`);
            const dataRow = document.querySelector(`.data-row[data-id="${id}"]`);
            editRow.classList.add('hidden');
            if (dataRow) dataRow.style.display = '';
        }

        function submitEdit(id) {
            const input = document.getElementById(`edit-input-${id}`);
            const newName = input.value.trim();
            if (!newName) {
                showToast('Nama aspek tidak boleh kosong!', 'error');
                return;
            }

            fetch(`/admin/template-penilaian/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        nama_aspek: newName,
                        _method: 'PUT'
                    })
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        showToast('Berhasil diperbarui!');
                        location.reload();
                    } else {
                        showToast(res.message || 'Gagal update', 'error');
                    }
                })
                .catch(() => showToast('Terjadi kesalahan', 'error'));
        }

        // ============================================
        // FUNGSI TAMBAH ROOT (INLINE)
        // ============================================
        function toggleAddRoot(kategori) {
            const row = document.getElementById(`add-root-${kategori}`);
            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
                document.getElementById(`root-input-${kategori}`).focus();
            } else {
                hideAddRoot(kategori);
            }
        }

        function hideAddRoot(kategori) {
            document.getElementById(`add-root-${kategori}`).classList.add('hidden');
            document.getElementById(`root-input-${kategori}`).value = '';
        }

        function submitRoot(kategori) {
            const input = document.getElementById(`root-input-${kategori}`);
            const name = input.value.trim();
            if (!name) {
                showToast('Nama aspek harus diisi!', 'error');
                return;
            }
            const jurusan = '{{ $jurusan }}';
            fetch('/admin/template-penilaian/add-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        kategori,
                        nama_aspek: name,
                        jurusan
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) location.reload();
                    else showToast(data.message || 'Gagal', 'error');
                })
                .catch(() => showToast('Terjadi kesalahan', 'error'));
        }

        // ============================================
        // FUNGSI TAMBAH SUB (INLINE)
        // ============================================
        function toggleAddSub(parentId) {
            const row = document.getElementById(`add-sub-row-${parentId}`);
            if (row.classList.contains('hidden')) {
                document.querySelectorAll('[id^="add-sub-row-"]').forEach(el => el.classList.add('hidden'));
                row.classList.remove('hidden');
                document.getElementById(`sub-input-${parentId}`).focus();
            } else {
                hideAddSub(parentId);
            }
        }

        function hideAddSub(parentId) {
            document.getElementById(`add-sub-row-${parentId}`).classList.add('hidden');
            document.getElementById(`sub-input-${parentId}`).value = '';
        }

        function submitSub(parentId) {
            const input = document.getElementById(`sub-input-${parentId}`);
            const name = input.value.trim();
            if (!name) {
                showToast('Nama sub-item harus diisi!', 'error');
                return;
            }
            fetch('/admin/template-penilaian/add-sub-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        parent_id: parentId,
                        nama_aspek: name
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) location.reload();
                    else showToast(data.message || 'Gagal', 'error');
                })
                .catch(() => showToast('Terjadi kesalahan', 'error'));
        }

        // ============================================
        // TOAST
        // ============================================
        function showToast(msg, type = 'success') {
            const toast = document.createElement('div');
            toast.className =
                `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white text-sm font-medium shadow-lg ${type === 'error' ? 'bg-red-500' : 'bg-green-500'}`;
            toast.textContent = msg;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // ============================================
        // KEYBOARD & BACKDROP CLOSE
        // ============================================
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.edit-row').forEach(row => row.classList.add('hidden'));
                document.querySelectorAll('.data-row').forEach(row => row.style.display = '');
                document.querySelectorAll('[id^="add-root-"]').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('[id^="add-sub-row-"]').forEach(el => el.classList.add('hidden'));
            }
        });

        // Enter key untuk input inline
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const target = e.target;
                if (target.id === 'root-input-kejuruan') submitRoot('kejuruan');
                else if (target.id === 'root-input-sikap') submitRoot('sikap');
                else if (target.id && target.id.startsWith('sub-input-')) {
                    const parentId = target.id.replace('sub-input-', '');
                    submitSub(parentId);
                } else if (target.id && target.id.startsWith('edit-input-')) {
                    const id = target.id.replace('edit-input-', '');
                    submitEdit(id);
                }
            }
        });
    </script>
@endsection
