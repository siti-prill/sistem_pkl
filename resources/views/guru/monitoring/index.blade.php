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
                    <input type="text" name="search" value="{{ is_array(request('search')) ? '' : request('search') }}" 
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

    <!-- Table Penempatan -->
    <div class="table-container mb-8">
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

    <!-- LIST JURNAL HARIAN -->
    <div class="mt-8">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">
            <i class="fas fa-book-open mr-2 text-indigo-500"></i> Jurnal Harian Siswa (Terbaru)
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($penempatans as $penempatan)
                <!-- Menggunakan optional chaining (?->) untuk mencegah error jika relasi null -->
                @forelse($penempatan->jurnals?->where('status', 'submitted')->take(5) ?? [] as $jurnal)
                    <!-- Card Jurnal -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 flex justify-between items-center border border-gray-100 dark:border-gray-700">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-800 dark:text-white">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">
                                    Hari ke-{{ \Carbon\Carbon::parse($penempatan->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($jurnal->tanggal)) + 1 }}
                                </span>
                            </div>
                            <span class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">
                                <i class="fas fa-user mr-1 text-xs"></i> {{ $penempatan->siswa->nama_siswa }}
                            </span>
                        </div>
                        <button type="button" class="btn-primary btn-sm" onclick="showDetailJurnal({{ $jurnal->id }})">
                            <i class="fas fa-eye mr-1"></i> Lihat Detail
                        </button>
                    </div>

                    <!-- Modal Pop Up -->
                    <div id="modal-jurnal-{{ $jurnal->id }}" class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm hidden items-center justify-center z-[100] p-4 transition-all duration-300">
                        <!-- Backdrop klik untuk close -->
                        <div class="absolute inset-0" onclick="closeDetailJurnal({{ $jurnal->id }})"></div>

                        <div class="relative bg-white dark:bg-gray-800 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col z-10">
                            
                            <!-- Header Modal -->
                            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-4 flex items-center justify-between shrink-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                        <i class="fas fa-book-open text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Detail Jurnal</h3>
                                        <p class="text-sm text-indigo-100">{{ $penempatan->siswa->nama_siswa }}</p>
                                    </div>
                                </div>
                                <button type="button" onclick="closeDetailJurnal({{ $jurnal->id }})" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <!-- Isi Modal -->
                            <div class="p-6 overflow-y-auto space-y-5">
                                <!-- Aktivitas -->
                                <div>
                                    <h4 class="font-bold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
                                        <i class="fas fa-tasks text-indigo-500"></i> Aktivitas
                                    </h4>
                                    <div class="bg-gray-50 dark:bg-gray-700/60 border border-gray-100 dark:border-gray-700 rounded-xl p-4">
                                        <p class="text-sm leading-7 text-gray-700 dark:text-gray-200 whitespace-pre-wrap">{{ $jurnal->aktivitas }}</p>
                                    </div>
                                </div>

                                <!-- Dokumentasi -->
                                @if($jurnal->dokumentasi)
                                    @php $isUrl = \Illuminate\Support\Str::startsWith($jurnal->dokumentasi, ['http://', 'https://']); @endphp
                                    <div>
                                        <h4 class="font-bold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
                                            <i class="fas fa-images text-purple-500"></i> Dokumentasi
                                        </h4>
                                        <a href="{{ $isUrl ? $jurnal->dokumentasi : route('jurnal.dokumentasi', $jurnal) }}" target="_blank" rel="noopener" class="flex items-center justify-between p-3 rounded-xl border border-indigo-100 dark:border-gray-700 bg-indigo-50/50 hover:bg-indigo-100 transition">
                                            <span class="font-semibold text-sm text-gray-800 dark:text-white">
                                                <i class="fas {{ $isUrl ? 'fa-link' : 'fa-image' }} text-indigo-500 mr-2"></i> Buka Dokumentasi
                                            </span>
                                            <i class="fas fa-external-link-alt text-indigo-500"></i>
                                        </a>
                                    </div>
                                @endif

                                <!-- Komentar -->
                                <div>
                                    <h4 class="font-bold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
                                        <i class="fas fa-comments text-teal-500"></i> Komentar ({{ $jurnal->komentarJurnal->count() }})
                                    </h4>
                                    
                                    @if($jurnal->komentarJurnal->count() > 0)
                                        <div class="space-y-2 mb-3">
                                            @foreach($jurnal->komentarJurnal as $komentar)
                                                <div class="p-3 bg-gray-50 dark:bg-gray-700/60 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <div class="flex justify-between items-start mb-1">
                                                        <p class="font-semibold text-sm text-gray-800 dark:text-white">{{ $komentar->guru->nama_guru ?? 'Guru' }}</p>
                                                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($komentar->created_at)->format('d/m/Y H:i') }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $komentar->komentar }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 italic mb-3">Belum ada komentar.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            @endforeach
        </div>
    </div>
</div>

<!-- Script Modal -->
<script>
    function showDetailJurnal(id) {
        const modal = document.getElementById('modal-jurnal-' + id);
        if (modal) { 
            modal.classList.remove('hidden'); 
            modal.classList.add('flex'); 
            document.body.style.overflow = 'hidden';
        }
    }
    function closeDetailJurnal(id) {
        const modal = document.getElementById('modal-jurnal-' + id);
        if (modal) { 
            modal.classList.add('hidden'); 
            modal.classList.remove('flex'); 
            document.body.style.overflow = '';
        }
    }
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('[id^="modal-jurnal-"]').forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    closeDetailJurnal(modal.id.replace('modal-jurnal-', ''));
                }
            });
        }
    });
</script>
@endsection