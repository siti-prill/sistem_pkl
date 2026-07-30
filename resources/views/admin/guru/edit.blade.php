@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')
<div class="animate-fadeIn">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold text-dark mb-0">
                <i class="fas fa-edit text-warning me-2"></i> Edit Guru
            </h2>
            <p class="text-muted">Perbarui data guru pembimbing</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.guru.update', $guru) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}" 
                               class="form-control @error('nip') is-invalid @enderror" required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_guru" value="{{ old('nama_guru', $guru->nama_guru) }}" 
                               class="form-control @error('nama_guru') is-invalid @enderror" required>
                        @error('nama_guru')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $guru->user->email) }}" 
                               class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password <span class="text-muted">(Kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Minimal 8 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">No Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $guru->no_telepon) }}" 
                               class="form-control @error('no_telepon') is-invalid @enderror">
                        @error('no_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $guru->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update
                        </button>
                        <a href="{{ route('admin.guru.index') }}" class="btn btn-danger">
                            <i class="fas fa-times me-2"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection