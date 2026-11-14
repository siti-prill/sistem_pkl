<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // authenticate() juga menjalankan ensureIsNotRateLimited()
        // (maks 5 percobaan sebelum lockout) — Auth::attempt() saja
        // melewati proteksi brute-force.
        $request->authenticate();

        $request->session()->regenerate();

        // Siswa menggunakan akses terpadu (pengajuan + jurnal); role lain mode reguler
        if (auth()->user()->role === 'siswa') {
            $request->session()->forget('login_mode');
        } else {
            $request->session()->put('login_mode', 'regular');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
