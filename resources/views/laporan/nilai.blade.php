@extends('layouts.app')

@section('title', 'Laporan Nilai PKL')

@section('content')
<div class="animate-fadeIn">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-file-alt mr-2 text-indigo-500"></i> Laporan Nilai PKL
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rekapitulasi nilai PKL siswa</p>
        </div>
        <a href="{{ route('laporan.nilai.pdf', request()->all()) }}" class="btn-danger mt-3 sm:mt-0">
            <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('laporan.nilai') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
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
                @if(request()->has('siswa_id'))
                    <a href="{{ route('laporan.nilai') }}" class="btn-danger">
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
                        <th class="table-header">Siswa</th>
                        <th class="table-header">NIS</th>
                        <th class="table-header">Industri</th>
                        <th class="table-header">Aspek Penilaian</th>
                        <th class="table-header">Nilai</th>
                        <th class="table-header">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($nilais as $index => $nilai)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="table-cell">{{ $nilais->firstItem() + $index }}</td>
                            <td class="table-cell font-medium">{{ $nilai->penempatan->siswa->nama_siswa }}</td>
                            <td class="table-cell">{{ $nilai->penempatan->siswa->nis }}</td>
                            <td class="table-cell">{{ $nilai->penempatan->industri->nama_perusahaan }}</td>
                            <td class="table-cell">{{ $nilai->aspek_penilaian }}</td>
                            <td class="table-cell">
                                <span class="font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $nilai->nilai }}
                                </span>
                            </td>
                            <td class="table-cell">{{ \Carbon\Carbon::parse($nilai->tanggal_penilaian)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                Tidak ada data nilai
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $nilais->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection