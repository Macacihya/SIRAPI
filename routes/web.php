<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::post('/', [AuthController::class, 'login']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [PasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ⚠️ tampilkan HARUS sebelum resource
    Route::get('/guru/tampilkan', [GuruController::class, 'tampilkan'])->name('guru.tampilkan');

    // ─── Guru Mata Pelajaran Pages ─────────────────────────────────
    Route::prefix('guru')->name('guru.')->group(function () {
        Route::view('/dashboard', 'pages.guru.dashboard')->name('dashboard');
        Route::view('/jadwal-mengajar', 'pages.guru.jadwal-mengajar')->name('jadwal-mengajar');
        Route::view('/data-siswa', 'pages.guru.data-siswa')->name('data-siswa');
        Route::view('/penilaian', 'pages.guru.penilaian')->name('penilaian');
        Route::view('/capaian-kompetensi', 'pages.guru.capaian-kompetensi')->name('capaian-kompetensi');
        Route::view('/laporan-nilai', 'pages.guru.laporan-nilai')->name('laporan-nilai');
        Route::view('/rekap-nilai', 'pages.guru.rekap-nilai')->name('rekap-nilai');
        Route::view('/profil', 'pages.guru.profil')->name('profil');
    });

    Route::resource('sekolah', SekolahController::class);
    Route::resource('guru', GuruController::class);
    Route::get('/siswa/tampilkan', [SiswaController::class, 'tampilkan'])->name('siswa.tampilkan');

    // ─── Walikelas Pages ─────────────────────────────────
    Route::prefix('walikelas')->name('walikelas.')->group(function () {
        Route::view('/dashboard', 'pages.walikelas.dashboard-walikelas')->name('dashboard');
        Route::view('/profil-kelas', 'pages.walikelas.profil-kelas')->name('profil-kelas');
        Route::view('/jadwal-kelas', 'pages.walikelas.jadwal-kelas')->name('jadwal-kelas');
        Route::view('/kehadiran', 'pages.walikelas.kehadiran')->name('kehadiran');
        Route::view('/penilaian', 'pages.walikelas.penilaian')->name('penilaian');
        Route::view('/rapor', 'pages.walikelas.rapor')->name('rapor');
        Route::view('/rapor/lihat', 'pages.walikelas.lihat-rapor')->name('rapor.lihat');
        Route::view('/laporan-nilai', 'pages.walikelas.laporan-nilai')->name('laporan-nilai');
        Route::view('/rekap-nilai', 'pages.walikelas.rekap-nilai')->name('rekap-nilai');
        Route::view('/profil', 'pages.walikelas.profil-user')->name('profil');
    });

    // ─── Admin TU Pages ─────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::view('/dashboard', 'pages.admin.dashboard-admin')->name('dashboard');
        Route::view('/manajemen-user', 'pages.admin.manajemen-user')->name('manajemen-user');
        Route::view('/data-sekolah', 'pages.admin.data-sekolah')->name('data-sekolah');
        Route::view('/guru', 'pages.admin.guru-tendik')->name('guru');
        Route::view('/data-siswa', 'pages.admin.data-siswa-admin')->name('data-siswa');
        Route::view('/akademik', 'pages.admin.akademik')->name('akademik');
        Route::view('/mata-pelajaran', 'pages.admin.mata-pelajaran')->name('mata-pelajaran');
        Route::view('/jadwal-pelajaran', 'pages.admin.jadwal-pelajaran')->name('jadwal-pelajaran');
        Route::view('/aturan-nilai', 'pages.admin.aturan-nilai')->name('aturan-nilai');
        Route::view('/laporan-nilai', 'pages.admin.laporan-nilai')->name('laporan-nilai');
        Route::view('/rekap-nilai', 'pages.admin.rekap-nilai')->name('rekap-nilai');
        Route::view('/profil', 'pages.admin.profil-admin')->name('profil');
    });
});