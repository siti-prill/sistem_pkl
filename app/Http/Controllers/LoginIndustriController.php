<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\TwoStepLoginController;

class LoginIndustriController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login_industri');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $request->email)
            ->where('role', 'industri')
            ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Akun industri tidak ditemukan.']);
        }

        // Cek password (tahap 1) -> simpan sesi pending, lanjut konfirmasi
        if (Hash::check($request->password, $user->password)) {
            TwoStepLoginController::pend($user, [
                'mode' => 'industri',
            ]);

            return redirect()->route('password.twostep.form');
        }

        return back()->withErrors(['password' => 'Password salah.']);
    }
}
