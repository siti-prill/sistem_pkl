@extends('layouts.app')

@section('title', 'Edit Industri')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.industri.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-edit mr-2 text-yellow-500"></i> Edit Industri
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 max-w-2xl">
        <form action="{{ route('admin.industri.update', $industri) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kode Perusahaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_perusahaan" value="{{ old('kode_perusahaan', $industri->kode_perusahaan) }}" 
                           class="form-input" placeholder="Contoh: IND-001" required>
                    @error('kode_perusahaan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nama Perusahaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $industri->nama_perusahaan) }}" 
                           class="form-input" placeholder="Masukkan nama perusahaan" required>
                    @error('nama_perusahaan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Lokasi / Kota <span class="text-red-500">*</span>
                    </label>
                    <select name="lokasi" class="form-input" required>
                        <option value="">Pilih Lokasi</option>
                        <option value="Padang" {{ old('lokasi', $industri->lokasi) == 'Padang' ? 'selected' : '' }}>Padang</option>
                        <option value="Bandung" {{ old('lokasi', $industri->lokasi) == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                        <option value="Yogyakarta" {{ old('lokasi', $industri->lokasi) == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                        <option value="Pekanbaru" {{ old('lokasi', $industri->lokasi) == 'Pekanbaru' ? 'selected' : '' }}>Pekanbaru</option>
                        <option value="Batam" {{ old('lokasi', $industri->lokasi) == 'Batam' ? 'selected' : '' }}>Batam</option>
                        <option value="Jakarta" {{ old('lokasi', $industri->lokasi) == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                        <option value="Lainnya" {{ old('lokasi', $industri->lokasi) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('lokasi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" rows="3" class="form-input" 
                              placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $industri->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        No Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $industri->no_telepon) }}" 
                           class="form-input" placeholder="Masukkan no telepon" required>
                    @error('no_telepon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $industri->email) }}" 
                           class="form-input" placeholder="Masukkan email perusahaan">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Bidang Usaha <span class="text-red-500">*</span>
                    </label>
                    <select name="bidang_usaha" class="form-input" required>
                        <option value="">Pilih Bidang Usaha</option>
                        <option value="Teknologi" {{ old('bidang_usaha', $industri->bidang_usaha) == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                        <option value="Manufaktur" {{ old('bidang_usaha', $industri->bidang_usaha) == 'Manufaktur' ? 'selected' : '' }}>Manufaktur</option>
                        <option value="Jasa" {{ old('bidang_usaha', $industri->bidang_usaha) == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                        <option value="Perdagangan" {{ old('bidang_usaha', $industri->bidang_usaha) == 'Perdagangan' ? 'selected' : '' }}>Perdagangan</option>
                        <option value="Pendidikan" {{ old('bidang_usaha', $industri->bidang_usaha) == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                        <option value="Kesehatan" {{ old('bidang_usaha', $industri->bidang_usaha) == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                        <option value="Lainnya" {{ old('bidang_usaha', $industri->bidang_usaha) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('bidang_usaha')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Penanggung Jawab <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab', $industri->penanggung_jawab) }}" 
                           class="form-input" placeholder="Masukkan nama penanggung jawab" required>
                    @error('penanggung_jawab')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kuota <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="kuota" value="{{ old('kuota', $industri->kuota) }}" 
                           class="form-input" placeholder="Jumlah kuota PKL" min="1" required>
                    @error('kuota')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Status
                    </label>
                    <select name="status" class="form-input">
                        <option value="aktif" {{ old('status', $industri->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ old('status', $industri->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Update
                    </button>
                    <a href="{{ route('admin.industri.index') }}" class="btn-danger flex-1 text-center">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection