<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\AturanPenilaianController;
use App\Http\Controllers\GuruPengampuController;
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
        Route::get('/manajemen-user', [App\Http\Controllers\UserController::class, 'index'])->name('manajemen-user');
        Route::post('/manajemen-user', [App\Http\Controllers\UserController::class, 'store'])->name('manajemen-user.store');
        Route::put('/manajemen-user/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('manajemen-user.update');
        Route::delete('/manajemen-user/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('manajemen-user.destroy');

        Route::get('/data-sekolah', [App\Http\Controllers\SekolahController::class, 'index'])->name('data-sekolah');
        Route::view('/akademik', 'pages.akademik.index')->name('akademik');

        // Guru & Tendik
        Route::get('/guru-tendik', [GuruController::class, 'index'])->name('guru-tendik');
        Route::get('/guru/tampilkan', [GuruController::class, 'tampilkan'])->name('guru.tampilkan');
        Route::resource('guru', GuruController::class);

        // Sekolah
        Route::resource('sekolah', SekolahController::class);

        // Siswa (tampilkan harus sebelum resource)
        Route::get('/siswa/tampilkan', [SiswaController::class, 'tampilkan'])->name('siswa.tampilkan');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

        // Kelas
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::put('/kelas/{kela}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{kela}', [KelasController::class, 'destroy'])->name('kelas.destroy');

        // Mata Pelajaran
        Route::get('/mata-pelajaran', [MataPelajaranController::class, 'index'])->name('mata-pelajaran');
        Route::post('/mata-pelajaran', [MataPelajaranController::class, 'store'])->name('mata-pelajaran.store');
        Route::put('/mata-pelajaran/{kodeMapel}', [MataPelajaranController::class, 'update'])->name('mata-pelajaran.update');
        Route::delete('/mata-pelajaran/{kodeMapel}', [MataPelajaranController::class, 'destroy'])->name('mata-pelajaran.destroy');

        // Aturan Penilaian
        Route::get('/aturan-nilai', [AturanPenilaianController::class, 'index'])->name('aturan-nilai');
        Route::post('/aturan-nilai', [AturanPenilaianController::class, 'store'])->name('aturan-nilai.store');
        Route::put('/aturan-nilai/{aturanPenilaian}', [AturanPenilaianController::class, 'update'])->name('aturan-nilai.update');
        Route::delete('/aturan-nilai/{aturanPenilaian}', [AturanPenilaianController::class, 'destroy'])->name('aturan-nilai.destroy');

        // Guru Pengampu
        Route::post('/guru-pengampu', [GuruPengampuController::class, 'store'])->name('guru-pengampu.store');
        Route::delete('/guru-pengampu/{guruPengampu}', [GuruPengampuController::class, 'destroy'])->name('guru-pengampu.destroy');

        // Tahun Ajaran (sudah ada)
        Route::resource('tahun-ajaran', \App\Http\Controllers\TahunAjaranController::class);
    });

    // ── Admin & Guru ─────────────────────────────────────────
    Route::middleware('role:admin,guru')->group(function () {
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa');
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