@extends('layouts.app')

@section('title', 'Detail Jurnal')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('siswa.jurnal.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-book mr-2 text-indigo-500"></i> Detail Jurnal
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</h3>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}
                    </p>
                </div>
                <div>
                    @if($jurnal->status == 'submitted')
                        <span class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                            <i class="fas fa-check-circle mr-1"></i> Submitted
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300">
                            <i class="fas fa-pencil-alt mr-1"></i> Draft
                        </span>
                    @endif
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Aktivitas</h3>
                <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $jurnal->aktivitas }}</p>
            </div>

            @if($jurnal->dokumentasi)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Dokumentasi</h3>
                    <img src="/storage/{{ $jurnal->dokumentasi }}" 
                         alt="Dokumentasi" class="rounded-lg max-h-96 w-auto">
                </div>
            @endif

            <!-- Komentar -->
            @if($jurnal->komentarJurnal->count() > 0)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                        <i class="fas fa-comments mr-1"></i> Komentar Guru ({{ $jurnal->komentarJurnal->count() }})
                    </h3>
                    @foreach($jurnal->komentarJurnal as $komentar)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-2">
                            <div class="flex justify-between items-start">
                                <p class="font-medium text-gray-800 dark:text-white">{{ $komentar->guru->nama_guru }}</p>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $komentar->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $komentar->komentar }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('siswa.jurnal.index') }}" class="btn-primary">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                @if($jurnal->status == 'draft')
                    <a href="{{ route('siswa.jurnal.edit', $jurnal) }}" class="btn-warning">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <form action="{{ route('siswa.jurnal.submit', $jurnal) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-success" onclick="return confirm('Submit jurnal ini?')">
                            <i class="fas fa-check mr-2"></i> Submit
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection