@extends('layouts.app')

@section('title', 'Penilaian PKL')

@section('content')
<div class="animate-fadeIn">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-star mr-2 text-indigo-500"></i> Penilaian PKL
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola penilaian siswa bimbingan</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('guru.nilai.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari siswa..." class="form-input pl-10">
            </div>
            <div>
                <select name="status" class="form-input">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('guru.nilai.index') }}" class="btn-danger">
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
                        <th class="table-header">Status</th>
                        <th class="table-header">Total Nilai</th>
                        <th class="table-header text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($penempatans as $index => $penempatan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="table-cell">{{ $penempatans->firstItem() + $index }}</td>
                            <td class="table-cell">{{ $penempatan->siswa->nis }}</td>
                            <td class="table-cell font-medium">{{ $penempatan->siswa->nama_siswa }}</td>
                            <td class="table-cell">{{ $penempatan->industri->nama_perusahaan }}</td>
                            <td class="table-cell">
                                @if($penempatan->status == 'aktif')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="table-cell">
                                @php
                                    $totalNilai = $penempatan->monitoringNilai->avg('nilai');
                                @endphp
                                @if($totalNilai)
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($totalNilai, 1) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="table-cell text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('guru.nilai.show', $penempatan->id) }}" class="btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <a href="{{ route('guru.nilai.create', ['penempatan_id' => $penempatan->id]) }}" class="btn btn-success btn-sm">
    <i class="fas fa-plus"></i> Nilai
</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                Tidak ada data siswa bimbingan
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