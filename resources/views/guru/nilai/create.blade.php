@extends('layouts.app')

@section('title', 'Tambah Nilai')
@section('page-title', 'Tambah Nilai')
@section('page-subtitle', 'Penilaian PKL')

@section('content')
<div class="animate-fadeIn">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('guru.nilai.index') }}" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold text-dark mb-0">
                <i class="fas fa-star text-warning me-2"></i> Tambah Nilai
            </h2>
            <p class="text-muted">Berikan penilaian untuk siswa</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Siswa:</strong> {{ $penempatan->siswa->nama_siswa }} ({{ $penempatan->siswa->nis }}) |
                <strong>Industri:</strong> {{ $penempatan->industri->nama_perusahaan }} |
                <strong>Kompetensi:</strong> {{ $penempatan->kompetensi->nama_kompetensi }}
            </div>

            <form action="{{ route('guru.nilai.store') }}" method="POST">
                @csrf
                
                <!-- ===== HIDDEN INPUT ===== -->
                <input type="hidden" name="penempatan_id" value="{{ $penempatan->id }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Aspek Penilaian <span class="text-danger">*</span></label>
                        <input type="text" name="aspek_penilaian" class="form-control @error('aspek_penilaian') is-invalid @enderror" 
                               placeholder="Contoh: Kedisiplinan, Keterampilan, dll" value="{{ old('aspek_penilaian') }}" required>
                        @error('aspek_penilaian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nilai (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror" 
                               placeholder="Masukkan nilai 0-100" min="0" max="100" value="{{ old('nilai') }}" required>
                        @error('nilai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Penilaian <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_penilaian" class="form-control @error('tanggal_penilaian') is-invalid @enderror" 
                               value="{{ old('tanggal_penilaian', date('Y-m-d')) }}" required>
                        @error('tanggal_penilaian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror" 
                                  placeholder="Catatan tambahan tentang penilaian">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan
                            </button>
                            <a href="{{ route('guru.nilai.index') }}" class="btn btn-danger">
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