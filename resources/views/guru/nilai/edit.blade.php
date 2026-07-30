@extends('layouts.app')

@section('title', 'Edit Nilai')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('guru.nilai.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-edit mr-2 text-yellow-500"></i> Edit Nilai
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 max-w-2xl">
        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                <strong>Siswa:</strong> {{ $nilai->penempatan->siswa->nama_siswa }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                <strong>Aspek:</strong> {{ $nilai->aspek_penilaian }}
            </p>
        </div>

        <form action="{{ route('guru.nilai.update', $nilai->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Aspek Penilaian <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="aspek_penilaian" value="{{ old('aspek_penilaian', $nilai->aspek_penilaian) }}" 
                           class="form-input" placeholder="Contoh: Kedisiplinan, Keterampilan, dll" required>
                    @error('aspek_penilaian')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nilai (0-100) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="nilai" value="{{ old('nilai', $nilai->nilai) }}" 
                           class="form-input" placeholder="Masukkan nilai 0-100" min="0" max="100" required>
                    @error('nilai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tanggal Penilaian <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_penilaian" value="{{ old('tanggal_penilaian', $nilai->tanggal_penilaian) }}" 
                           class="form-input" required>
                    @error('tanggal_penilaian')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Catatan
                    </label>
                    <textarea name="catatan" rows="3" class="form-input" 
                              placeholder="Catatan tambahan tentang penilaian">{{ old('catatan', $nilai->catatan) }}</textarea>
                    @error('catatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Update
                    </button>
                    <a href="{{ route('guru.nilai.show', $nilai->penempatan_id) }}" class="btn-danger flex-1 text-center">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection