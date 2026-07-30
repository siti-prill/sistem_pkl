@extends('layouts.app')

@section('title', 'Status Pengajuan PKL')
@section('page-title', 'Pengajuan PKL')
@section('page-subtitle', 'Status pengajuan tempat PKL Anda')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($pengajuan)
            <div class="card">
                <div class="card-header">
                    <h5>Detail Pengajuan PKL</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Pilihan 1:</strong> {{ $pengajuan->pilihan_1 }}</p>
                            <p><strong>Pilihan 2:</strong> {{ $pengajuan->pilihan_2 }}</p>
                            <p><strong>Jurusan:</strong> {{ $pengajuan->jurusan }}</p>
                            <p><strong>Penghasilan Orang Tua:</strong> Rp
                                {{ number_format($pengajuan->penghasilan_ortu, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong>
                                @if ($pengajuan->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                @elseif($pengajuan->status == 'diterima')
                                    <span class="badge bg-success">Diterima</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </p>
                            @if ($pengajuan->status == 'diterima')
                                <p><strong>Tempat Diterima:</strong> {{ $pengajuan->tempat_diterima }}</p>
                            @endif
                            @if ($pengajuan->catatan_admin)
                                <p><strong>Catatan Admin:</strong> {{ $pengajuan->catatan_admin }}</p>
                            @endif
                            <p><strong>Tanggal Pengajuan:</strong> {{ $pengajuan->created_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                <h5>Belum Ada Pengajuan</h5>
                <p class="text-muted">Silakan ajukan tempat PKL Anda sekarang.</p>
                <a href="{{ route('siswa.pengajuan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Ajukan PKL
                </a>
            </div>
        @endif
    </div>
@endsection
