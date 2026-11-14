@extends('layouts.app')

@section('title', 'Edit Jurnal')

@section('content')
    <div class="animate-fadeIn">
        <div class="flex items-center mb-12">
            <a href="{{ route('siswa.jurnal.index') }}"
                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-edit mr-2 text-yellow-500"></i> Edit Jurnal
            </h2>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 max-w-2xl">
            <form action="{{ route('siswa.jurnal.update', $jurnal) }}" method="POST">
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
                        <div class="mb-2">
                            <a href="https://drive.google.com/drive/folders/1oWZTchR0t5_frbcFs3Yto5aUm8LXOtVM?usp=drive_link"
                               target="_blank" rel="noopener"
                               class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm">
                                <i class="fas fa-cloud-upload-alt mr-2"></i> Upload Foto ke Google Drive
                            </a>
                        </div>
                        @if ($jurnal->dokumentasi)
                            @php $isUrl = \Illuminate\Support\Str::startsWith($jurnal->dokumentasi, ['http://', 'https://']); @endphp
                            <div class="mb-2">
                                @if ($isUrl)
                                    <a href="{{ $jurnal->dokumentasi }}" target="_blank" rel="noopener" class="text-blue-500 hover:text-blue-700 text-sm">
                                        <i class="fas fa-external-link-alt mr-1"></i> Lihat dokumentasi saat ini
                                    </a>
                                @else
                                    <img src="{{ route('siswa.jurnal.dokumentasi', $jurnal) }}" alt="Dokumentasi"
                                        class="rounded-lg max-h-96 w-auto">
                                @endif
                            </div>
                        @endif
                        <input type="url" name="dokumentasi" value="{{ old('dokumentasi', $jurnal->dokumentasi) }}"
                               placeholder="Tempel link berbagi foto Google Drive di sini (https://...)" class="form-input">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Klik tombol di atas untuk upload foto ke folder Drive, lalu salin link berbagi fotonya dan tempel di sini. Kosongkan untuk menghapus.</p>
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
                            <option value="submitted" {{ $jurnal->status == 'submitted' ? 'selected' : '' }}>Submitted
                            </option>
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
