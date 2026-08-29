@extends('layouts.app')

@section('title', 'Laporan PKL')

@section('content')
<div class="animate-fadeIn">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-clipboard-list mr-2 text-teal-500"></i> Laporan PKL
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Daftar nilai siswa yang diberikan oleh industri sesuai template dari admin
            </p>
        </div>
    </div>

    <!-- Info -->
    <div class="bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-800 rounded-xl p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-teal-600 dark:text-teal-400 mr-3"></i>
            <p class="text-sm text-teal-800 dark:text-teal-200">
                Nilai diisi oleh pihak industri (Pembimbing Lapangan) berdasarkan template penilaian dari sekolah.
                Anda hanya dapat melihat laporan ini.
            </p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('laporan.pkl') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-9 text-gray-400"></i>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Siswa</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama atau NIS..." class="form-input pl-10">
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
                @if(request('search') || request('status'))
                    <a href="{{ route('laporan.pkl') }}" class="btn-danger">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
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
                        <th class="table-header">NIS</th>
                        <th class="table-header">Nama Siswa</th>
                        <th class="table-header">Industri</th>
                        @if(auth()->user()->role == 'guru')
                            <th class="table-header">Guru Pembimbing</th>
                        @endif
                        <th class="table-header">Rata-rata</th>
                        <th class="table-header">Status Penilaian</th>
                        <th class="table-header text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($penempatans as $index => $penempatan)
                        @php
                            $nilaiIndustri = $penempatan->monitoringNilai
                                ->where('role_penilai', 'industri')
                                ->avg('nilai');
                            $sudahDinilai = $penempatan->jumlah_nilai_industri > 0;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="table-cell">{{ $penempatans->firstItem() + $index }}</td>
                            <td class="table-cell">{{ $penempatan->siswa->nis }}</td>
                            <td class="table-cell font-medium">{{ $penempatan->siswa->nama_siswa }}</td>
                            <td class="table-cell">{{ $penempatan->industri->nama_perusahaan }}</td>
                            @if(auth()->user()->role == 'guru')
                                <td class="table-cell">{{ $penempatan->guru->nama_guru }}</td>
                            @endif
                            <td class="table-cell">
                                @if($sudahDinilai)
                                    <span class="font-bold text-teal-600 dark:text-teal-400">
                                        {{ number_format($nilaiIndustri, 1) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="table-cell">
                                @if($sudahDinilai)
                                    <span class="badge-success">Sudah Dinilai</span>
                                @else
                                    <span class="badge-warning">Belum Dinilai</span>
                                @endif
                            </td>
                            <td class="table-cell text-center">
                                <a href="{{ route('laporan.pkl.show', $penempatan->id) }}" class="btn-info btn-sm">
                                    <i class="fas fa-eye mr-1"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                Tidak ada data laporan PKL
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