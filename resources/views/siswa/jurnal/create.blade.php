@extends('layouts.app')

@section('title', 'Buat Jurnal')
@section('page-title', 'Buat Jurnal Harian')
@section('page-subtitle', 'Catat aktivitas PKL hari ini')

@section('content')
<div class="animate-fadeIn">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('siswa.jurnal.index') }}" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold text-dark mb-0">
                <i class="fas fa-plus-circle text-primary me-2"></i> Buat Jurnal Harian
            </h2>
            <p class="text-muted">Catat aktivitas yang Anda lakukan hari ini</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('siswa.jurnal.store') }}" method="POST">
                @csrf

                <!-- penempatan_id & status ditentukan server (bukan dari input user) -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                               class="form-control @error('tanggal') is-invalid @enderror" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <p class="form-control-plaintext">Draft (kirim ke guru lewat tombol "Submit" setelah disimpan)</p>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Aktivitas Hari Ini <span class="text-danger">*</span></label>
                        <textarea name="aktivitas" rows="6" class="form-control @error('aktivitas') is-invalid @enderror" 
                                  placeholder="Deskripsikan aktivitas yang dikerjakan hari ini..." required>{{ old('aktivitas') }}</textarea>
                        @error('aktivitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Dokumentasi (Foto)</label>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <a href="https://drive.google.com/drive/folders/1oWZTchR0t5_frbcFs3Yto5aUm8LXOtVM?usp=drive_link"
                               target="_blank" rel="noopener" class="btn btn-outline-primary">
                                <i class="fas fa-cloud-upload-alt me-2"></i> Upload Foto ke Google Drive
                            </a>
                            <small class="text-muted">Klik untuk membuka folder Drive, upload foto di sana, lalu salin link berbagi fotonya dan tempel di bawah.</small>
                        </div>
                        <input type="url" name="dokumentasi" value="{{ old('dokumentasi') }}"
                               placeholder="Tempel link berbagi foto Google Drive di sini (https://...)"
                               class="form-control @error('dokumentasi') is-invalid @enderror">
                        <small class="text-muted">Pastikan akses link foto dapat dilihat (mis. "Siapa saja yang memiliki link").</small>
                        @error('dokumentasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan
                            </button>
                            <a href="{{ route('siswa.jurnal.index') }}" class="btn btn-danger">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection