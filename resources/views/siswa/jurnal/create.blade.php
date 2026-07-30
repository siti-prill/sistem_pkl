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
            <form action="{{ route('siswa.jurnal.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- HIDDEN INPUT UNTUK PENEMPATAN_ID -->
                <input type="hidden" name="penempatan_id" value="{{ $penempatan->id ?? '' }}">
                
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
                        <select name="status" class="form-select">
                            <option value="draft">Draft (Simpan sebagai draft)</option>
                            <option value="submitted">Submit (Kirim langsung)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <input type="file" name="dokumentasi" accept="image/*" 
                               class="form-control @error('dokumentasi') is-invalid @enderror">
                        <small class="text-muted">Format: JPEG, PNG, JPG, GIF (Max 2MB)</small>
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