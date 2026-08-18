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
                <p>Masuk dengan NIS dan Password</p>
            </div>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login.siswa') }}">
                @csrf
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">

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
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Masukkan password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>

                <div class="text-center mt-2">
                    <a href="{{ route('landing') }}" class="text-decoration-none"
                        style="color: #4f46e5; font-weight: 500; font-size: 14px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Halaman Awal
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
