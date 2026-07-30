@extends('layouts.app')

@section('title', 'Data Penempatan PKL')

@section('content')
<div class="animate-fadeIn">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-people-arrows mr-2 text-indigo-500"></i> Data Penempatan PKL
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola penempatan siswa PKL</p>
        </div>
        <a href="{{ route('admin.penempatan.create') }}" class="btn-primary mt-3 sm:mt-0">
            <i class="fas fa-plus mr-2"></i> Tambah Penempatan
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('admin.penempatan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari siswa/industri..." class="form-input pl-10">
            </div>
            <div>
                <select name="status" class="form-input">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.penempatan.index') }}" class="btn-danger">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="table-header">No</th>
                        <th class="table-header">Siswa</th>
                        <th class="table-header">Industri</th>
                        <th class="table-header">Guru Pembimbing</th>
                        <th class="table-header">Kompetensi</th>
                        <th class="table-header">Tanggal</th>
                        <th class="table-header">Status</th>
                        <th class="table-header text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($penempatans as $index => $penempatan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="table-cell">{{ $penempatans->firstItem() + $index }}</td>
                            <td class="table-cell font-medium">{{ $penempatan->siswa->nama_siswa }}</td>
                            <td class="table-cell">{{ $penempatan->industri->nama_perusahaan }}</td>
                            <td class="table-cell">{{ $penempatan->guru->nama_guru }}</td>
                            <td class="table-cell">{{ $penempatan->kompetensi->nama_kompetensi }}</td>
                            <td class="table-cell text-sm">
                                {{ \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d/m/Y') }} - 
                                {{ \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d/m/Y') }}
                            </td>
                            <td class="table-cell">
                                @if($penempatan->status == 'aktif')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                                        <i class="fas fa-circle text-xs mr-1"></i> Aktif
                                    </span>
                                @elseif($penempatan->status == 'selesai')
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">
                                        <i class="fas fa-check-circle text-xs mr-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300">
                                        <i class="fas fa-times-circle text-xs mr-1"></i> Batal
                                    </span>
                                @endif
                            </td>
                            <td class="table-cell text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.penempatan.show', $penempatan) }}" class="btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.penempatan.edit', $penempatan) }}" class="btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.penempatan.destroy', $penempatan) }}" 
                                          method="POST" class="inline" onsubmit="return confirmDelete(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                Tidak ada data penempatan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $penempatans->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection