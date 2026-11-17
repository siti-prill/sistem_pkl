<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Siswa - Sistem PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .login-card .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-card .logo i {
            font-size: 48px;
            color: #4f46e5;
            background: rgba(79, 70, 229, 0.1);
            padding: 20px;
            border-radius: 50%;
        }

        .login-card .logo img {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            object-fit: contain;
            background: rgba(79, 70, 229, 0.1);
            padding: 16px;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.25);
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
        }

        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
            z-index: 1;
        }

        /* Menyesuaikan border input group agar menyatu dengan rapi */
        .input-group .form-control {
            border-right: none;
        }
        
        .input-group .btn-toggle-password {
            border: 2px solid #e5e7eb;
            border-left: none;
            border-radius: 0 12px 12px 0;
            color: #6c757d;
            background-color: #fff;
            padding: 0 16px;
            transition: all 0.3s ease;
        }

        .input-group .btn-toggle-password:hover {
            color: #4f46e5;
            background-color: #f8f9fa;
        }

        /* Mengatasi fokus outline pada tombol mata */
        .input-group .btn-toggle-password:focus {
            box-shadow: none;
            border-color: #e5e7eb;
        }

        .btn-login {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
            color: white;
        }

        .back-link {
            color: #4f46e5;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
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
                <h3>Login Siswa</h3>
                <p>Pengajuan & Jurnal PKL dalam satu akun. Masuk dengan NIS dan Password.</p>
            </div>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login.siswa') }}">
                @csrf
                <input type="hidden" name="redirect" value="{{ is_array(request('redirect')) ? '' : request('redirect') }}">

                <div class="mb-3">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror"
                        value="{{ old('nis') }}" placeholder="Masukkan NIS" required autofocus>
                    @error('nis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <!-- Menggunakan Input Group untuk Tombol Mata -->
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan password" required>
                        <button class="btn btn-toggle-password" type="button" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                        @error('password')
                            <!-- Pesan error diletakkan di luar input-group agar tidak merusak desain -->
                        @enderror
                    </div>
                    @error('password')
                        <div class="text-danger mt-1" style="font-size: 0.875em;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            <i class="fas fa-key me-1"></i> Lupa Password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-login mt-2">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('landing') }}" class="text-decoration-none"
                        style="color: #4f46e5; font-weight: 500; font-size: 14px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Halaman Awal
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script Toggle Password -->
    <script>
        function togglePassword() {
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
        }
    </script>
</body>

</html>