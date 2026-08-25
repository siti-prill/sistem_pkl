@extends('layouts.app')

@section('title', 'Nilai Kesimpulan Akhir')

@section('content')
<div class="animate-fadeIn">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-award mr-2 text-purple-500"></i> Nilai Kesimpulan Akhir
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Nilai raport akhir untuk siswa (tidak terlihat siswa)</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('guru.kesimpulan.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input"
                    placeholder="Cari nama siswa atau NIS...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="form-input">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="flex gap-3 items-end">
                <button type="submit" class="btn-primary flex-1">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="table-header">No</th>
                        <th class="table-header">Siswa</th>
                        <th class="table-header">NIS</th>
                        <th class="table-header">Industri</th>
                        <th class="table-header">Nilai Guru</th>
                        <th class="table-header">Nilai Industri</th>
                        <th class="table-header">Kesimpulan</th>
                        <th class="table-header">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($penempatans as $index => $penempatan)
                        @php
                            $nilaiGuru = \App\Models\MonitoringNilai::where('penempatan_id', $penempatan->id)
                                ->where('role_penilai', 'guru')->avg('nilai');
                            $nilaiIndustri = \App\Models\MonitoringNilai::where('penempatan_id', $penempatan->id)
                                ->where('role_penilai', 'industri')->avg('nilai');
                            $kesimpulan = \App\Models\NilaiKesimpulan::where('penempatan_id', $penempatan->id)
                                ->where('guru_id', auth()->user()->guru->id)->first();
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="table-cell">{{ $penempatans->firstItem() + $index }}</td>
                            <td class="table-cell font-medium">{{ $penempatan->siswa->nama_siswa }}</td>
                            <td class="table-cell">{{ $penempatan->siswa->nis }}</td>
                            <td class="table-cell">{{ $penempatan->industri->nama_perusahaan }}</td>
                            <td class="table-cell">
                                @if($nilaiGuru)
                                    <span class="font-bold text-indigo-600">{{ number_format($nilaiGuru, 1) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="table-cell">
                                @if($nilaiIndustri)
                                    <span class="font-bold text-teal-600">{{ number_format($nilaiIndustri, 1) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="table-cell">
                                @if($kesimpulan)
                                    <span class="font-bold text-purple-600">{{ number_format($kesimpulan->nilai_kesimpulan, 1) }}</span>
                                @else
                                    <span class="badge-warning">Belum diisi</span>
                                @endif
                            </td>
                            <td class="table-cell">
                                <a href="{{ route('guru.kesimpulan.show', $penempatan->id) }}"
                                    class="{{ $kesimpulan ? 'btn-success' : 'btn-primary' }} btn-sm">
                                    <i class="fas fa-{{ $kesimpulan ? 'eye' : 'edit' }} mr-1"></i>
                                    {{ $kesimpulan ? 'Lihat' : 'Isi' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-users text-4xl mb-2 block"></i>
                                Tidak ada data penempatan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $penempatans->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
