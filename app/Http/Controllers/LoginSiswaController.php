<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;

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

        // Cari siswa berdasarkan NIS
        $siswa = Siswa::where('nis', $request->nis)->first();

        if (!$siswa) {
            return back()->withErrors(['nis' => 'NIS tidak ditemukan.']);
        }

        // Coba login menggunakan user terkait
        $user = $siswa->user;

        if (!$user) {
            return back()->withErrors(['nis' => 'Akun tidak ditemukan.']);
        }

        // Cek password
        if (Auth::attempt(['email' => $user->email, 'password' => $request->password])) {
            // Login sukses
            $request->session()->regenerate();
            $request->session()->put('login_mode', 'pengajuan');

            // Redirect ke halaman yang diminta atau ke pengajuan
            $redirect = $request->input('redirect') ?? route('siswa.pengajuan.index');
            return redirect()->intended($redirect);
        }

        return back()->withErrors(['password' => 'Password salah.']);
    }
}
