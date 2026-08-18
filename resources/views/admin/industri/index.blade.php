@extends('layouts.app')

@section('title', 'Data Industri')

@section('content')
<div class="animate-fadeIn">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-building mr-2 text-indigo-500"></i> Data Industri
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data perusahaan mitra PKL</p>
        </div>
        <a href="{{ route('admin.industri.create') }}" class="btn-primary mt-3 sm:mt-0">
            <i class="fas fa-plus mr-2"></i> Tambah Industri
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('admin.industri.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari industri..." class="form-input pl-10">
            </div>
            <div>
                <select name="status" class="form-input">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.industri.index') }}" class="btn-danger">
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
                        <th class="table-header">Kode</th>
                        <th class="table-header">Nama Perusahaan</th>
                        <th class="table-header">Bidang</th>
                        <th class="table-header">Kuota</th>
                        <th class="table-header">Status</th>
                        <th class="table-header text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($industris as $index => $industri)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="table-cell">{{ $industris->firstItem() + $index }}</td>
                            <td class="table-cell font-medium">{{ $industri->kode_perusahaan }}</td>
                            <td class="table-cell">{{ $industri->nama_perusahaan }}</td>
                            <td class="table-cell">{{ $industri->bidang_usaha }}</td>
                            <td class="table-cell">{{ $industri->kuota }}</td>
                            <td class="table-cell">
                                @if($industri->status == 'aktif')
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
                                    <a href="{{ route('admin.industri.show', $industri) }}" class="btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.industri.edit', $industri) }}" class="btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.industri.destroy', $industri) }}" 
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
                            <td colspan="7" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                Tidak ada data industri
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $industris->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection