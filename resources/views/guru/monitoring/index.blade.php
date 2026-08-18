@extends('layouts.app')

@section('title', 'Monitoring PKL')

@section('content')
<div class="animate-fadeIn">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-eye mr-2 text-indigo-500"></i> Monitoring PKL
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau aktivitas siswa bimbingan</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="stat-label">Total Siswa Bimbingan</p>
                    <p class="stat-number">{{ $totalSiswa }}</p>
                </div>
                <div class="stat-icon bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="stat-label">Jurnal Hari Ini</p>
                    <p class="stat-number">{{ $totalJurnalHariIni }}</p>
                </div>
                <div class="stat-icon bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="stat-label">Belum Jurnal</p>
                    <p class="stat-number text-red-600 dark:text-red-400">{{ $siswaBelumJurnal->count() }}</p>
                </div>
                <div class="stat-icon bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Siswa Belum Jurnal -->
    @if($siswaBelumJurnal->count() > 0)
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 mb-6">
            <h4 class="font-semibold text-red-700 dark:text-red-400 mb-2">
                <i class="fas fa-bell mr-2"></i> Siswa Belum Mengisi Jurnal Hari Ini
            </h4>
            <div class="flex flex-wrap gap-2">
                @foreach($siswaBelumJurnal as $item)
                    <span class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-full text-sm">
                        {{ $item->siswa->nama_siswa }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Search -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('guru.monitoring.index') }}" class="flex flex-col sm:flex-row gap-6">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama siswa..." class="form-input pl-10">
                </div>
            </div>
            <button type="submit" class="btn-primary whitespace-nowrap">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('guru.monitoring.index') }}" class="btn-danger whitespace-nowrap">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            @endif
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
                        <th class="table-header">Kompetensi</th>
                        <th class="table-header">Status</th>
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
                            <td class="table-cell">{{ $penempatan->kompetensi->nama_kompetensi }}</td>
                            <td class="table-cell">
                                @if($penempatan->status == 'aktif')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {{ ucfirst($penempatan->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="table-cell text-center">
                                <a href="{{ route('guru.monitoring.show', $penempatan->id) }}" class="btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                Tidak ada siswa bimbingan
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