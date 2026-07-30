@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
    <div class="animate-fadeIn">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline-secondary me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold text-dark mb-0">
                    <i class="fas fa-edit text-warning me-2"></i> Edit Siswa
                </h2>
                <p class="text-muted">Perbarui data siswa</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIS <span class="text-danger">*</span></label>
                            <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}"
                                class="form-control @error('nis') is-invalid @enderror" required>
                            @error('nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}"
                                class="form-control @error('nama_siswa') is-invalid @enderror" required>
                            @error('nama_siswa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $siswa->user->email) }}"
                                class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span class="text-muted">(Kosongkan jika tidak
                                    diubah)</span></label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas" class="form-control @error('kelas') is-invalid @enderror" required>
                                <option value="">Pilih Kelas</option>
                                <option value="X" {{ old('kelas', $siswa->kelas) == 'X' ? 'selected' : '' }}>X</option>
                                <option value="XI" {{ old('kelas', $siswa->kelas) == 'XI' ? 'selected' : '' }}>XI
                                </option>
                                <option value="XII" {{ old('kelas', $siswa->kelas) == 'XII' ? 'selected' : '' }}>XII
                                </option>
                            </select>
                            @error('kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <select name="jurusan" class="form-control @error('jurusan') is-invalid @enderror" required>
                                <option value="">Pilih Jurusan</option>
                                <option value="RPL" {{ old('jurusan', $siswa->jurusan) == 'RPL' ? 'selected' : '' }}>RPL
                                </option>
                                <option value="TKJ" {{ old('jurusan', $siswa->jurusan) == 'TKJ' ? 'selected' : '' }}>TKJ
                                </option>
                                <option value="MM" {{ old('jurusan', $siswa->jurusan) == 'MM' ? 'selected' : '' }}>MM
                                </option>
                                <option value="AKL" {{ old('jurusan', $siswa->jurusan) == 'AKL' ? 'selected' : '' }}>AKL
                                </option>
                                <option value="OTKP" {{ old('jurusan', $siswa->jurusan) == 'OTKP' ? 'selected' : '' }}>
                                    OTKP</option>
                            </select>
                            @error('jurusan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" name="no_telepon" value="{{ old('no_telepon', $siswa->no_telepon) }}"
                                class="form-control @error('no_telepon') is-invalid @enderror">
                            @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $siswa->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update
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
