<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\TwoStepLoginController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * Tahap 1 dari verifikasi 2 langkah: validasi kredensial,
     * lalu arahkan ke halaman konfirmasi password.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = $request->findUserForTwoStep();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        TwoStepLoginController::pend($user, [
            'remember' => $request->boolean('remember'),
            'mode' => 'regular',
        ]);

        return redirect()->route('password.twostep.form');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
