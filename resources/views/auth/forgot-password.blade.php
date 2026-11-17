<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Tetap panggil Vite agar Tailwind & JS bawaan tetap jalan -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-color: #1e3a8a; /* Biru Gelap Utama */
            --primary-hover: #172554; /* Biru Lebih Gelap */
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #cbd5e1;
            --bg-input: #f8fafc;
            --error-color: #ef4444;
        }

        /* RESET TOTAL UNTUK FULL SCREEN */
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #0f172a !important; /* Background Biru Tua Full Screen */
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .forgot-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .forgot-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            padding: 2.5rem 2rem;
            box-sizing: border-box;
            animation: fadeInUp 0.5s ease-out forwards;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Ikon Ilustrasi */
        .icon-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .icon-circle {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.1), rgba(30, 58, 138, 0.2));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 24px;
        }

        /* Teks Deskripsi */
        .description-text {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 24px;
            text-align: center;
        }

        /* Grup Form */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 8px;
            color: var(--text-main);
            letter-spacing: 0.3px;
        }

        /* Input Field Modern */
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--bg-input);
            font-size: 14px;
            color: var(--text-main);
            box-sizing: border-box;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--primary-color);
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        /* Pesan Error */
        .error-message {
            color: var(--error-color);
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Tombol Submit Modern */
        .btn-submit {
            width: 100%;
            background: var(--primary-color);
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* Tombol Kembali */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="forgot-wrapper">
        <div class="forgot-card">
            <!-- Ikon Ilustrasi -->
            <div class="icon-wrapper">
                <div class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
            </div>

            <!-- Teks Deskripsi -->
            <div class="description-text">
                {{ __('Lupa password Anda? Tidak masalah. Beritahu kami alamat email Anda dan kami akan mengirimkan link untuk mereset password Anda.') }}
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div style="margin-bottom: 1rem; font-size: 0.875rem; font-weight: 500; color: #10b981; text-align: center; background: #ecfdf5; padding: 10px; border-radius: 8px;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="nama@email.com"
                           class="form-input">
                    
                    @error('email')
                        <span class="error-message">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn-submit">
                    {{ __('Kirim Link Reset') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>

            <!-- Link Kembali ke Login -->
            <a href="{{ route('landing') }}" class="back-link">
                &larr; Kembali ke Utama
            </a>
        </div>
    </div>
</body>
</html>