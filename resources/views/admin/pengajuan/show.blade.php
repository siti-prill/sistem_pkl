@extends('layouts.app')

@section('title', 'Detail Pengajuan PKL')
@section('page-title', 'Detail Pengajuan')
@section('page-subtitle', 'Verifikasi pengajuan tempat PKL')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5>Detail Pengajuan - {{ $pengajuan->siswa->nama_siswa }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>NIS:</strong> {{ $pengajuan->siswa->nis }}</p>
                        <p><strong>Nama:</strong> {{ $pengajuan->siswa->nama_siswa }}</p>
                        <p><strong>Kelas:</strong> {{ $pengajuan->siswa->kelas }}</p>
                        <p><strong>Jurusan:</strong> {{ $pengajuan->jurusan }}</p>
                        <p><strong>Pilihan 1:</strong> {{ $pengajuan->pilihan_1 }}</p>
                        <p><strong>Pilihan 2:</strong> {{ $pengajuan->pilihan_2 }}</p>
                        <p><strong>Penghasilan Orang Tua:</strong> Rp
                            {{ number_format($pengajuan->penghasilan_ortu, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status Saat Ini:</strong>
                            @if ($pengajuan->status == 'pending')
                                <span class="badge bg-warning text-dark">Menunggu</span>
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

                <hr>

                <form action="{{ route('admin.pengajuan.update', $pengajuan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $pengajuan->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="diterima" {{ $pengajuan->status == 'diterima' ? 'selected' : '' }}>Diterima
                                </option>
                                <option value="ditolak" {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>Ditolak
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tempat Diterima</label>
                            <input type="text" name="tempat_diterima" class="form-control"
                                value="{{ $pengajuan->tempat_diterima }}" placeholder="Isi jika diterima">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Catatan Admin</label>
                            <textarea name="catatan_admin" class="form-control" rows="2" placeholder="Catatan untuk siswa">{{ $pengajuan->catatan_admin }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
