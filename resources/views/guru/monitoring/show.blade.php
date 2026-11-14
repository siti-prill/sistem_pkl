@extends('layouts.app')

@section('title', 'Detail Monitoring')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('guru.monitoring.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-user-graduate mr-2 text-indigo-500"></i> Monitoring: {{ $penempatan->siswa->nama_siswa }}
        </h2>
    </div>

    <!-- Info Siswa -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">NIS</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $penempatan->siswa->nis }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nama</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $penempatan->siswa->nama_siswa }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Industri</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $penempatan->industri->nama_perusahaan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                <span class="px-2 py-1 rounded-full text-sm bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                    {{ ucfirst($penempatan->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Nilai dari Industri -->
    @php
        $nilaiIndustri = \App\Models\MonitoringNilai::where('penempatan_id', $penempatan->id)
            ->where('role_penilai', 'industri')
            ->get();
    @endphp

    @if($nilaiIndustri->count() > 0)
        @php
            $rataIndustri = $nilaiIndustri->avg('nilai');
            $gradeIndustri = $rataIndustri >= 85 ? 'A' : ($rataIndustri >= 70 ? 'B' : ($rataIndustri >= 60 ? 'C' : 'D'));
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-clipboard-check mr-2 text-teal-500"></i> Nilai dari Industri
            </h3>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="text-center p-3 bg-teal-50 dark:bg-teal-900/20 rounded-lg">
                    <p class="text-sm text-gray-500">Jumlah Aspek</p>
                    <p class="text-2xl font-bold text-teal-600">{{ $nilaiIndustri->count() }}</p>
                </div>
                <div class="text-center p-3 bg-teal-50 dark:bg-teal-900/20 rounded-lg">
                    <p class="text-sm text-gray-500">Rata-rata</p>
                    <p class="text-2xl font-bold text-teal-600">{{ number_format($rataIndustri, 1) }}</p>
                </div>
                <div class="text-center p-3 bg-teal-50 dark:bg-teal-900/20 rounded-lg">
                    <p class="text-sm text-gray-500">Grade</p>
                    <p class="text-2xl font-bold text-teal-600">{{ $gradeIndustri }}</p>
                </div>
            </div>
            <div class="space-y-2">
                @foreach($nilaiIndustri as $nilai)
                    <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $nilai->aspek_penilaian }}</span>
                        <span class="font-bold text-teal-600">{{ $nilai->nilai }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        
<div class="flex items-center gap-3 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2.5 mb-4
            dark:border-amber-800 dark:bg-amber-900/20">
    <i class="fas fa-clock text-gray-700 shrink-0"></i>
    <p class="text-sm text-amber-800 dark:text-amber-200">
        Industri belum mengisi penilaian untuk siswa ini.
    </p>
</div>
    @endif

    <!-- Jurnal List -->
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
        <i class="fas fa-book mr-2"></i> Jurnal Harian
    </h3>

        @php
        $jurnalsSubmitted = $jurnals->filter(fn($j) => $j->status === 'submitted');
    @endphp

    @forelse($jurnalsSubmitted as $jurnal)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-semibold text-gray-800 dark:text-white">
                            {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}
                        </span>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                            <i class="fas fa-check-circle mr-1"></i> Submitted
                        </span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $jurnal->aktivitas }}</p>
                    @if($jurnal->dokumentasi)
                        @php $isUrl = \Illuminate\Support\Str::startsWith($jurnal->dokumentasi, ['http://', 'https://']); @endphp
                        <div class="mt-2">
                            <a href="{{ $isUrl ? $jurnal->dokumentasi : route('jurnal.dokumentasi', $jurnal) }}" target="_blank" rel="noopener"
                               class="text-blue-500 hover:text-blue-700 text-sm">
                                <i class="fas {{ $isUrl ? 'fa-external-link-alt' : 'fa-image' }} mr-1"></i> Lihat Dokumentasi
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Komentar -->
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                    <i class="fas fa-comment mr-1"></i> Komentar
                </h4>
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

                <!-- Form Komentar -->
                <form action="{{ route('guru.komentar.store', $jurnal->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="komentar" placeholder="Tulis komentar..." 
                               class="form-input flex-1" required>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
            <i class="fas fa-book-open text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Belum Ada Jurnal</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Siswa belum mengisi atau mensubmit jurnal</p>
        </div>
    @endforelse
    <div class="mt-4">
        {{ $jurnals->withQueryString()->links() }}
    </div>
</div>
@endsection