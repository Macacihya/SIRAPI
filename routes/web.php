<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// Mockup routes (tanpa controller)
Route::view('/home', 'home');
Route::view('/about', 'about');
Route::view('/contact', 'contact');
Route::view('/features', 'features');
Route::view('/services', 'services');

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

    Route::resource('sekolah', SekolahController::class);
    Route::resource('guru', GuruController::class);
    Route::get('/siswa/tampilkan', [SiswaController::class, 'tampilkan'])->name('siswa.tampilkan');

    // ─── Walikelas Pages ─────────────────────────────────
    Route::prefix('walikelas')->name('walikelas.')->group(function () {
        Route::view('/profil-kelas', 'walikelas.profil-kelas')->name('profil-kelas');
        Route::view('/jadwal-kelas', 'walikelas.jadwal-kelas')->name('jadwal-kelas');
        Route::view('/kehadiran', 'walikelas.kehadiran')->name('kehadiran');
        Route::view('/penilaian', 'walikelas.penilaian')->name('penilaian');
        Route::view('/rapor', 'walikelas.rapor')->name('rapor');
        Route::view('/profil', 'walikelas.profil-user')->name('profil');
    });

    // ─── Admin TU Pages ─────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::view('/dashboard', 'admin.dashboard-admin')->name('dashboard');
        Route::view('/manajemen-user', 'admin.manajemen-user')->name('manajemen-user');
        Route::view('/data-sekolah', 'admin.data-sekolah')->name('data-sekolah');
        Route::view('/guru', 'admin.guru-tendik')->name('guru');
        Route::view('/data-siswa', 'admin.data-siswa-admin')->name('data-siswa');
        Route::view('/akademik', 'admin.akademik')->name('akademik');
        Route::view('/jadwal-pelajaran', 'admin.jadwal-pelajaran')->name('jadwal-pelajaran');
        Route::view('/aturan-nilai', 'admin.aturan-nilai')->name('aturan-nilai');
        Route::view('/profil', 'admin.profil-admin')->name('profil');
    });
});