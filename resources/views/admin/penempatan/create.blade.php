@extends('layouts.app')

@section('title', 'Tambah Penempatan PKL')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.penempatan.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-plus-circle mr-2 text-indigo-500"></i> Tambah Penempatan PKL
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 max-w-2xl">
        <form action="{{ route('admin.penempatan.store') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Siswa <span class="text-red-500">*</span>
                    </label>
                    <select name="siswa_id" class="form-input" required>
                        <option value="">Pilih Siswa</option>
                        @foreach($siswas as $siswa)
                            <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                {{ $siswa->nama_siswa }} ({{ $siswa->nis }})
                            </option>
                        @endforeach
                    </select>
                    @error('siswa_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Industri <span class="text-red-500">*</span>
                    </label>
                    <select name="industri_id" class="form-input" required>
                        <option value="">Pilih Industri</option>
                        @foreach($industris as $industri)
                            <option value="{{ $industri->id }}" {{ old('industri_id') == $industri->id ? 'selected' : '' }}>
                                {{ $industri->nama_perusahaan }} (Kuota: {{ $industri->kuota }})
                            </option>
                        @endforeach
                    </select>
                    @error('industri_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Guru Pembimbing <span class="text-red-500">*</span>
                    </label>
                    <select name="guru_id" class="form-input" required>
                        <option value="">Pilih Guru</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                {{ $guru->nama_guru }} ({{ $guru->nip }})
                            </option>
                        @endforeach
                    </select>
                    @error('guru_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kompetensi <span class="text-red-500">*</span>
                    </label>
                    <select name="kompetensi_id" class="form-input" required>
                        <option value="">Pilih Kompetensi</option>
                        @foreach($kompetensis as $kompetensi)
                            <option value="{{ $kompetensi->id }}" {{ old('kompetensi_id') == $kompetensi->id ? 'selected' : '' }}>
                                {{ $kompetensi->nama_kompetensi }} ({{ $kompetensi->kode_kompetensi }})
                            </option>
                        @endforeach
                    </select>
                    @error('kompetensi_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" 
                               class="form-input" required>
                        @error('tanggal_mulai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" 
                               class="form-input" required>
                        @error('tanggal_selesai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Status
                    </label>
                    <select name="status" class="form-input">
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="batal" {{ old('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                    <a href="{{ route('admin.penempatan.index') }}" class="btn-danger flex-1 text-center">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection