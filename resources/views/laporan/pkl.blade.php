@extends('layouts.app')

@section('title', 'Laporan PKL')
@section('page-title', 'Laporan PKL')
@section('page-subtitle', 'Rekap nilai PKL dari industri')

@section('content')
<div class="animate-fadeIn">
    <div class="mb-4">
        <h4 class="fw-bold mb-1"><i class="fas fa-clipboard-list text-primary me-2"></i>Laporan PKL</h4>
        <p class="text-muted mb-0">Daftar nilai siswa dari industri sesuai template penilaian sekolah.</p>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.pkl') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-8 col-lg-9">
                    <label class="form-label">Cari Siswa</label>
                    <input type="text" name="search" value="{{ is_array(request('search')) ? '' : request('search') }}" placeholder="Nama atau NIS..." class="form-control">
                </div>
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-search me-2"></i> Filter
                        </button>
                        @if (request('search') || request('status'))
                            <a href="{{ route('laporan.pkl') }}" class="btn btn-outline-secondary" title="Reset">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Industri</th>
                            @if (auth()->user()->role == 'guru')
                                <th>Guru Pembimbing</th>
                            @endif
                            <th>Rata-rata</th>
                            <th>Status Penilaian</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penempatans as $index => $penempatan)
                            @php
                                $nilaiIndustri = $penempatan->monitoringNilai->where('role_penilai', 'industri')->avg('nilai');
                                $sudahDinilai = $penempatan->jumlah_nilai_industri > 0;
                            @endphp
                            <tr>
                                <td>{{ $penempatans->firstItem() + $index }}</td>
                                <td>{{ $penempatan->siswa->nis ?? '-' }}</td>
                                <td class="fw-semibold">{{ $penempatan->siswa->nama_siswa ?? '-' }}</td>
                                <td>{{ $penempatan->industri->nama_perusahaan ?? '-' }}</td>
                                @if (auth()->user()->role == 'guru')
                                    <td>{{ $penempatan->guru->nama_guru ?? '-' }}</td>
                                @endif
                                <td>
                                    @if ($sudahDinilai)
                                        <span class="fw-bold text-primary">{{ number_format($nilaiIndustri, 1) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($sudahDinilai)
                                        <span class="badge bg-success">Sudah Dinilai</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Dinilai</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('laporan.pkl.show', $penempatan->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye me-1"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role == 'guru' ? 8 : 7 }}" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Tidak ada data laporan PKL
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penempatans->hasPages())
            <div class="card-footer">
                {{ $penempatans->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
