@extends('layouts.app')

@section('title', 'Data Kompetensi')

@section('content')
<div class="animate-fadeIn">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-tasks mr-2 text-indigo-500"></i> Data Kompetensi
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola kompetensi penilaian PKL</p>
        </div>
        <a href="{{ route('admin.kompetensi.create') }}" class="btn-primary mt-3 sm:mt-0">
            <i class="fas fa-plus mr-2"></i> Tambah Kompetensi
        </a>
    </div>

    <!-- Search -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('admin.kompetensi.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari kode atau nama kompetensi..." 
                           class="form-input pl-10">
                </div>
            </div>
            <button type="submit" class="btn-primary whitespace-nowrap">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.kompetensi.index') }}" class="btn-danger whitespace-nowrap">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            @endif
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
                        <th class="table-header">Nama Kompetensi</th>
                        <th class="table-header">Deskripsi</th>
                        <th class="table-header text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($kompetensis as $index => $kompetensi)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="table-cell">{{ $kompetensis->firstItem() + $index }}</td>
                            <td class="table-cell font-medium">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">
                                    {{ $kompetensi->kode_kompetensi }}
                                </span>
                            </td>
                            <td class="table-cell">{{ $kompetensi->nama_kompetensi }}</td>
                            <td class="table-cell max-w-xs truncate">{{ $kompetensi->deskripsi ?? '-' }}</td>
                            <td class="table-cell text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.kompetensi.show', $kompetensi) }}" 
                                       class="btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.kompetensi.edit', $kompetensi) }}" 
                                       class="btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.kompetensi.destroy', $kompetensi) }}" 
                                          method="POST" class="inline" 
                                          onsubmit="return confirmDelete(event)">
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
                            <td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                Tidak ada data kompetensi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $kompetensis->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection