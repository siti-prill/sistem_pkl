@extends('layouts.app')

@section('title', 'Laporan Jurnal PKL')

@section('content')
<div class="animate-fadeIn">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-file-alt mr-2 text-indigo-500"></i> Laporan Jurnal PKL
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rekapitulasi jurnal harian PKL</p>
        </div>
        <a href="{{ route('laporan.jurnal.pdf', request()->all()) }}" class="btn-danger mt-3 sm:mt-0">
            <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('laporan.jurnal') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="form-input">
            </div>
            @if(auth()->user()->role == 'admin' || auth()->user()->role == 'guru')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siswa</label>
                    <select name="siswa_id" class="form-input">
                        <option value="">Semua Siswa</option>
                        @foreach($siswas as $siswa)
                            <option value="{{ $siswa->id }}" {{ request('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                {{ $siswa->nama_siswa }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="flex gap-2 items-end">
                <button type="submit" class="btn-primary flex-1">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                @if(request()->anyFilled(['tanggal_mulai', 'tanggal_selesai', 'siswa_id']))
                    <a href="{{ route('laporan.jurnal') }}" class="btn-danger">
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
                        <th class="table-header">Tanggal</th>
                        <th class="table-header">Siswa</th>
                        <th class="table-header">NIS</th>
                        <th class="table-header">Industri</th>
                        <th class="table-header">Aktivitas</th>
                        <th class="table-header">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($jurnals as $index => $jurnal)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="table-cell">{{ $jurnals->firstItem() + $index }}</td>
                            <td class="table-cell">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}</td>
                            <td class="table-cell font-medium">{{ $jurnal->penempatan->siswa->nama_siswa }}</td>
                            <td class="table-cell">{{ $jurnal->penempatan->siswa->nis }}</td>
                            <td class="table-cell">{{ $jurnal->penempatan->industri->nama_perusahaan }}</td>
                            <td class="table-cell max-w-xs truncate">{{ $jurnal->aktivitas }}</td>
                            <td class="table-cell">
                                @if($jurnal->status == 'submitted')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                                        <i class="fas fa-check-circle mr-1"></i> Submitted
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300">
                                        <i class="fas fa-pencil-alt mr-1"></i> Draft
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                Tidak ada data jurnal
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $jurnals->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection