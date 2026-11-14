<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginSiswaController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login_siswa');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nis' => 'required|string',
            'password' => 'required|string',
        ]);

        // Batasi percobaan login per IP+identitas (anti brute-force)
        $throttleKey = 'login-siswa:' . md5(strtolower($request->nis) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'nis' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $siswa = \App\Models\Siswa::where('nis', $request->nis)->first();
        $user = $siswa?->user;

        // Pesan error diseragamkan agar penyerang tidak bisa
        // membedakan NIS valid / tidak (anti user enumeration)
        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'nis' => 'NIS atau password salah.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::login($user);
        $request->session()->regenerate();
        // Mode terpadu: siswa dapat mengakses pengajuan dan jurnal dalam satu akun
        $request->session()->forget('login_mode');

        $redirect = $request->input('redirect');
        $redirectPath = is_string($redirect) ? parse_url($redirect, PHP_URL_PATH) : null;

        return ($redirectPath && str_starts_with($redirectPath, '/siswa/'))
            ? redirect()->to($redirect)
            : redirect()->route('siswa.dashboard');
    }
}
