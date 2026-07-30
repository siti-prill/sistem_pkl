@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview PKL')

@section('content')
    <div class="animate-fadeIn">
        <!-- Stats Cards -->
<div class="row g-3 g-lg-4 mb-4">
    <!-- Card 1: Total Siswa -->
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="stat-label">
                        <i class="fas fa-user-graduate me-1"></i> Total Siswa
                    </div>
                    <div class="stat-number text-primary">{{ $totalSiswa ?? 0 }}</div>
                    <div class="stat-change up">
                        <i class="fas fa-arrow-up me-1"></i> 12%
                    </div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary flex-shrink-0">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Guru -->
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="stat-label">
                        <i class="fas fa-chalkboard-teacher me-1"></i> Total Guru
                    </div>
                    <div class="stat-number text-success">{{ $totalGuru ?? 0 }}</div>
                    <div class="stat-change up">
                        <i class="fas fa-arrow-up me-1"></i> 5%
                    </div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success flex-shrink-0">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Industri -->
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="stat-label">
                        <i class="fas fa-building me-1"></i> Total Industri
                    </div>
                    <div class="stat-number text-info">{{ $totalIndustri ?? 0 }}</div>
                    <div class="stat-change up">
                        <i class="fas fa-arrow-up me-1"></i> 3 baru
                    </div>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info flex-shrink-0">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: PKL Aktif -->
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="stat-label">
                        <i class="fas fa-users me-1"></i> PKL Aktif
                    </div>
                    <div class="stat-number text-warning">{{ $totalAktif ?? 0 }}</div>
                    <div class="stat-change up">
                        <i class="fas fa-users me-1"></i> {{ $totalAktif ?? 0 }} aktif
                    </div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning flex-shrink-0">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Charts -->
        <div class="row g-3 g-lg-4 mb-4">
            <div class="col-lg-6">
                <div class="chart-container">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold mb-0">
                            <i class="fas fa-chart-pie text-primary me-2"></i> Status PKL
                        </h5>
                        <span class="text-muted small">
                            <i class="far fa-clock me-1"></i> Real-time
                        </span>
                    </div>
                    <div class="chart-wrapper" style="position: relative; height: 260px; width: 100%;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                        <div class="text-center">
                            <span class="badge bg-primary" style="width:12px;height:12px;display:inline-block;border-radius:50%;"></span>
                            <span class="text-muted small ms-1">Aktif: {{ $statusData['aktif'] ?? 0 }}</span>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-success" style="width:12px;height:12px;display:inline-block;border-radius:50%;"></span>
                            <span class="text-muted small ms-1">Selesai: {{ $statusData['selesai'] ?? 0 }}</span>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-danger" style="width:12px;height:12px;display:inline-block;border-radius:50%;"></span>
                            <span class="text-muted small ms-1">Batal: {{ $statusData['batal'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-container">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold mb-0">
                            <i class="fas fa-chart-bar text-primary me-2"></i> Jurnal Per Minggu
                        </h5>
                        <span class="text-muted small">
                            <i class="far fa-calendar-alt me-1"></i> Minggu ini
                        </span>
                    </div>
                    <div class="chart-wrapper" style="position: relative; height: 260px; width: 100%;">
                        <canvas id="jurnalChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Siswa Belum Jurnal -->
        <div class="card card-hover shadow-sm mb-4">
            <div class="card-header bg-transparent d-flex flex-wrap justify-content-between align-items-center py-3">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i> Siswa Belum Mengisi Jurnal Hari Ini
                </h5>
                <div class="mt-2 mt-sm-0">
                    <span class="badge bg-warning text-dark me-2">{{ $siswaBelumJurnal->count() ?? 0 }} Siswa</span>
                    <span class="text-muted small">
                        <i class="far fa-calendar-alt me-1"></i> {{ now()->format('d/m/Y') }}
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                @if(isset($siswaBelumJurnal) && $siswaBelumJurnal->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3 px-lg-4 py-3">NIS</th>
                                    <th class="px-3 px-lg-4 py-3">Nama Siswa</th>
                                    <th class="px-3 px-lg-4 py-3">Kelas</th>
                                    <th class="px-3 px-lg-4 py-3 d-none d-md-table-cell">Jurusan</th>
                                    <th class="px-3 px-lg-4 py-3 d-none d-lg-table-cell">Industri</th>
                                    <th class="px-3 px-lg-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswaBelumJurnal as $item)
                                    <tr>
                                        <td class="px-3 px-lg-4 py-3"><span class="fw-semibold">{{ $item->siswa->nis ?? '-' }}</span></td>
                                        <td class="px-3 px-lg-4 py-3">{{ $item->siswa->nama_siswa ?? '-' }}</td>
                                        <td class="px-3 px-lg-4 py-3">{{ $item->siswa->kelas ?? '-' }}</td>
                                        <td class="px-3 px-lg-4 py-3 d-none d-md-table-cell">
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                {{ $item->siswa->jurusan ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-3 px-lg-4 py-3 d-none d-lg-table-cell">{{ $item->industri->nama_perusahaan ?? '-' }}</td>
                                        <td class="px-3 px-lg-4 py-3 text-center">
                                            <button onclick="showWarning('{{ $item->siswa->nama_siswa ?? '' }}')" 
                                                    class="btn btn-warning btn-sm">
                                                <i class="fas fa-bell me-1"></i> <span class="d-none d-sm-inline">Ingatkan</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="text-success display-1 mb-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5 class="fw-semibold">Semua siswa sudah mengisi jurnal hari ini!</h5>
                        <p class="text-muted">Kerja bagus, semua siswa disiplin.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-3 g-lg-4">
            <div class="col-6 col-md-3">
                <div class="card card-hover shadow-sm text-center p-3">
                    <div class="text-primary mb-2">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h6 class="fw-semibold mb-1">Tambah Siswa</h6>
                    <p class="text-muted small mb-2 d-none d-sm-block">Registrasi siswa baru</p>
                    <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-plus me-1"></i> Tambah
                    </a>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-hover shadow-sm text-center p-3">
                    <div class="text-success mb-2">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                    <h6 class="fw-semibold mb-1">Tambah Industri</h6>
                    <p class="text-muted small mb-2 d-none d-sm-block">Tambah mitra industri</p>
                    <a href="{{ route('admin.industri.create') }}" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-plus me-1"></i> Tambah
                    </a>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-hover shadow-sm text-center p-3">
                    <div class="text-warning mb-2">
                        <i class="fas fa-people-arrows fa-2x"></i>
                    </div>
                    <h6 class="fw-semibold mb-1">Penempatan PKL</h6>
                    <p class="text-muted small mb-2 d-none d-sm-block">Atur penempatan siswa</p>
                    <a href="{{ route('admin.penempatan.create') }}" class="btn btn-warning btn-sm w-100">
                        <i class="fas fa-plus me-1"></i> Tambah
                    </a>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-hover shadow-sm text-center p-3">
                    <div class="text-info mb-2">
                        <i class="fas fa-file-pdf fa-2x"></i>
                    </div>
                    <h6 class="fw-semibold mb-1">Cetak Laporan</h6>
                    <p class="text-muted small mb-2 d-none d-sm-block">Download laporan PDF</p>
                    <a href="{{ route('laporan.jurnal') }}" class="btn btn-info btn-sm w-100 text-white">
                        <i class="fas fa-print me-1"></i> Cetak
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.body.classList.contains('dark-mode');
    const textColor = isDark ? '#e5e7eb' : '#1f2937';
    const gridColor = isDark ? '#374151' : '#e5e7eb';
    
    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Selesai', 'Batal'],
            datasets: [{
                data: [{{ $statusData['aktif'] ?? 0 }}, {{ $statusData['selesai'] ?? 0 }}, {{ $statusData['batal'] ?? 0 }}],
                backgroundColor: ['#4f46e5', '#22c55e', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Jurnal Chart
    const jurnalCtx = document.getElementById('jurnalChart').getContext('2d');
    new Chart(jurnalCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($jurnalPerMinggu['labels'] ?? ['Sen','Sel','Rab','Kam','Jum','Sab','Min']) !!},
            datasets: [{
                label: 'Jumlah Jurnal',
                data: {!! json_encode($jurnalPerMinggu['data'] ?? [0,0,0,0,0,0,0]) !!},
                backgroundColor: ['#4f46e5', '#6366f1', '#818cf8', '#4f46e5', '#6366f1', '#818cf8', '#4f46e5'],
                borderColor: '#4338ca',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: textColor,
                        stepSize: 1
                    },
                    grid: {
                        color: gridColor,
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        color: textColor
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});

function showWarning(nama) {
    Swal.fire({
        title: 'Ingatkan Siswa',
        html: `Anda akan mengingatkan <strong>${nama}</strong> untuk mengisi jurnal hari ini.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Kirim Pengingat',
        cancelButtonText: 'Batal',
        background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#ffffff',
        color: document.body.classList.contains('dark-mode') ? '#f0f2f5' : '#1a1a2e'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pengingat berhasil dikirim.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#ffffff',
                color: document.body.classList.contains('dark-mode') ? '#f0f2f5' : '#1a1a2e'
            });
        }
    });
}
</script>
@endpush