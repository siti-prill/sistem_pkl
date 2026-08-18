@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
    <div class="animate-fadeIn">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline-secondary me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold text-dark mb-0">
                    <i class="fas fa-plus-circle text-primary me-2"></i> Tambah Siswa
                </h2>
                <p class="text-muted">Registrasi siswa baru</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.siswa.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIS <span class="text-danger">*</span></label>
                            <input type="text" name="nis" value="{{ old('nis') }}"
                                class="form-control @error('nis') is-invalid @enderror" required>
                            @error('nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_siswa" value="{{ old('nama_siswa') }}"
                                class="form-control @error('nama_siswa') is-invalid @enderror" required>
                            @error('nama_siswa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <select name="jurusan" class="form-control @error('jurusan') is-invalid @enderror" required>
                                <option value="">Pilih Jurusan</option>
                                @foreach ($jurusanList as $j)
                                    <option value="{{ $j }}" {{ old('jurusan') == $j ? 'selected' : '' }}>
                                        {{ $j }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jurusan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                                class="form-control @error('no_telepon') is-invalid @enderror">
                            @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan
                            </button>
                            <a href="{{ route('admin.siswa.index') }}" class="btn btn-danger">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
