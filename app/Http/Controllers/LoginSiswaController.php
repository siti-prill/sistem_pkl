<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $siswa = \App\Models\Siswa::where('nis', $request->nis)->first();

        if (!$siswa) {
            return back()->withErrors(['nis' => 'NIS tidak ditemukan.']);
        }

        $user = $siswa->user;

        if (!$user) {
            return back()->withErrors(['nis' => 'Akun tidak ditemukan.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('login_mode', 'pengajuan');

        return redirect()->intended(route('siswa.pengajuan.index'));
    }
}
