@extends('layouts.app')

@section('title', 'Laporan Jurnal PKL')
@section('page-title', 'Laporan Jurnal PKL')
@section('page-subtitle', 'Rekapitulasi jurnal harian PKL')

@section('content')
<div class="animate-fadeIn">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-file-alt text-primary me-2"></i>Laporan Jurnal PKL</h4>
            <p class="text-muted mb-0">Rekapitulasi jurnal harian PKL</p>
        </div>
        <a href="{{ route('laporan.jurnal.pdf', request()->all()) }}" class="btn btn-danger mt-2 mt-sm-0">
            <i class="fas fa-file-pdf me-2"></i> Cetak PDF
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.jurnal') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ is_array(request('tanggal_mulai')) ? '' : request('tanggal_mulai') }}" class="form-control">
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ is_array(request('tanggal_selesai')) ? '' : request('tanggal_selesai') }}" class="form-control">
                </div>
                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'guru')
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label">Siswa</label>
                        <select name="siswa_id" class="form-select">
                            <option value="">Semua Siswa</option>
                            @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}" {{ request('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama_siswa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-search me-2"></i> Filter
                        </button>
                        @if(request()->anyFilled(['tanggal_mulai', 'tanggal_selesai', 'siswa_id']))
                            <a href="{{ route('laporan.jurnal') }}" class="btn btn-outline-secondary" title="Reset">
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
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>NIS</th>
                            <th>Industri</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jurnals as $index => $jurnal)
                            <tr>
                                <td>{{ $jurnals->firstItem() + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}</td>
                                <td class="fw-semibold">{{ $jurnal->penempatan->siswa->nama_siswa ?? '-' }}</td>
                                <td>{{ $jurnal->penempatan->siswa->nis ?? '-' }}</td>
                                <td>{{ $jurnal->penempatan->industri->nama_perusahaan ?? '-' }}</td>
                                <td style="max-width: 320px;">{{ \Illuminate\Support\Str::limit($jurnal->aktivitas, 80) }}</td>
                                <td>
                                    @if($jurnal->status == 'submitted')
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Submitted</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fas fa-pencil-alt me-1"></i> Draft</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Tidak ada data jurnal
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($jurnals->hasPages())
            <div class="card-footer">
                {{ $jurnals->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection