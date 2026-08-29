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
use App\Http\Controllers\LoginIndustriController;

// Landing Page (Publik)
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Login Siswa (Publik - bisa diakses tanpa login)
Route::get('/login-siswa', [LoginSiswaController::class, 'showLoginForm'])->name('login.siswa.form');
Route::post('/login-siswa', [LoginSiswaController::class, 'login'])->name('login.siswa');

// Login Industri (Publik - bisa diakses tanpa login)
Route::get('/login-industri', [LoginIndustriController::class, 'showLoginForm'])->name('login.industri.form');
Route::post('/login-industri', [LoginIndustriController::class, 'login'])->name('login.industri');

// Verifikasi Password 2 Langkah (Publik, butuh sesi pending dari login tahap 1)
Route::get('/konfirmasi-password', [\App\Http\Controllers\Auth\TwoStepLoginController::class, 'show'])->name('password.twostep.form');
Route::post('/konfirmasi-password', [\App\Http\Controllers\Auth\TwoStepLoginController::class, 'confirm'])->name('password.twostep.confirm');
Route::post('/konfirmasi-password/batal', [\App\Http\Controllers\Auth\TwoStepLoginController::class, 'cancel'])->name('password.twostep.cancel');

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
    } elseif ($user->role === 'industri') {
        return redirect()->route('industri.dashboard');
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

        // Route Template Penilaian
        Route::resource('template-penilaian', \App\Http\Controllers\Admin\TemplatePenilaianController::class);
        Route::post('/template-penilaian/{templatePenilaian}/toggle-active', [\App\Http\Controllers\Admin\TemplatePenilaianController::class, 'toggleActive'])->name('template-penilaian.toggle-active');
        Route::put('/template-penilaian/{templatePenilaian}/inline', [\App\Http\Controllers\Admin\TemplatePenilaianController::class, 'updateInline'])->name('template-penilaian.inline');
        Route::post('/template-penilaian/add-sub-item', [\App\Http\Controllers\Admin\TemplatePenilaianController::class, 'addSubItem'])->name('template-penilaian.add-sub-item');
        Route::post('/template-penilaian/add-item', [\App\Http\Controllers\Admin\TemplatePenilaianController::class, 'addItem'])->name('template-penilaian.add-item');
        Route::post('/template-penilaian/store-table', [\App\Http\Controllers\Admin\TemplatePenilaianController::class, 'storeTable'])->name('template-penilaian.store-table');
        Route::delete('/template-penilaian/destroy-table', [\App\Http\Controllers\Admin\TemplatePenilaianController::class, 'destroyTable'])->name('template-penilaian.destroy-table');
        Route::post('/template-penilaian/{templatePenilaian}/update-inline', [App\Http\Controllers\Admin\TemplatePenilaianController::class, 'updateInline'])->name('admin.template-penilaian.update-inline');

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

        // Route Nilai Kesimpulan
        Route::get('/kesimpulan', [\App\Http\Controllers\Guru\KesimpulanController::class, 'index'])->name('kesimpulan.index');
        Route::get('/kesimpulan/{penempatan_id}', [\App\Http\Controllers\Guru\KesimpulanController::class, 'show'])->name('kesimpulan.show');
        Route::post('/kesimpulan/{penempatan_id}', [\App\Http\Controllers\Guru\KesimpulanController::class, 'store'])->name('kesimpulan.store');
    });

    // ==================== INDUSTRI ROUTES ====================
    Route::middleware(['role:industri'])->prefix('industri')->name('industri.')->group(function () {
        Route::get('/dashboard', function () {
            return redirect()->route('industri.penilaian.index');
        })->name('dashboard');
        Route::get('/penilaian', [\App\Http\Controllers\Industri\PenilaianController::class, 'index'])->name('penilaian.index');
        Route::get('/penilaian/{penempatan_id}', [\App\Http\Controllers\Industri\PenilaianController::class, 'show'])->name('penilaian.show');
        Route::post('/penilaian/{penempatan_id}', [\App\Http\Controllers\Industri\PenilaianController::class, 'store'])->name('penilaian.store');
    });

    // ==================== LAPORAN ROUTES ====================
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/jurnal', [LaporanController::class, 'jurnal'])->name('jurnal');
        Route::get('/nilai', [LaporanController::class, 'nilai'])->name('nilai');
        Route::get('/pkl', [LaporanController::class, 'pkl'])->name('pkl');
        Route::get('/pkl/{penempatan_id}/excel', [LaporanController::class, 'pklExport'])->name('pkl.excel');
        Route::get('/pkl/{penempatan_id}', [LaporanController::class, 'pklShow'])->name('pkl.show');
        Route::get('/jurnal/pdf', [LaporanController::class, 'jurnalPdf'])->name('jurnal.pdf');
        Route::get('/nilai/pdf', [LaporanController::class, 'nilaiPdf'])->name('nilai.pdf');
        Route::get('/raport/pdf', [LaporanController::class, 'raportPdf'])->name('raport.pdf');
        Route::get('/nilai/cetak/{penempatan_id}', [LaporanController::class, 'nilaiCetak'])->name('nilai.cetak');
    });

    // Profile (Breeze - bisa diakses semua role: admin, guru, siswa)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
}); // <-- Tutup middleware auth

require __DIR__ . '/auth.php';
