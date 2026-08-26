@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
    <div class="animate-fadeIn">
        <div class="flex items-center mb-12">
            <a href="{{ route('admin.siswa.index') }}"
                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-edit mr-2 text-yellow-500"></i> Edit Siswa
            </h2>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            NIS <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" class="form-input"
                            placeholder="Masukkan NIS" required>
                        @error('nis')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" class="form-input"
                            placeholder="Masukkan nama lengkap" required>
                        @error('nama_siswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $siswa->user->email) }}" class="form-input"
                            placeholder="Masukkan email" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Password <span class="text-gray-400">(Kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" name="password" class="form-input"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Jurusan <span class="text-red-500">*</span>
                        </label>
                        <select name="jurusan" class="form-input" required>
                            <option value="">Pilih Jurusan</option>
                            @foreach ($jurusanList as $j)
                                <option value="{{ $j }}"
                                    {{ old('jurusan', $siswa->jurusan) == $j ? 'selected' : '' }}>
                                    {{ $j }}
                                </option>
                            @endforeach
                        </select>
                        @error('jurusan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            No Telepon
                        </label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $siswa->no_telepon) }}" class="form-input"
                            placeholder="Masukkan no telepon">
                        @error('no_telepon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Alamat
                        </label>
                        <textarea name="alamat" rows="3" class="form-input" placeholder="Masukkan alamat">{{ old('alamat', $siswa->alamat) }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="btn-primary flex-1">
                            <i class="fas fa-save mr-2"></i> Update
                        </button>
                        <a href="{{ route('admin.siswa.index') }}" class="btn-danger flex-1 text-center">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
