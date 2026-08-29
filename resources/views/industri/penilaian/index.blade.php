@extends('layouts.app')

@section('title', 'Penilaian Siswa')

@section('content')
    <div class="animate-fadeIn">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    <i class="fas fa-clipboard-check mr-2 text-teal-500"></i> Penilaian Siswa
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $industri->nama_perusahaan }}</p>
                <p class="text-sm text-teal-700 dark:text-teal-300 leading-relaxed">
                    <i class="fas fa-info-circle text-black dark:text-white mr-1"></i>
                    Pilih siswa untuk mengisi penilaian sesuai template dari sekolah. Nilai akan langsung terlihat oleh guru
                    pembimbing dan siswa.
                </p>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
            <form method="GET" action="{{ route('industri.penilaian.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input"
                        placeholder="Cari nama siswa atau NIS...">
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="table-container">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="table-header">No</th>
                            <th class="table-header">Nama Siswa</th>
                            <th class="table-header">NIS</th>
                            <th class="table-header">Jurusan</th>
                            <th class="table-header">Guru Pembimbing</th>
                            <th class="table-header">Status</th>
                            <th class="table-header">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($penempatans as $index => $penempatan)
                            @php
                                $sudahDinilai =
                                    \App\Models\MonitoringNilai::where('penempatan_id', $penempatan->id)
                                        ->where('role_penilai', 'industri')
                                        ->count() > 0;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="table-cell">{{ $penempatans->firstItem() + $index }}</td>
                                <td class="table-cell font-medium">{{ $penempatan->siswa->nama_siswa }}</td>
                                <td class="table-cell">{{ $penempatan->siswa->nis }}</td>
                                <td class="table-cell">{{ $penempatan->siswa->jurusan }}</td>
                                <td class="table-cell">{{ $penempatan->guru->nama_guru }}</td>
                                <td class="table-cell">
                                    @if ($sudahDinilai)
                                        <span class="badge-success">Sudah Dinilai</span>
                                    @else
                                        <span class="badge-warning">Belum Dinilai</span>
                                    @endif
                                </td>
                                <td class="table-cell">
                                    <a href="{{ route('industri.penilaian.show', $penempatan->id) }}"
                                        class="{{ $sudahDinilai ? 'btn-success' : 'btn-primary' }} btn-sm">
                                        <i class="fas fa-{{ $sudahDinilai ? 'eye' : 'edit' }} mr-1"></i>
                                        {{ $sudahDinilai ? 'Lihat' : 'Isi Nilai' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-users text-4xl mb-2 block"></i>
                                    Tidak ada siswa yang ditempatkan di perusahaan ini
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
