@extends('layouts.app')

@section('title', 'Detail Kompetensi')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.kompetensi.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-tasks mr-2 text-indigo-500"></i> Detail Kompetensi
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Kompetensi</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        <span class="px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">
                            {{ $kompetensi->kode_kompetensi }}
                        </span>
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Kompetensi</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $kompetensi->nama_kompetensi }}</p>
                </div>
                <div class="md:col-span-2">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $kompetensi->deskripsi ?? '-' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $kompetensi->created_at->format('d F Y H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Diupdate</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $kompetensi->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex gap-3">
            <a href="{{ route('admin.kompetensi.edit', $kompetensi) }}" class="btn-warning">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form action="{{ route('admin.kompetensi.destroy', $kompetensi) }}" method="POST" onsubmit="return confirmDelete(event)">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash mr-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection