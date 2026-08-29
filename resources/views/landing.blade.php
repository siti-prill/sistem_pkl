<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem PKL - Manajemen Praktik Kerja Lapangan</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing/style.css') }}">
</head>

<body>
    <!-- ============ POPUP FULL LAYAR ============ -->
    <div class="popup-overlay" id="popupIklan">
        <div class="popup-box">
            <!-- Tombol Close -->
            <button class="popup-close" id="popupClose">
                <i class="fas fa-times"></i>
            </button>

            <!-- GAMBAR FULL -->
            <img src="{{ asset('images/kepala-sekolah.jpeg') }}" alt="Kepala SMKN 2 Padang" id="popupImage">

            <!-- COUNTDOWN DI BAWAH (OVERLAY) -->
            <div class="popup-countdown" id="popupCountdown">
                <i class="fas fa-clock me-2"></i>
                Tunggu <strong id="countdownNumber">5</strong> detik
            </div>
        </div>
    </div>

    <!-- ============ NAVBAR ============ -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                <img src="{{ logo_url() }}" alt="Logo SMK 2">
                Sistem PKL
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#tentangModal">
                            Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login.siswa.form', ['redirect' => route('siswa.pengajuan.index')]) }}"
                            class="btn btn-pengajuan-nav">
                            <i class="fas fa-file-alt me-2"></i>Pengajuan PKL
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login.industri.form') }}" class="btn btn-login-nav"
                            style="background: linear-gradient(135deg, #0d9488, #0891b2);">
                            <i class="fas fa-building me-2"></i>Industri
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login') }}" class="btn btn-login-nav">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ============ HERO SECTION ============ -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1>
                        Kelola PKL <br>
                        Lebih <span>Mudah & Terintegrasi</span>
                    </h1>
                    <p>
                        Sistem manajemen Praktik Kerja Lapangan yang menghubungkan
                        sekolah, siswa, dan perusahaan dalam satu platform.
                    </p>
                    <div>
                        <a href="{{ route('login') }}" class="btn btn-hero">
                            <i class="fas fa-rocket me-2"></i>Mulai Sekarang
                        </a>
                        <a href="#features" class="btn btn-outline-hero">
                            <i class="fas fa-info-circle me-2"></i>Pelajari
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 hero-image text-center">
                    <div class="hero-text-box"
                        style="
                                background: #4f46e5;
                                border-radius: 12px;
                                padding: 60px 30px;
                                min-height: 400px;
                                display: flex;
                                flex-direction: column;
                                justify-content: center;
                                align-items: center;
                                color: white;
                                width: 100%; ">
                            <h1 style="
                                font-size: 72px;
                                font-weight: 700;
                                letter-spacing: 4px;
                                margin: 0;
                                line-height: 1.1; "> LENTERA</h1>
                        <p style="
                                font-size: 18px;
                                font-weight: 300;
                                margin-top: 16px;
                                max-width: 80%;
                                letter-spacing: 0.5px;
                                line-height: 1.6;
                                opacity: 0.95; ">
                            Layanan Ekosistem Navigasi Terpadu dan Evaluasi Relasi Aktivitas PKL</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FEATURES ============ -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Fitur Unggulan</h2>
                <p class="subtitle">Semua yang Anda butuhkan untuk mengelola PKL dengan efisien</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card" role="button" data-bs-toggle="modal" data-bs-target="#featureModal"
                        data-feature="siswa">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h5>Manajemen Siswa</h5>
                        <p>Kelola data siswa, penempatan PKL, dan jurnal harian dengan mudah.</p>
                        <span class="detail-link"><i class="fas fa-arrow-right me-1"></i>Selengkapnya</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card" role="button" data-bs-toggle="modal" data-bs-target="#featureModal"
                        data-feature="industri">
                        <div class="feature-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-building"></i>
                        </div>
                        <h5>Mitra Industri</h5>
                        <p>Daftar perusahaan mitra, kuota PKL, dan contact person terintegrasi.</p>
                        <span class="detail-link"><i class="fas fa-arrow-right me-1"></i>Selengkapnya</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card" role="button" data-bs-toggle="modal" data-bs-target="#featureModal"
                        data-feature="monitoring">
                        <div class="feature-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5>Monitoring & Laporan</h5>
                        <p>Pantau aktivitas siswa dan cetak laporan jurnal & nilai dalam PDF.</p>
                        <span class="detail-link"><i class="fas fa-arrow-right me-1"></i>Selengkapnya</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card" role="button" data-bs-toggle="modal" data-bs-target="#featureModal"
                        data-feature="bimbingan">
                        <div class="feature-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h5>Bimbingan Online</h5>
                        <p>Guru dapat memberikan komentar dan umpan balik langsung pada jurnal siswa.</p>
                        <span class="detail-link"><i class="fas fa-arrow-right me-1"></i>Selengkapnya</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card" role="button" data-bs-toggle="modal" data-bs-target="#featureModal"
                        data-feature="export">
                        <div class="feature-icon bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h5>Export PDF</h5>
                        <p>Cetak laporan jurnal dan nilai PKL dalam format PDF siap cetak.</p>
                        <span class="detail-link"><i class="fas fa-arrow-right me-1"></i>Selengkapnya</span>
                    </div>
                </div>
                <!-- ===== FITUR BARU: PENGAJUAN PKL ===== -->
                <div class="col-md-4">
                    <div class="feature-card" role="button" data-bs-toggle="modal" data-bs-target="#featureModal"
                        data-feature="pengajuan">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary" style="color:#0d6efd;">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <h5>Pengajuan Tempat PKL</h5>
                        <p>Siswa dapat mengajukan pilihan tempat PKL yang akan
                            diverifikasi oleh admin.</p>
                        <span class="detail-link"><i class="fas fa-arrow-right me-1"></i>Selengkapnya</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card" role="button" data-bs-toggle="modal" data-bs-target="#featureModal"
                        data-feature="aman">
                        <div class="feature-icon bg-purple bg-opacity-10 text-purple" style="color:#7c3aed;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5>Aman & Terpercaya</h5>
                        <p>Sistem dengan autentikasi user dan role management yang aman.</p>
                        <span class="detail-link"><i class="fas fa-arrow-right me-1"></i>Selengkapnya</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card" role="button" data-bs-toggle="modal" data-bs-target="#featureModal"
                        data-feature="penilaian_industri">
                        <div class="feature-icon bg-success bg-opacity-10 text-success" style="color:#0d9488;">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h5>Penilaian Industri</h5>
                        <p>Industri dapat langsung menilai siswa sesuai template dari sekolah secara online.</p>
                        <span class="detail-link"><i class="fas fa-arrow-right me-1"></i>Selengkapnya</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FOOTER ============ -->
    <footer class="footer" id="contact">
        <div class="container position-relative">
            <div class="row text-start">
                <div class="col-md-3 mb-4 mb-md-0 pe-lg-4">
                    <h5 class="text-white fw-bold footer-brand d-flex align-items-center gap-2">
                        <img src="{{ logo_url() }}" alt="Logo">
                        <span>{{ setting('footer_nama', 'Sistem PKL') }}</span>
                    </h5>
                    <p class="mb-4">
                        {{ setting('footer_deskripsi', 'Sistem Manajemen Praktik Kerja Lapangan terintegrasi untuk sekolah, siswa, dan industri.') }}
                    </p>
                    @php $hasSocial = setting('footer_instagram') || setting('footer_youtube') || setting('footer_github'); @endphp
                    @if ($hasSocial)
                        <div class="d-flex gap-2">
                            @if (setting('footer_instagram'))
                                <a href="{{ setting('footer_instagram') }}" target="_blank" class="social-link"
                                    title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                            @if (setting('footer_youtube'))
                                <a href="{{ setting('footer_youtube') }}" target="_blank" class="social-link"
                                    title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            @endif
                            @if (setting('footer_github'))
                                <a href="{{ setting('footer_github') }}" target="_blank" class="social-link"
                                    title="GitHub">
                                    <i class="fab fa-github"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="text-white fw-bold">Tautan</h5>
                    <div class="footer-links">
                        <a href="{{ route('login') }}"><i class="fas fa-chevron-right me-2"
                                style="font-size:10px;"></i>Login</a>
                        <a href="#features"><i class="fas fa-chevron-right me-2"
                                style="font-size:10px;"></i>Fitur</a>
                        <a href="#"><i class="fas fa-chevron-right me-2" style="font-size:10px;"></i>Kebijakan
                            Privasi</a>
                    </div>
                </div>
                <div class="col-md-3 footer-contact">
                    <h5 class="text-white fw-bold">Kontak</h5>
                    @if (setting('footer_alamat'))
                        <p><i class="fas fa-map-marker-alt me-2"></i> {{ setting('footer_alamat') }}</p>
                    @endif
                    <p><i class="fas fa-envelope me-2"></i> {{ setting('footer_email', 'support@sistem-pkl.com') }}
                    </p>
                    <p><i class="fas fa-phone me-2"></i> {{ setting('footer_telepon', '+62 812 3456 7890') }}</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="text-white fw-bold footer-brand d-flex align-items-center gap-2">
                        <img src="{{ asset('images/barsilya.jpeg') }}" alt="Barsilya"
                            style="width: 34px; height: 34px; border-radius: 8px; object-fit: cover; background: #fff; padding: 3px;">
                        <span>Lentera</span>
                    </h5>
                    <p class="mb-1"><a href="https://github.com/gitdusk-dev" target="_blank"
                            style="color: rgba(255,255,255,0.6); text-decoration: none;"><i
                                class="fab fa-github me-2"></i>gitdusk-dev</a></p>
                    <p class="mb-0"><a href="mailto:rasyaprilyy@gmail.com"
                            style="color: rgba(255,255,255,0.6); text-decoration: none;"><i
                                class="fas fa-envelope me-2"></i>rasyaprilyy@gmail.com</a></p>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p class="mb-0">
                    @if (setting('footer_copyright'))
                        {{ setting('footer_copyright') }}
                    @else
                        &copy; {{ date('Y') }} {{ setting('footer_nama', 'Sistem PKL') }}. All rights reserved.
                    @endif
                </p>
            </div>
        </div>
    </footer>

    <!-- ============ MODAL DETAIL FITUR ============ -->
    <div class="modal fade" id="featureModal" tabindex="-1" aria-labelledby="featureModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div id="featureModalIcon" class="feature-icon"
                            style="width:60px; height:60px; border-radius:14px; font-size:26px; display:flex; align-items:center; justify-content:center; margin:0;">
                        </div>
                        <h4 class="mb-0 fw-bold" id="featureModalTitle" style="color:#1a1a2e;"></h4>
                    </div>
                    <p class="text-muted mb-4" id="featureModalDesc" style="font-size:1.05rem;"></p>
                    <div class="bg-light p-4 rounded-4">
                        <h6 class="fw-bold mb-3" style="color:#4f46e5;">
                            <i class="fas fa-check-circle me-2"></i>Keunggulan Fitur
                        </h6>
                        <ul class="mb-0" id="featureModalPoints" style="list-style: none; padding: 0;">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MODAL TENTANG ============ -->
    <div class="modal fade" id="tentangModal" tabindex="-1" aria-labelledby="tentangModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <div class="text-center mb-4">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto"
                            style="width:70px; height:70px; border-radius:18px; font-size:30px; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h4 class="fw-bold mt-3" style="color:#1a1a2e;">Tentang Sistem PKL</h4>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 h-100">
                                <h6 class="fw-bold text-primary">
                                    <i class="fas fa-graduation-cap me-2"></i>Apa itu Sistem PKL?
                                </h6>
                                <p class="text-muted mb-0" style="font-size:0.95rem;">
                                    Sistem PKL adalah sebuah
                                    Sistem Informasi Manajemen berbasis web yang dirancang khusus untuk mengelola
                                    seluruh proses magang atau PKL di sekolah secara terintegrasi, digital, dan efisien.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 h-100">
                                <h6 class="fw-bold text-success">
                                    <i class="fas fa-bullseye me-2"></i>Tujuan
                                </h6>
                                <p class="text-muted mb-0" style="font-size:0.95rem;">
                                    Memudahkan proses administrasi PKL, monitoring jurnal,
                                    penilaian, dan pelaporan secara digital untuk semua
                                    pihak yang terlibat.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 h-100">
                                <h6 class="fw-bold text-warning">
                                    <i class="fas fa-users me-2"></i>Pengguna
                                </h6>
                                <ul class="text-muted mb-0" style="font-size:0.95rem; padding-left:20px;">
                                    <li><strong>Admin</strong> - Mengelola seluruh data</li>
                                    <li><strong>Guru</strong> - Membimbing dan menilai siswa</li>
                                    <li><strong>Siswa</strong> - Mengajukan PKL dan membuat jurnal</li>
                                    <li><strong>Industri</strong> - Menilai siswa PKL langsung dari web</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 h-100">
                                <h6 class="fw-bold text-danger">
                                    <i class="fas fa-heart me-2"></i>Dibuat Oleh Kelompok 5
                                </h6>
                                <ul class="text-muted mb-0" style="font-size:0.95rem; padding-left:20px;">
                                    <li><strong>Siti</strong> - Backend Developer</li>
                                    <li><strong>Nazhwa Humayra</strong> - UI/UX Designer</li>
                                    <li><strong>Aldo Diali Putra</strong> - Frontend Developer</li>
                                    <li><strong>Muhammad Tahmid</strong> - UI/UX Designer</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted small mb-0">
                            <i class="fas fa-code me-1"></i>
                            Versi {{ setting('app_version', '1.0.0') }} |
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ date('Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/landing/script.js') }}"></script>
</body>

</html>
