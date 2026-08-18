<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem PKL - Login')</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 15px;
        }

        .login-card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .login-card .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-card .logo i {
            font-size: 48px;
            color: #4f46e5;
            background: rgba(79,70,229,0.1);
            padding: 20px;
            border-radius: 50%;
        }

        .login-card .logo img {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            object-fit: contain;
            background: rgba(79,70,229,0.1);
            padding: 16px;
            box-shadow: 0 4px 15px rgba(79,70,229,0.25);
        }

        .login-card .logo h3 {
            margin-top: 15px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .login-card .logo p {
            color: #6c757d;
            font-size: 14px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79,70,229,0.15);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 4px rgba(220,53,69,0.15);
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .btn-login {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79,70,229,0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .form-check-input:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .form-check-label {
            color: #6c757d;
            font-size: 14px;
        }

        .forgot-link {
            color: #4f46e5;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #7c3aed;
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            border: none;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            background: #fecaca;
            color: #991b1b;
        }

        .input-group-text {
            background: transparent;
            border: 2px solid #e5e7eb;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #6c757d;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }

        .input-group .form-control:focus {
            border-color: #4f46e5;
        }

        .input-group:focus-within .input-group-text {
            border-color: #4f46e5;
            color: #4f46e5;
        }

        body.dark-mode .login-card {
            background: rgba(31,41,55,0.95);
        }

        body.dark-mode .login-card .logo h3 {
            color: #f9fafb;
        }

        body.dark-mode .login-card .logo p {
            color: #9ca3af;
        }

        body.dark-mode .form-label {
            color: #e5e7eb;
        }

        body.dark-mode .form-control {
            background: #374151;
            border-color: #4b5563;
            color: #f9fafb;
        }

        body.dark-mode .form-control:focus {
            border-color: #6366f1;
            background: #374151;
            color: #f9fafb;
        }

        body.dark-mode .input-group-text {
            background: #374151;
            border-color: #4b5563;
            color: #9ca3af;
        }

        body.dark-mode .form-check-label {
            color: #9ca3af;
        }

        body.dark-mode .alert-success {
            background: #065f46;
            color: #a7f3d0;
        }

        body.dark-mode .alert-danger {
            background: #7f1d1d;
            color: #fca5a5;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <img src="{{ logo_url() }}" alt="Logo SMK 2">
                <h3>Sistem PKL</h3>
                <p>Praktik Kerja Lapangan</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-1"></i> Email
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Masukkan email Anda"
                               required 
                               autofocus 
                               autocomplete="username">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-1"></i> Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan password Anda"
                               required 
                               autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                        <label class="form-check-label" for="remember_me">
                            <i class="fas fa-check-circle me-1"></i> Ingat Saya
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            <i class="fas fa-key me-1"></i> Lupa Password?
                        </a>
                    @endif
                </div>

                <!-- ===== TOMBOL LOGIN ===== -->
                <button type="submit" class="btn-login mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>

                <!-- Tombol Kembali ke Landing Page -->
                <div class="text-center mt-2">
                    <a href="{{ route('landing') }}" class="text-decoration-none" style="color: #4f46e5; font-weight: 500; font-size: 14px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Halaman Awal
                    </a>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>