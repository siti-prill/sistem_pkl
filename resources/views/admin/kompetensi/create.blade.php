@extends('layouts.app')

@section('title', 'Tambah Kompetensi')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.kompetensi.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-plus-circle mr-2 text-indigo-500"></i> Tambah Kompetensi
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 max-w-2xl">
        <form action="{{ route('admin.kompetensi.store') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kode Kompetensi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_kompetensi" value="{{ old('kode_kompetensi') }}" 
                           class="form-input" placeholder="Contoh: KMP-001" required>
                    @error('kode_kompetensi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nama Kompetensi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kompetensi" value="{{ old('nama_kompetensi') }}" 
                           class="form-input" placeholder="Masukkan nama kompetensi" required>
                    @error('nama_kompetensi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="4" class="form-input" 
                              placeholder="Masukkan deskripsi kompetensi">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                    <a href="{{ route('admin.kompetensi.index') }}" class="btn-danger flex-1 text-center">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection