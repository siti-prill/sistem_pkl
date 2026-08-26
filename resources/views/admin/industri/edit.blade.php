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

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <form action="{{ route('admin.industri.update', $industri) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                {{-- Akun Login --}}
                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                    <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-3">
                        <i class="fas fa-key mr-1"></i> Akun Login
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Email Login <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email_login" value="{{ old('email_login', $industri->user->email) }}"
                                   class="form-input" placeholder="Untuk login ke sistem" required>
                            @error('email_login')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Password
                            </label>
                            <input type="password" name="password" value="{{ old('password') }}"
                                   class="form-input" placeholder="Kosongkan jika tidak diubah">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak diubah. Minimal 8 karakter.</p>
                        </div>
                    </div>
                </div>

                {{-- Data Perusahaan --}}
                <div>
                    <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-3">
                        <i class="fas fa-industry mr-1"></i> Data Perusahaan
                    </h4>
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
                                Email Perusahaan
                            </label>
                            <input type="email" name="email" value="{{ old('email', $industri->email) }}"
                                   class="form-input" placeholder="Email kontak perusahaan">
                            @error('email')
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
                                Lokasi / Kota <span class="text-red-500">*</span>
                            </label>
                            <select name="lokasi" class="form-input" required>
                                <option value="">Pilih Lokasi</option>
                                @foreach(['Padang','Bandung','Yogyakarta','Pekanbaru','Batam','Jakarta','Lainnya'] as $lok)
                                    <option value="{{ $lok }}" {{ old('lokasi', $industri->lokasi) == $lok ? 'selected' : '' }}>{{ $lok }}</option>
                                @endforeach
                            </select>
                            @error('lokasi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Detail Usaha --}}
                <div>
                    <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-3">
                        <i class="fas fa-briefcase mr-1"></i> Detail Usaha & PKL
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Bidang Usaha <span class="text-red-500">*</span>
                            </label>
                            <select name="bidang_usaha" class="form-input" required>
                                <option value="">Pilih Bidang Usaha</option>
                                @foreach(['Teknologi','Manufaktur','Jasa','Perdagangan','Pendidikan','Kesehatan','Lainnya'] as $bid)
                                    <option value="{{ $bid }}" {{ old('bidang_usaha', $industri->bidang_usaha) == $bid ? 'selected' : '' }}>{{ $bid }}</option>
                                @endforeach
                            </select>
                            @error('bidang_usaha')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jurusan yang Dituju
                            </label>
                            <select name="jurusan" class="form-input">
                                <option value="">Pilih Jurusan</option>
                                @foreach(\App\Models\Industri::JURUSAN_LIST as $j)
                                    @if($j != 'Semua Jurusan')
                                        <option value="{{ $j }}" {{ old('jurusan', $industri->jurusan) == $j ? 'selected' : '' }}>{{ $j }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('jurusan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Penanggung Jawab <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab', $industri->penanggung_jawab) }}"
                                   class="form-input" placeholder="Masukkan nama PJ" required>
                            @error('penanggung_jawab')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Kuota PKL <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="kuota" value="{{ old('kuota', $industri->kuota) }}"
                                   class="form-input" placeholder="Jumlah kuota" min="1" required>
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
                    </div>
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
