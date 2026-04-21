<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

// ─── Guest Routes ────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));
Route::post('/', [AuthController::class, 'login']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [PasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');
});

// ─── Authenticated Routes ────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Semua Role ───────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('/profil', 'pages.profil.index')->name('profil');
    Route::view('/laporan-nilai', 'pages.laporan-nilai.index')->name('laporan-nilai');
    Route::view('/rekap-nilai', 'pages.rekap-nilai.index')->name('rekap-nilai');

    // ── Admin Only ───────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::view('/manajemen-user', 'pages.manajemen-user.index')->name('manajemen-user');
        Route::view('/data-sekolah', 'pages.data-sekolah.index')->name('data-sekolah');
        Route::view('/guru-tendik', 'pages.guru-tendik.index')->name('guru-tendik');
        Route::view('/akademik', 'pages.akademik.index')->name('akademik');
        Route::view('/mata-pelajaran', 'pages.mata-pelajaran.index')->name('mata-pelajaran');
        Route::view('/aturan-nilai', 'pages.aturan-nilai.index')->name('aturan-nilai');

        // ⚠️ tampilkan HARUS sebelum resource
        Route::get('/guru/tampilkan', [GuruController::class, 'tampilkan'])->name('guru.tampilkan');
        Route::resource('sekolah', SekolahController::class);
        Route::resource('guru', GuruController::class);
        Route::get('/siswa/tampilkan', [SiswaController::class, 'tampilkan'])->name('siswa.tampilkan');
    });

    // ── Admin & Guru ─────────────────────────────────────────
    Route::middleware('role:admin,guru')->group(function () {
        Route::view('/siswa', 'pages.siswa.index')->name('siswa');
    });

    // ── Admin, Guru & Walikelas ──────────────────────────────
    Route::middleware('role:admin,guru,walikelas')->group(function () {
        Route::view('/jadwal', 'pages.jadwal.index')->name('jadwal');
        Route::view('/penilaian', 'pages.penilaian.index')->name('penilaian');
    });

    // ── Guru Only ────────────────────────────────────────────
    Route::middleware('role:guru')->group(function () {
        Route::view('/capaian-kompetensi', 'pages.capaian-kompetensi.index')->name('capaian-kompetensi');
    });

    // ── Walikelas Only ───────────────────────────────────────
    Route::middleware('role:walikelas')->group(function () {
        Route::view('/profil-kelas', 'pages.profil-kelas.index')->name('profil-kelas');
        Route::view('/kehadiran', 'pages.kehadiran.index')->name('kehadiran');
        Route::view('/rapor', 'pages.rapor.index')->name('rapor');
        Route::view('/rapor/lihat', 'pages.rapor.lihat')->name('rapor.lihat');
    });
});