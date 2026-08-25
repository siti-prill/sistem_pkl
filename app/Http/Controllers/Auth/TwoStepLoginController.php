<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TwoStepLoginController extends Controller
{
    /**
     * Key session untuk data login yang menunggu konfirmasi password.
     */
    public const SESSION_KEY = 'password_pending';

    /**
     * Simpan data user yang berhasil tahap 1 ke session.
     */
    public static function pend(User $user, array $meta = []): void
    {
        session([
            self::SESSION_KEY => array_merge([
                'id' => $user->id,
                'expires' => now()->addMinutes(5)->timestamp,
                'attempts' => 0,
            ], $meta),
        ]);
    }

    /**
     * Tampilkan halaman konfirmasi password (langkah 2).
     */
    public function show()
    {
        $pending = session(self::SESSION_KEY);

        if (!$pending || !isset($pending['id']) || now()->timestamp > ($pending['expires'] ?? 0)) {
            session()->forget(self::SESSION_KEY);
            return redirect()->route('landing')
                ->with('error', 'Sesi konfirmasi berakhir. Silakan login ulang.');
        }

        $user = User::find($pending['id']);
        if (!$user) {
            session()->forget(self::SESSION_KEY);
            return redirect()->route('landing')
                ->with('error', 'Akun tidak ditemukan. Silakan login ulang.');
        }

        // Identitas ditampilkan sebagian agar tidak terekspos penuh
        $email = $user->email;
        [$name, $domain] = str_contains($email, '@') ? explode('@', $email, 2) : [$email, null];
        $maskedEmail = substr($name, 0, 3) . '*****' . ($domain ? '@' . $domain : '');

        return view('auth.two_step_password', [
            'maskedEmail' => $maskedEmail,
            'userName' => $user->name,
        ]);
    }

    /**
     * Proses konfirmasi password (langkah 2), lalu login penuh.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ], [
            'password.required' => 'Password konfirmasi wajib diisi.',
        ]);

        $pending = session(self::SESSION_KEY);

        if (!$pending || !isset($pending['id']) || now()->timestamp > ($pending['expires'] ?? 0)) {
            session()->forget(self::SESSION_KEY);
            return redirect()->route('landing')
                ->with('error', 'Sesi konfirmasi berakhir. Silakan login ulang.');
        }

        if (($pending['attempts'] ?? 0) >= 3) {
            session()->forget(self::SESSION_KEY);
            return redirect()->route('landing')
                ->with('error', 'Terlalu banyak percobaan gagal. Silakan login ulang.');
        }

        $user = User::find($pending['id']);

        if (!$user || !Hash::check($request->password, $user->password)) {
            $pending['attempts'] = ($pending['attempts'] ?? 0) + 1;
            session([self::SESSION_KEY => $pending]);

            return back()->withErrors([
                'password' => 'Password konfirmasi salah. Sisa percobaan: ' . max(0, 3 - $pending['attempts']) . 'x',
            ]);
        }

        // Konfirmasi benar -> selesaikan login
        $remember = (bool) ($pending['remember'] ?? false);
        $loginMode = $pending['mode'] ?? 'regular';

        Auth::login($user, $remember);
        $request->session()->regenerate();
        session()->forget(self::SESSION_KEY);

        if ($loginMode === 'pengajuan') {
            $request->session()->put('login_mode', 'pengajuan');
            return redirect()->intended(route('siswa.pengajuan.index'));
        }

        if ($loginMode === 'industri') {
            $request->session()->put('login_mode', 'regular');
            return redirect()->intended(route('industri.dashboard'));
        }

        $request->session()->put('login_mode', 'regular');
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Batalkan proses login 2 langkah.
     */
    public function cancel(Request $request)
    {
        session()->forget(self::SESSION_KEY);
        return redirect()->route('landing');
    }
}
