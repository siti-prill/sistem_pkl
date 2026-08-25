@extends('layouts.app')

@section('title', 'Kesimpulan Akhir - ' . $penempatan->siswa->nama_siswa)

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('guru.kesimpulan.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-award mr-2 text-purple-500"></i> Nilai Kesimpulan Akhir
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $penempatan->siswa->nama_siswa }} ({{ $penempatan->siswa->nis }})</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Ringkasan Nilai -->
        <div>
            <!-- Nilai Guru -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                    <i class="fas fa-chalkboard-teacher mr-2 text-indigo-500"></i> Nilai dari Guru
                </h3>
                @if($nilaisGuru->count() > 0)
                    <div class="text-center mb-4">
                        <p class="text-sm text-gray-500">Rata-rata</p>
                        <p class="text-3xl font-bold text-indigo-600">{{ number_format($rataGuru, 1) }}</p>
                    </div>
                    <div class="space-y-2">
                        @foreach($nilaisGuru as $nilai)
                            <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $nilai->aspek_penilaian }}</span>
                                <span class="font-bold text-indigo-600">{{ $nilai->nilai }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada nilai guru</p>
                @endif
            </div>

            <!-- Nilai Industri -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                    <i class="fas fa-clipboard-check mr-2 text-teal-500"></i> Nilai dari Industri
                </h3>
                @if($nilaisIndustri->count() > 0)
                    <div class="text-center mb-4">
                        <p class="text-sm text-gray-500">Rata-rata</p>
                        <p class="text-3xl font-bold text-teal-600">{{ number_format($rataIndustri, 1) }}</p>
                    </div>
                    <div class="space-y-2">
                        @foreach($nilaisIndustri as $nilai)
                            <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $nilai->aspek_penilaian }}</span>
                                <span class="font-bold text-teal-600">{{ $nilai->nilai }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada nilai industri</p>
                @endif
            </div>
        </div>

        <!-- Form Kesimpulan -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                    <i class="fas fa-pen mr-2 text-purple-500"></i> Nilai Kesimpulan Akhir
                </h3>

                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-purple-600 dark:text-purple-400 mr-3"></i>
                        <p class="text-sm text-purple-800 dark:text-purple-200">
                            Nilai ini akan digunakan sebagai nilai raport akhir PKL.
                            <strong>Tidak terlihat oleh siswa.</strong>
                        </p>
                    </div>
                </div>

                @if($kesimpulan)
                    <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg mb-6">
                        <p class="text-sm text-gray-500">Nilai Kesimpulan Saat Ini</p>
                        <p class="text-4xl font-bold text-purple-600 mt-1">{{ number_format($kesimpulan->nilai_kesimpulan, 1) }}</p>
                        @if($kesimpulan->catatan_kesimpulan)
                            <p class="text-sm text-gray-500 mt-2">{{ $kesimpulan->catatan_kesimpulan }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">Terakhir diupdate: {{ $kesimpulan->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                @endif

                <form action="{{ route('guru.kesimpulan.store', $penempatan->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nilai Kesimpulan (0-100) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="nilai_kesimpulan"
                                class="form-input @error('nilai_kesimpulan') is-invalid @enderror"
                                min="0" max="100" step="0.01"
                                value="{{ old('nilai_kesimpulan', $kesimpulan ? $kesimpulan->nilai_kesimpulan : '') }}"
                                placeholder="Masukkan nilai kesimpulan" required>
                            @error('nilai_kesimpulan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Catatan Kesimpulan
                            </label>
                            <textarea name="catatan_kesimpulan" rows="4" class="form-input"
                                placeholder="Catatan tambahan untuk nilai kesimpulan">{{ old('catatan_kesimpulan', $kesimpulan ? $kesimpulan->catatan_kesimpulan : '') }}</textarea>
                        </div>

                        <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="btn-primary flex-1">
                                <i class="fas fa-save mr-2"></i> {{ $kesimpulan ? 'Update' : 'Simpan' }} Kesimpulan
                            </button>
                            <a href="{{ route('guru.kesimpulan.index') }}" class="btn-danger flex-1 text-center">
                                <i class="fas fa-times mr-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
