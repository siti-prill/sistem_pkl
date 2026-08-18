@extends('layouts.app')

@section('title', 'Jurnal Harian')
@section('page-title', 'Jurnal Harian PKL')
@section('page-subtitle', 'Catat aktivitas harian selama PKL')

@section('content')
<div class="animate-fadeIn">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">
                <i class="fas fa-book me-2 text-primary"></i> Jurnal Harian PKL
            </h2>
            <p class="text-muted">Catat aktivitas harian selama PKL</p>
        </div>
        <a href="{{ route('siswa.jurnal.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Buat Jurnal
        </a>
    </div>

    @if(!$penempatan)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Anda belum memiliki penempatan PKL aktif. Silakan hubungi admin.
        </div>
    @else
        <!-- Info Penempatan -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted">Perusahaan</small>
                        <p class="fw-semibold">{{ $penempatan->industri->nama_perusahaan ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Tanggal Mulai</small>
                        <p class="fw-semibold">{{ \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Tanggal Selesai</small>
                        <p class="fw-semibold">{{ \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted">Status</small>
                        <p><span class="badge bg-success">{{ ucfirst($penempatan->status) }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('siswa.jurnal.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i> Filter
                        </button>
                        @if(request('tanggal') || request('status'))
                            <a href="{{ route('siswa.jurnal.index') }}" class="btn btn-danger">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Jurnal List -->
        @forelse($jurnals as $jurnal)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}
                                </h5>
                                @if($jurnal->status == 'submitted')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Submitted
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-pencil-alt me-1"></i> Draft
                                    </span>
                                @endif
                            </div>
                            <p class="text-muted mt-2 mb-0">{{ Str::limit($jurnal->aktivitas, 150) }}</p>
                            @if($jurnal->dokumentasi)
                                <small class="text-primary">
                                    <i class="fas fa-image me-1"></i> Ada dokumentasi
                                </small>
                            @endif
                            @if($jurnal->komentarJurnal->count() > 0)
                                <div class="mt-1">
                                    <small class="text-info">
                                        <i class="fas fa-comment me-1"></i> {{ $jurnal->komentarJurnal->count() }} komentar
                                    </small>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex gap-2 mt-2 mt-sm-0">
                            <a href="{{ route('siswa.jurnal.show', $jurnal) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($jurnal->status == 'draft')
                                <a href="{{ route('siswa.jurnal.edit', $jurnal) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('siswa.jurnal.destroy', $jurnal) }}" 
                                      method="POST" class="d-inline" onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <form action="{{ route('siswa.jurnal.submit', $jurnal) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" 
                                            onclick="return confirm('Submit jurnal ini?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book-open text-muted display-1 mb-3"></i>
                    <h5>Belum Ada Jurnal</h5>
                    <p class="text-muted">Mulai catat aktivitas PKL Anda sekarang</p>
                    <a href="{{ route('siswa.jurnal.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Buat Jurnal Pertama
                    </a>
                </div>
            </div>
        @endforelse

        <div class="mt-3">
            {{ $jurnals->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection