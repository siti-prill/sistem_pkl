@extends('layouts.app')

@section('title', 'Data Industri')

@section('content')
    <div class="animate-fadeIn">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    <i class="fas fa-building mr-2 text-indigo-500"></i> Data Industri
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data industri mitra PKL</p>
            </div>
            <a href="{{ route('admin.industri.create') }}" class="btn-primary mt-3 sm:mt-0">
                <i class="fas fa-plus mr-2"></i> Tambah Industri
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
            <form method="GET" action="{{ route('admin.industri.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama, atau bidang..."
                            class="form-input pl-10">
                    </div>
                </div>
                <div class="flex-1">
                    <select name="jurusan" class="form-input w-full">
                        <option value="">Semua Jurusan</option>
                        @foreach (\App\Models\Industri::JURUSAN_LIST as $j)
                            @if ($j != 'Semua Jurusan')
                                <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>
                                    {{ $j }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <select name="status" class="form-input w-full">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary whitespace-nowrap">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
                @if (request()->anyFilled(['search', 'status', 'jurusan']))
                    <a href="{{ route('admin.industri.index') }}" class="btn-danger whitespace-nowrap">
                        <i class="fas fa-times mr-2"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        @forelse($grupIndustri as $grupJurusan => $grup)
            <div class="mb-6">
                <div class="flex items-center justify-between bg-gradient-to-r from-indigo-600 to-purple-600 rounded-t-xl px-5 py-3">
                    <h3 class="text-white font-bold flex items-center gap-2 text-base">
                        <i class="fas fa-layer-group"></i> {{ $grupJurusan }}
                    </h3>
                    <span class="bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $grup->count() }} industri
                    </span>
                </div>

                <div class="table-container !rounded-t-none">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="table-header">No</th>
                                    <th class="table-header">Kode</th>
                                    <th class="table-header">Nama Perusahaan</th>
                                    <th class="table-header">Bidang</th>
                                    <th class="table-header">Kuota</th>
                                    <th class="table-header">Status</th>
                                    <th class="table-header text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($grup as $index => $industri)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="table-cell">{{ $index + 1 }}</td>
                                        <td class="table-cell font-medium">{{ $industri->kode_perusahaan }}</td>
                                        <td class="table-cell">{{ $industri->nama_perusahaan }}</td>
                                        <td class="table-cell">{{ $industri->bidang_usaha }}</td>
                                        <td class="table-cell text-center">{{ $industri->kuota }}</td>
                                        <td class="table-cell">
                                            @if ($industri->status == 'aktif')
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                                                    <i class="fas fa-circle text-xs mr-1"></i> Aktif
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300">
                                                    <i class="fas fa-circle text-xs mr-1"></i> Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="table-cell text-center">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('admin.industri.show', $industri) }}"
                                                    class="btn-info btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.industri.edit', $industri) }}"
                                                    class="btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.industri.destroy', $industri) }}"
                                                    method="POST" class="inline" onsubmit="return confirmDelete(event)">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="table-container">
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <i class="fas fa-inbox text-4xl mb-2 block"></i>
                    Tidak ada data industri
                </div>
            </div>
        @endforelse
    </div>
@endsection
