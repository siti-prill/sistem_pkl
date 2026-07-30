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

    <style>
        /* ============ GENERAL ============ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* ============ NAVBAR ============ */
        .navbar-custom {
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        .navbar-custom .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.4rem;
        }

        .navbar-custom .navbar-brand i {
            color: #4f46e5;
        }

        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .navbar-custom .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
        }

        .navbar-custom .btn-login-nav {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            padding: 8px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .navbar-custom .btn-login-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
            color: white;
        }

        /* Tombol Pengajuan di Navbar (warna hijau) */
        .btn-pengajuan-nav {
            background: linear-gradient(135deg, #7d13ef, #710fcd);
            color: white;
            border: none;
            padding: 8px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-pengajuan-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(34, 91, 197, 0.937);
            color: white;
        }

        /* ============ HERO SECTION ============ */
        .hero-section {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: rgba(79, 70, 229, 0.1);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: rgba(124, 58, 237, 0.08);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-content h1 {
            color: white;
            font-weight: 800;
            font-size: 3.5rem;
            line-height: 1.2;
        }

        .hero-content h1 span {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-content p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.2rem;
            max-width: 500px;
            margin: 20px 0 30px;
        }

        .hero-content .btn-hero {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .hero-content .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 35px rgba(79, 70, 229, 0.4);
            color: white;
        }

        .hero-content .btn-outline-hero {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 14px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin-left: 15px;
        }

        .hero-content .btn-outline-hero:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
            color: white;
        }

        /* Tombol Pengajuan di Hero (warna hijau terang) */
        .btn-pengajuan-hero {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin-left: 15px;
        }

        .btn-pengajuan-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 35px rgba(34, 197, 94, 0.4);
            color: white;
        }

        .hero-image {
            position: relative;
            z-index: 1;
        }

        .hero-image img {
            width: 100%;
            max-width: 550px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* ============ FEATURES ============ */
        .features-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .features-section h2 {
            font-weight: 700;
            color: #1a1a2e;
            font-size: 2.5rem;
        }

        .features-section .subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e5e7eb;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .feature-card .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
        }

        .feature-card h5 {
            font-weight: 700;
            color: #1a1a2e;
        }

        .feature-card p {
            color: #6c757d;
            font-size: 0.95rem;
        }

        /* ============ FOOTER ============ */
        .footer {
            background: #1a1a2e;
            color: rgba(255, 255, 255, 0.6);
            padding: 40px 0;
            text-align: center;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: white;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-image {
                margin-top: 40px;
            }

            .hero-image img {
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .hero-content .btn-hero,
            .hero-content .btn-outline-hero,
            .hero-content .btn-pengajuan-hero {
                display: block;
                width: 100%;
                margin: 10px 0;
            }

            .features-section h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <!-- ============ NAVBAR ============ -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                <i class="fas fa-school me-2"></i>Sistem PKL
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
                        <a class="nav-link" href="#about">Tentang</a>
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
                    <img src="https://placehold.co/550x400/4f46e5/white?text=Sistem+PKL" alt="Sistem PKL">
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
                    <div class="feature-card">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h5>Manajemen Siswa</h5>
                        <p>Kelola data siswa, penempatan PKL, dan jurnal harian dengan mudah.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-building"></i>
                        </div>
                        <h5>Mitra Industri</h5>
                        <p>Daftar perusahaan mitra, kuota PKL, dan contact person terintegrasi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5>Monitoring & Laporan</h5>
                        <p>Pantau aktivitas siswa dan cetak laporan jurnal & nilai dalam PDF.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h5>Bimbingan Online</h5>
                        <p>Guru dapat memberikan komentar dan umpan balik langsung pada jurnal siswa.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h5>Export PDF</h5>
                        <p>Cetak laporan jurnal dan nilai PKL dalam format PDF siap cetak.</p>
                    </div>
                </div>
                <!-- ===== FITUR BARU: PENGAJUAN PKL ===== -->
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary" style="color:#0d6efd;">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <h5>Pengajuan Tempat PKL</h5>
                        <p>Siswa dapat mengajukan pilihan tempat PKL yang akan
                            diverifikasi oleh admin.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-purple bg-opacity-10 text-purple" style="color:#7c3aed;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5>Aman & Terpercaya</h5>
                        <p>Sistem dengan autentikasi user dan role management yang aman.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FOOTER ============ -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="text-white fw-bold">Sistem PKL</h5>
                    <p>Sistem Manajemen Praktik Kerja Lapangan terintegrasi untuk sekolah, siswa, dan industri.</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="text-white fw-bold">Tautan</h5>
                    <a href="{{ route('login') }}" class="d-block">Login</a>
                    <a href="#features" class="d-block">Fitur</a>
                    <a href="#" class="d-block">Kebijakan Privasi</a>
                </div>
                <div class="col-md-4">
                    <h5 class="text-white fw-bold">Kontak</h5>
                    <p><i class="fas fa-envelope me-2"></i> support@sistem-pkl.com</p>
                    <p><i class="fas fa-phone me-2"></i> +62 812 3456 7890</p>
                    <div class="mt-2">
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-youtube fa-lg"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-github fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary">
            <p class="mb-0">&copy; {{ date('Y') }} Sistem PKL. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
