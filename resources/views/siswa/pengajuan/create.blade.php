@extends('layouts.app')

@section('title', 'Form Pengajuan PKL')
@section('page-title', 'Pengajuan PKL')
@section('page-subtitle', 'Isi form pengajuan tempat PKL')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5>Form Pengajuan Tempat PKL</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.pengajuan.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" class="form-control" value="{{ $siswa->nis }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" value="{{ $siswa->nama_siswa }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" class="form-control" value="{{ $siswa->kelas }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <input type="text" name="jurusan" class="form-control @error('jurusan') is-invalid @enderror"
                                value="{{ old('jurusan', $siswa->jurusan) }}" placeholder="Masukkan jurusan">
                            @error('jurusan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilihan Tempat PKL 1 <span class="text-danger">*</span></label>
                            <select name="pilihan_1" class="form-select @error('pilihan_1') is-invalid @enderror">
                                <option value="">Pilih Tempat</option>
                                <option value="Padang" {{ old('pilihan_1') == 'Padang' ? 'selected' : '' }}>Padang</option>
                                <option value="Bandung" {{ old('pilihan_1') == 'Bandung' ? 'selected' : '' }}>Bandung
                                </option>
                                <option value="Yogyakarta" {{ old('pilihan_1') == 'Yogyakarta' ? 'selected' : '' }}>
                                    Yogyakarta</option>
                                <option value="Pekanbaru" {{ old('pilihan_1') == 'Pekanbaru' ? 'selected' : '' }}>Pekanbaru
                                </option>
                                <option value="Batam" {{ old('pilihan_1') == 'Batam' ? 'selected' : '' }}>Batam</option>
                                <option value="Jakarta" {{ old('pilihan_1') == 'Jakarta' ? 'selected' : '' }}>Jakarta
                                </option>
                                <option value="Lainnya" {{ old('pilihan_1') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                    (tulis di bawah)</option>
                            </select>
                            @error('pilihan_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilihan Tempat PKL 2 <span class="text-danger">*</span></label>
                            <select name="pilihan_2" class="form-select @error('pilihan_2') is-invalid @enderror">
                                <option value="">Pilih Tempat</option>
                                <option value="Padang" {{ old('pilihan_2') == 'Padang' ? 'selected' : '' }}>Padang</option>
                                <option value="Bandung" {{ old('pilihan_2') == 'Bandung' ? 'selected' : '' }}>Bandung
                                </option>
                                <option value="Yogyakarta" {{ old('pilihan_2') == 'Yogyakarta' ? 'selected' : '' }}>
                                    Yogyakarta</option>
                                <option value="Pekanbaru" {{ old('pilihan_2') == 'Pekanbaru' ? 'selected' : '' }}>Pekanbaru
                                </option>
                                <option value="Batam" {{ old('pilihan_2') == 'Batam' ? 'selected' : '' }}>Batam</option>
                                <option value="Jakarta" {{ old('pilihan_2') == 'Jakarta' ? 'selected' : '' }}>Jakarta
                                </option>
                                <option value="Lainnya" {{ old('pilihan_2') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                    (tulis di bawah)</option>
                            </select>
                            @error('pilihan_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penghasilan Orang Tua (per bulan)</label>
                            <input type="number" name="penghasilan_ortu"
                                class="form-control @error('penghasilan_ortu') is-invalid @enderror"
                                value="{{ old('penghasilan_ortu') }}" placeholder="Masukkan penghasilan orang tua">
                            @error('penghasilan_ortu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Jika memilih "Lainnya", tuliskan tempat yang diinginkan</label>
                            <input type="text" name="tempat_lain" class="form-control"
                                placeholder="Tulis tempat lainnya...">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                        </button>
                        <a href="{{ route('siswa.pengajuan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
