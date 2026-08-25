<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('login_mode', 'regular');

        return redirect()->intended(route('industri.dashboard'));
    }
}
