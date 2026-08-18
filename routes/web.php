<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\IndustriController;
use App\Http\Controllers\Admin\KompetensiController;
use App\Http\Controllers\Admin\PenempatanController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Guru\MonitoringController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\JurnalController;
use App\Http\Controllers\Siswa\PengajuanController as SiswaPengajuanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginSiswaController;

// Landing Page (Publik)
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Login Siswa (Publik - bisa diakses tanpa login)
Route::get('/login-siswa', [LoginSiswaController::class, 'showLoginForm'])->name('login.siswa.form');
Route::post('/login-siswa', [LoginSiswaController::class, 'login'])->name('login.siswa');

// Route Dashboard (Fallback) - tetap untuk redirect setelah login
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'guru') {
        return redirect()->route('guru.monitoring.index');
    } elseif ($user->role === 'siswa') {
        return redirect()->route('siswa.jurnal.index');
    }
    return redirect('/');
})->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // ==================== ADMIN ROUTES ====================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('guru', GuruController::class);
        Route::resource('siswa', SiswaController::class);
        Route::resource('kompetensi', KompetensiController::class);
        Route::resource('industri', IndustriController::class);
        Route::resource('penempatan', PenempatanController::class);

        // Route Pengajuan untuk Admin
        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{id}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show');
        Route::put('/pengajuan/{id}', [AdminPengajuanController::class, 'update'])->name('pengajuan.update');

        // Route Pengaturan (logo, dll)
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    });

    // ==================== SISWA ROUTES ====================
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', function () {
            if (session('login_mode') === 'pengajuan') {
                $siswa = auth()->user()->siswa;
                $pengajuan = \App\Models\PengajuanPkl::where('siswa_id', $siswa->id)->first();

                if (!$pengajuan) {
                    return redirect()->route('siswa.pengajuan.create');
                } elseif ($pengajuan->status !== 'diterima') {
                    return redirect()->route('siswa.pengajuan.index');
                }
            }

            return redirect()->route('siswa.jurnal.index');
        })->name('dashboard');

        // Route Pengajuan untuk Siswa
        Route::get('/pengajuan', [SiswaPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [SiswaPengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [SiswaPengajuanController::class, 'store'])->name('pengajuan.store');

        Route::resource('jurnal', JurnalController::class);
        Route::post('/jurnal/{jurnal}/submit', [JurnalController::class, 'submit'])->name('jurnal.submit');
    });

    // ==================== GURU ROUTES ====================
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', function () {
            return redirect()->route('guru.monitoring.index');
        })->name('dashboard');
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('/monitoring/{id}', [MonitoringController::class, 'show'])->name('monitoring.show');
        Route::post('/komentar/{jurnal_id}', [MonitoringController::class, 'storeKomentar'])->name('komentar.store');
        Route::resource('nilai', NilaiController::class);
    });

    // ==================== LAPORAN ROUTES ====================
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/jurnal', [LaporanController::class, 'jurnal'])->name('jurnal');
        Route::get('/nilai', [LaporanController::class, 'nilai'])->name('nilai');
        Route::get('/jurnal/pdf', [LaporanController::class, 'jurnalPdf'])->name('jurnal.pdf');
        Route::get('/nilai/pdf', [LaporanController::class, 'nilaiPdf'])->name('nilai.pdf');
    });

    // Profile (Breeze - bisa diakses semua role: admin, guru, siswa)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
}); // <-- Tutup middleware auth

require __DIR__ . '/auth.php';
