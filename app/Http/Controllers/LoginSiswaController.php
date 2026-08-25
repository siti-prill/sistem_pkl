<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\TwoStepLoginController;
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

        // Cek password (tahap 1) -> simpan sesi pending, lanjut konfirmasi
        if (Hash::check($request->password, $user->password)) {
            TwoStepLoginController::pend($user, [
                'mode' => 'pengajuan',
            ]);

            return redirect()->route('password.twostep.form');
        }

        return back()->withErrors(['password' => 'Password salah.']);
    }
}
