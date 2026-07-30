@extends('layouts.app')

@section('title', 'Detail Nilai')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('guru.nilai.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-star mr-2 text-indigo-500"></i> Detail Nilai: {{ $penempatan->siswa->nama_siswa }}
        </h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Siswa -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="text-center mb-4">
                    <div class="w-20 h-20 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mx-auto">
                        <i class="fas fa-user-graduate text-3xl text-indigo-600 dark:text-indigo-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-2">{{ $penempatan->siswa->nama_siswa }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">NIS: {{ $penempatan->siswa->nis }}</p>
                </div>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-500 dark:text-gray-400">Jurusan:</span> {{ $penempatan->siswa->jurusan }}</p>
                    <p><span class="text-gray-500 dark:text-gray-400">Industri:</span> {{ $penempatan->industri->nama_perusahaan }}</p>
                    <p><span class="text-gray-500 dark:text-gray-400">Guru:</span> {{ $penempatan->guru->nama_guru }}</p>
                </div>
            </div>
        </div>

        <!-- Daftar Nilai -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            <i class="fas fa-list mr-2"></i> Daftar Nilai
                        </h3>
                        <a href="{{ route('guru.nilai.create', $penempatan->id) }}" class="btn-success btn-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Nilai
                        </a>
                    </div>
                </div>
                <div class="p-4">
                    @if($nilais->count() > 0)
                        @php
                            $totalNilai = $nilais->avg('nilai');
                            $grade = $totalNilai >= 85 ? 'A' : ($totalNilai >= 70 ? 'B' : ($totalNilai >= 60 ? 'C' : 'D'));
                        @endphp
                        
                        <div class="grid grid-cols-3 gap-4 mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Nilai</p>
                                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $nilais->count() }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Rata-rata</p>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalNilai, 1) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Grade</p>
                                <p class="text-2xl font-bold 
                                    @if($grade == 'A') text-green-600 dark:text-green-400
                                    @elseif($grade == 'B') text-blue-600 dark:text-blue-400
                                    @elseif($grade == 'C') text-yellow-600 dark:text-yellow-400
                                    @else text-red-600 dark:text-red-400 @endif">
                                    {{ $grade }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @foreach($nilais as $nilai)
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white">{{ $nilai->aspek_penilaian }}</p>
                                        @if($nilai->catatan)
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $nilai->catatan }}</p>
                                        @endif
                                        {{ isset($nilai->tanggal_penilaian) ? \Carbon\Carbon::parse($nilai->tanggal_penilaian)->format('d/m/Y') : '-' }}
                                    </div>
                                    <div class="flex items-center gap-3 mt-2 sm:mt-0">
                                        <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ $nilai->nilai }}
                                        </span>
                                        <a href="{{ route('guru.nilai.edit', $nilai->id) }}" class="btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('guru.nilai.destroy', $nilai->id) }}" 
                                              method="POST" class="inline" onsubmit="return confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-star text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                            <p class="text-gray-500 dark:text-gray-400">Belum ada nilai untuk siswa ini</p>
                            <a href="{{ route('guru.nilai.create', $penempatan->id) }}" class="btn-primary mt-3 inline-block">
                                <i class="fas fa-plus mr-2"></i> Tambah Nilai Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection