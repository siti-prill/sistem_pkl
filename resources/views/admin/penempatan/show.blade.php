@extends('layouts.app')

@section('title', 'Detail Penempatan PKL')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.penempatan.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-people-arrows mr-2 text-indigo-500"></i> Detail Penempatan PKL
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Siswa</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $penempatan->siswa->nama_siswa }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">NIS: {{ $penempatan->siswa->nis }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Industri</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $penempatan->industri->nama_perusahaan }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $penempatan->industri->bidang_usaha }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Guru Pembimbing</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $penempatan->guru->nama_guru }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">NIP: {{ $penempatan->guru->nip }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kompetensi</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $penempatan->kompetensi->nama_kompetensi }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kode: {{ $penempatan->kompetensi->kode_kompetensi }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d F Y') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d F Y') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                    @if($penempatan->status == 'aktif')
                        <span class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                            <i class="fas fa-circle text-xs mr-1"></i> Aktif
                        </span>
                    @elseif($penempatan->status == 'selesai')
                        <span class="px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">
                            <i class="fas fa-check-circle text-xs mr-1"></i> Selesai
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300">
                            <i class="fas fa-times-circle text-xs mr-1"></i> Batal
                        </span>
                    @endif
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Lama PKL</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($penempatan->tanggal_mulai)->diffInDays($penempatan->tanggal_selesai) + 1 }} Hari
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $penempatan->created_at->format('d F Y H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Diupdate</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $penempatan->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>

            <!-- Statistik Jurnal -->
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Statistik Jurnal</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $penempatan->jurnalHarian->count() }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Jurnal</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ $penempatan->jurnalHarian->where('status', 'submitted')->count() }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Submitted</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                            {{ $penempatan->jurnalHarian->where('status', 'draft')->count() }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Draft</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ $penempatan->monitoringNilai->count() }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nilai</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex gap-3">
            <a href="{{ route('admin.penempatan.edit', $penempatan) }}" class="btn-warning">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form action="{{ route('admin.penempatan.destroy', $penempatan) }}" method="POST" onsubmit="return confirmDelete(event)">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash mr-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection