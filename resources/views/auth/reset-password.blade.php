<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Tetap panggil Vite agar Tailwind & JS bawaan tetap jalan -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* RESET TOTAL UNTUK FULL SCREEN */
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #0f172a !important; /* Biru Tua Full Screen */
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .reset-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        
        /* Opsional: Jika Anda punya logo di atas form */
        .logo-container {
            margin-bottom: 1.5rem;
            color: white; 
        }
        
        .reset-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            padding: 2.5rem 2rem;
            box-sizing: border-box;
        }

        .reset-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
            text-align: center;
            margin-top: 0;
        }
        
        .reset-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 2rem;
            text-align: center;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            pointer-events: none;
        }

        /* Kunci ukuran SVG */
        .input-icon svg {
            width: 18px !important;
            height: 18px !important;
            min-width: 18px;
            min-height: 18px;
            max-width: 18px;
            max-height: 18px;
            display: block;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 0.875rem;
            background-color: #f8fafc; 
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #0f172a;
            transition: all 0.2s;
            outline: none;
            box-sizing: border-box;
        }

        .form-input:focus {
            background-color: #ffffff;
            border-color: #1e3a8a;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15); 
        }

        .form-input.pw-input {
            padding-right: 2.5rem;
        }

        .toggle-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            display: flex;
            align-items: center;
            padding: 0;
        }

        .toggle-btn:hover { color: #1e3a8a; }
        
        .toggle-btn svg {
            width: 18px !important;
            height: 18px !important;
            min-width: 18px;
            min-height: 18px;
            max-width: 18px;
            max-height: 18px;
        }

        .toggle-btn .eye-closed { display: none; }

        .error-text {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        .submit-btn {
            width: 100%;
            padding: 0.875rem;
            background-color: #1e3a8a; 
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            background-color: #172554; 
        }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            font-size: 0.875rem;
            color: #64748b;
            text-decoration: none;
            margin-top: 1.5rem;
            transition: color 0.2s;
        }

        .back-link:hover { color: #1e3a8a; }
        
        .back-link svg {
            width: 16px !important; 
            height: 16px !important; 
        }
    </style>
</head>
<body>
    <div class="reset-wrapper">
        
        <!-- Opsional: Tempatkan logo Anda di sini jika ada -->
        <div class="logo-container">
            <svg style="width: 48px; height: 48px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>

        <div class="reset-card">
            <h1 class="reset-title">Reset Password</h1>
            <p class="reset-subtitle">Silakan masukkan password baru Anda di bawah ini.</p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="nama@email.com" class="form-input" />
                    </div>
                    @error('email') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <!-- Password Baru -->
                <div class="form-group">
                    <label for="password" class="form-label">Password Baru</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="form-input pw-input" />
                        <button type="button" onclick="togglePassword('password', this)" class="toggle-btn">
                            <svg class="eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="form-input pw-input" />
                        <button type="button" onclick="togglePassword('password_confirmation', this)" class="toggle-btn">
                            <svg class="eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password_confirmation') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="submit-btn">
                    <svg style="width: 18px !important; height: 18px !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Password
                </button>
                
                <!-- Back -->
                <div style="text-align: center;">
                    <a href="{{ route('landing') }}" class="back-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke Layar Utama
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const open = btn.querySelector('.eye-open');
            const closed = btn.querySelector('.eye-closed');
            
            if (input.type === 'password') {
                input.type = 'text';
                open.style.display = 'none';
                closed.style.display = 'block';
            } else {
                input.type = 'password';
                open.style.display = 'block';
                closed.style.display = 'none';
            }
        }
    </script>
</body>
</html>