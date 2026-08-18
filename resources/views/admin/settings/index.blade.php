@extends('layouts.app')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Kelola logo dan konten footer aplikasi')

@section('content')
    <div class="container">
        <ul class="nav nav-pills mb-4 gap-2" id="settingsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="logo-tab" data-bs-toggle="pill" data-bs-target="#logo-pane" type="button"
                    role="tab">
                    <i class="fas fa-image me-1"></i> Logo Aplikasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="footer-tab" data-bs-toggle="pill" data-bs-target="#footer-pane" type="button"
                    role="tab">
                    <i class="fas fa-align-left me-1"></i> Footer Landing Page
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- ============ TAB LOGO ============ -->
            <div class="tab-pane fade show active" id="logo-pane" role="tabpanel" aria-labelledby="logo-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-image me-2 text-primary"></i> Logo Aplikasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Logo Aplikasi</label>
                                    <div class="mb-3">
                                        <img src="{{ logo_url() }}" alt="Logo Aplikasi"
                                            style="max-width: 180px; max-height: 180px; border: 1px solid #dee2e6; border-radius: 8px; padding: 8px; background: #fff;">
                                    </div>
                                    <input type="file" name="logo" id="logoInput" accept="image/*"
                                        class="form-control @error('logo') is-invalid @enderror"
                                        onchange="previewLogo(event)">
                                    <div class="form-text">Format: JPG, PNG, atau WEBP. Maksimal 2 MB.</div>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Logo
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ============ TAB FOOTER ============ -->
            <div class="tab-pane fade" id="footer-pane" role="tabpanel" aria-labelledby="footer-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-align-left me-2 text-primary"></i> Konten Footer Landing Page
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light border d-flex align-items-center gap-2 mb-4">
                            <i class="fas fa-info-circle text-primary"></i>
                            <span>Konten berikut akan tampil pada bagian footer halaman landing (halaman utama).</span>
                        </div>
                        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="footer_deskripsi" rows="3" class="form-control"
                                        placeholder="Deskripsi singkat tentang sekolah / sistem PKL">{{ $settings['footer_deskripsi'] }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" name="footer_alamat" value="{{ $settings['footer_alamat'] }}"
                                        placeholder="Jl. Contoh No. 1, Kota" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Telepon / WhatsApp</label>
                                    <input type="text" name="footer_telepon" value="{{ $settings['footer_telepon'] }}"
                                        placeholder="+62 812 3456 7890" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="footer_email" value="{{ $settings['footer_email'] }}"
                                        placeholder="support@sekolah.sch.id" class="form-control">
                                </div>
                                <div class="col-12">
                                    <hr class="my-2">
                                    <label class="form-label fw-semibold">
                                        <i class="fab fa-instagram me-1 text-danger"></i> Link Media Sosial
                                    </label>
                                    <div class="form-text mb-3">Kosongkan link yang tidak dipakai, tautan tidak akan
                                        ditampilkan.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Instagram</label>
                                    <input type="url" name="footer_instagram"
                                        value="{{ $settings['footer_instagram'] }}"
                                        placeholder="https://instagram.com/..." class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">YouTube</label>
                                    <input type="url" name="footer_youtube" value="{{ $settings['footer_youtube'] }}"
                                        placeholder="https://youtube.com/..." class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">GitHub</label>
                                    <input type="url" name="footer_github" value="{{ $settings['footer_github'] }}"
                                        placeholder="https://github.com/..." class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Simpan Footer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            if (!file) return;
            const img = document.querySelector('#logo-pane form img');
            if (img) {
                img.src = URL.createObjectURL(file);
            }
        }
    </script>
@endpush
