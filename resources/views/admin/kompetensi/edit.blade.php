@extends('layouts.app')

@section('title', 'Edit Kompetensi')

@section('content')
<div class="animate-fadeIn">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.kompetensi.index') }}" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold text-dark mb-0">
                <i class="fas fa-edit text-warning me-2"></i> Edit Kompetensi
            </h2>
            <p class="text-muted">Perbarui data kompetensi</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.kompetensi.update', $kompetensi) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Kompetensi <span class="text-danger">*</span></label>
                        <input type="text" name="kode_kompetensi" value="{{ old('kode_kompetensi', $kompetensi->kode_kompetensi) }}" 
                               class="form-control @error('kode_kompetensi') is-invalid @enderror" required>
                        @error('kode_kompetensi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Kompetensi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kompetensi" value="{{ old('nama_kompetensi', $kompetensi->nama_kompetensi) }}" 
                               class="form-control @error('nama_kompetensi') is-invalid @enderror" required>
                        @error('nama_kompetensi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $kompetensi->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update
                        </button>
                        <a href="{{ route('admin.kompetensi.index') }}" class="btn btn-danger">
                            <i class="fas fa-times me-2"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection