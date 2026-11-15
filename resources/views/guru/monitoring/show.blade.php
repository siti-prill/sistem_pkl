@extends('layouts.app')

@section('title', 'Detail Monitoring')

@section('content')
    <div class="animate-fadeIn">
        <!-- Tombol Kembali & Judul -->
        <div class="flex items-center mb-6">
            <a href="{{ route('guru.monitoring.index') }}"
                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4 transition">
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
                    <span
                        class="px-2 py-1 rounded-full text-sm bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
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

        @if ($nilaiIndustri->count() > 0)
            @php
                $rataIndustri = $nilaiIndustri->avg('nilai');
                $gradeIndustri =
                    $rataIndustri >= 85 ? 'A' : ($rataIndustri >= 70 ? 'B' : ($rataIndustri >= 60 ? 'C' : 'D'));
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
                    @foreach ($nilaiIndustri as $nilai)
                        <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $nilai->aspek_penilaian }}</span>
                            <span class="font-bold text-teal-600">{{ $nilai->nilai }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
        @endif

        <!-- Jurnal List -->
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            <i class="fas fa-book mr-2 text-indigo-500"></i> Jurnal Harian
        </h3>

        @php
            $jurnalsSubmitted = $jurnals->filter(fn($j) => $j->status === 'submitted');
        @endphp

        @forelse($jurnalsSubmitted as $jurnal)
            <!-- Card Jurnal (Hanya Tanggal, Hari, Tombol) -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 mb-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-wrap items-center gap-4">
                    <h4 class="font-bold text-lg text-gray-800 dark:text-white">
                        {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}
                    </h4>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        (Hari ke-{{ \Carbon\Carbon::parse($penempatan->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($jurnal->tanggal)) + 1 }})
                    </span>
                    <span
                        class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                        <i class="fas fa-check-circle mr-1"></i> Submitted
                    </span>
                </div>
                <button type="button" class="btn-primary btn-sm" onclick="showDetailJurnal({{ $jurnal->id }})">
                    <i class="fas fa-eye mr-1"></i> Lihat Detail
                </button>
            </div>

            <!-- Modal Detail Jurnal -->
            <div id="modal-jurnal-{{ $jurnal->id }}"
                class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm hidden items-center justify-center z-[100] p-4 transition-all duration-300">

                <!-- Klik di area luar (backdrop) untuk menutup -->
                <div class="absolute inset-0" onclick="closeDetailJurnal({{ $jurnal->id }})"></div>

                <!-- Konten Modal -->
                <div
                    class="relative bg-white dark:bg-gray-800 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col z-10">

                    <!-- Header Modal -->
                    <div
                        class="bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-5 flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                                <i class="fas fa-book-open text-white text-xl"></i>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-white">
                                    Detail Jurnal Harian
                                </h3>
                                <p class="text-sm text-indigo-100">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}
                                </p>
                            </div>
                        </div>

                        <button type="button" onclick="closeDetailJurnal({{ $jurnal->id }})"
                            class="w-9 h-9 rounded-lg bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Isi Modal -->
                    <div class="p-6 overflow-y-auto space-y-5">

                        <!-- Informasi Jurnal -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    <i class="fas fa-calendar-alt mr-1"></i> Tanggal
                                </p>
                                <p class="font-semibold text-gray-800 dark:text-white">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}
                                </p>
                            </div>

                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    <i class="fas fa-check-circle mr-1"></i> Status
                                </p>
                                <span
                                    class="inline-flex items-center gap-1 text-sm font-semibold text-green-700 dark:text-green-400">
                                    Submitted
                                </span>
                            </div>
                        </div>

                        <!-- Aktivitas -->
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                                    <i class="fas fa-tasks text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <h4 class="font-bold text-gray-800 dark:text-white">
                                    Aktivitas Harian
                                </h4>
                            </div>

                            <div
                                class="bg-gray-50 dark:bg-gray-700/60 border border-gray-100 dark:border-gray-700 rounded-xl p-4">
                                <p class="text-sm leading-7 text-gray-700 dark:text-gray-200 whitespace-pre-wrap">
                                    {{ $jurnal->aktivitas }}</p>
                            </div>
                        </div>

                        <!-- Dokumentasi -->
                        @if ($jurnal->dokumentasi)
                            @php
                                $isUrl = \Illuminate\Support\Str::startsWith($jurnal->dokumentasi, [
                                    'http://',
                                    'https://',
                                ]);
                            @endphp

                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center">
                                        <i class="fas fa-images text-purple-600 dark:text-purple-400"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-800 dark:text-white">
                                        Dokumentasi
                                    </h4>
                                </div>

                                <a href="{{ $isUrl ? $jurnal->dokumentasi : route('jurnal.dokumentasi', $jurnal) }}"
                                    target="_blank" rel="noopener"
                                    class="flex items-center justify-between p-4 rounded-xl border border-indigo-100 dark:border-gray-700 bg-indigo-50/50 dark:bg-gray-700/50 hover:bg-indigo-100 dark:hover:bg-gray-700 transition">

                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                                            <i
                                                class="fas {{ $isUrl ? 'fa-link' : 'fa-image' }} text-indigo-600 dark:text-indigo-400"></i>
                                        </div>

                                        <div>
                                            <p class="font-semibold text-sm text-gray-800 dark:text-white">
                                                Lihat Dokumentasi Foto
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Buka file dokumentasi jurnal
                                            </p>
                                        </div>
                                    </div>

                                    <i class="fas fa-external-link-alt text-indigo-500"></i>
                                </a>
                            </div>
                        @endif

                        <!-- Komentar -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center">
                                        <i class="fas fa-comments text-teal-600 dark:text-teal-400"></i>
                                    </div>

                                    <h4 class="font-bold text-gray-800 dark:text-white">
                                        Komentar Guru
                                    </h4>
                                </div>

                                <span
                                    class="text-xs px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    {{ $jurnal->komentarJurnal->count() }} Komentar
                                </span>
                            </div>

                            <!-- Daftar Komentar -->
                            @if ($jurnal->komentarJurnal->count() > 0)
                                <div class="space-y-3 mb-4">
                                    @foreach ($jurnal->komentarJurnal as $komentar)
                                        <div
                                            class="flex gap-3 p-4 bg-gray-50 dark:bg-gray-700/60 rounded-xl border border-gray-100 dark:border-gray-700">

                                            <div
                                                class="w-10 h-10 shrink-0 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                                                <i class="fas fa-user-tie text-indigo-600 dark:text-indigo-400"></i>
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-wrap justify-between gap-2 mb-1">
                                                    <p class="font-semibold text-sm text-gray-800 dark:text-white">
                                                        {{ $komentar->guru->nama_guru ?? 'Guru Pembimbing' }}
                                                    </p>

                                                    <span class="text-xs text-gray-400">
                                                        {{ \Carbon\Carbon::parse($komentar->created_at)->format('d/m/Y H:i') }}
                                                    </span>
                                                </div>

                                                <p
                                                    class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">
                                                    {{ $komentar->komentar }}
                                                </p>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div
                                    class="text-center py-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-600 mb-4">
                                    <i class="fas fa-comment-slash text-3xl text-gray-300 dark:text-gray-500 mb-2"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada komentar dari guru pembimbing.
                                    </p>
                                </div>
                            @endif

                            <!-- Form Komentar -->
                            <form action="{{ route('guru.komentar.store', $jurnal->id) }}" method="POST">
                                @csrf

                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Berikan Komentar
                                </label>

                                <div class="flex flex-col sm:flex-row gap-2">
                                    <input type="text" name="komentar"
                                        class="form-input flex-1 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500"
                                        placeholder="Tulis komentar atau evaluasi jurnal..." required>

                                    <button type="submit"
                                        class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition flex items-center justify-center gap-2">
                                        <i class="fas fa-paper-plane"></i>
                                        Kirim
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <!-- Footer Modal -->
                    <div
                        class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex justify-end shrink-0">
                        <button type="button" onclick="closeDetailJurnal({{ $jurnal->id }})"
                            class="px-5 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold transition">
                            Tutup
                        </button>
                    </div>

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

    <!-- Script untuk Modal Pop-up -->
    <script>
        function showDetailJurnal(id) {
            const modal = document.getElementById('modal-jurnal-' + id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden'; // Mencegah background di-scroll
            }
        }

        function closeDetailJurnal(id) {
            const modal = document.getElementById('modal-jurnal-' + id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = ''; // Mengembalikan scroll background
            }
        }

        // Tutup modal dengan tombol ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = document.querySelectorAll('[id^="modal-jurnal-"]');
                modals.forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        const id = modal.id.replace('modal-jurnal-', '');
                        closeDetailJurnal(id);
                    }
                });
            }
        });
    </script>
@endsection