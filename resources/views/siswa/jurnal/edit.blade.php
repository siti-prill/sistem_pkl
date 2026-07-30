@extends('layouts.app')

@section('title', 'Edit Jurnal')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('siswa.jurnal.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-edit mr-2 text-yellow-500"></i> Edit Jurnal
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 max-w-2xl">
        <form action="{{ route('siswa.jurnal.update', $jurnal) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $jurnal->tanggal) }}" 
                           class="form-input" required>
                    @error('tanggal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Aktivitas Hari Ini <span class="text-red-500">*</span>
                    </label>
                    <textarea name="aktivitas" rows="6" class="form-input" 
                              placeholder="Deskripsikan aktivitas yang dikerjakan hari ini..." required>{{ old('aktivitas', $jurnal->aktivitas) }}</textarea>
                    @error('aktivitas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Dokumentasi (Foto)
                    </label>
                    @if($jurnal->dokumentasi)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $jurnal->dokumentasi) }}" 
                                 alt="Dokumentasi" class="h-32 w-auto rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Foto saat ini</p>
                        </div>
                    @endif
                    <input type="file" name="dokumentasi" accept="image/*" class="form-input p-2">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPEG, PNG, JPG, GIF (Max 2MB)</p>
                    @error('dokumentasi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Status
                    </label>
                    <select name="status" class="form-input" disabled>
                        <option value="draft" {{ $jurnal->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="submitted" {{ $jurnal->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    </select>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Status tidak dapat diubah</p>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Update
                    </button>
                    <a href="{{ route('siswa.jurnal.index') }}" class="btn-danger flex-1 text-center">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection