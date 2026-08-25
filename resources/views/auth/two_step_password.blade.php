<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Konfirmasi Password - Sistem PKL</title>

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
            margin-bottom: 25px;
        }

        .login-card .logo i {
            font-size: 40px;
            color: #4f46e5;
            background: rgba(79,70,229,0.1);
            padding: 20px;
            border-radius: 50%;
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

        .user-chip {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(79,70,229,0.08);
            border: 1px dashed rgba(79,70,229,0.4);
            border-radius: 14px;
            padding: 12px 16px;
            margin-bottom: 20px;
        }

        .user-chip .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
        }

        .user-chip .name {
            font-weight: 600;
            color: #1a1a2e;
            font-size: 15px;
        }

        .user-chip .email {
            color: #6c757d;
            font-size: 13px;
        }

        .step-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #4f46e5;
            background: rgba(79,70,229,0.12);
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 10px;
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

        .btn-cancel {
            background: transparent;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 10px;
            font-weight: 600;
            width: 100%;
            color: #6c757d;
        }

        .btn-cancel:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #b91c1c;
        }

        .alert {
            border-radius: 12px;
            border: none;
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

        body.dark-mode .login-card {
            background: rgba(31,41,55,0.95);
        }

        body.dark-mode .login-card .logo h3,
        body.dark-mode .user-chip .name {
            color: #f9fafb;
        }

        body.dark-mode .login-card .logo p,
        body.dark-mode .user-chip .email {
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

        body.dark-mode .input-group-text {
            background: #374151;
            border-color: #4b5563;
            color: #9ca3af;
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
                <i class="fas fa-shield-halved"></i>
                <h3>Verifikasi 2 Langkah</h3>
                <p>Masukkan ulang password untuk konfirmasi</p>
            </div>

            <div class="text-center">
                <span class="step-badge">
                    <i class="fas fa-circle-check"></i> Langkah 1 berhasil &mdash; Langkah 2 dari 2
                </span>
            </div>

            <div class="user-chip">
                <div class="avatar">{{ strtoupper(substr($userName, 0, 1)) }}</div>
                <div>
                    <div class="name">{{ $userName }}</div>
                    <div class="email">{{ $maskedEmail }}</div>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.twostep.confirm') }}" autocomplete="off">
                @csrf

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-1"></i> Konfirmasi Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Masukkan password yang sama"
                               required
                               autofocus
                               autocomplete="new-password">
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-login mb-2">
                    <i class="fas fa-unlock me-2"></i> Konfirmasi &amp; Masuk
                </button>
            </form>

            <form method="POST" action="{{ route('password.twostep.cancel') }}">
                @csrf
                <button type="submit" class="btn-cancel mt-2">
                    <i class="fas fa-arrow-left me-2"></i> Batal &amp; Login Ulang
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
