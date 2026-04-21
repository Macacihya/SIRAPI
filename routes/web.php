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

<<<<<<< Updated upstream
// Mockup routes (tanpa controller) — file ada di folder pages/
Route::view('/didit', 'pages.didit');
Route::view('/rian', 'pages.rian');
Route::view('/fariz', 'pages.fariz');
Route::view('/home', 'pages.home');
Route::view('/about', 'pages.about');
Route::view('/contact', 'pages.contact');
Route::view('/features', 'pages.features');
Route::view('/services', 'pages.services');

Route::get('/products', [ProductController::class, 'index']);

=======
>>>>>>> Stashed changes
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
        Route::view('/dashboard', 'guru.dashboard')->name('dashboard');
        Route::view('/jadwal-mengajar', 'guru.jadwal-mengajar')->name('jadwal-mengajar');
        Route::view('/data-siswa', 'guru.data-siswa')->name('data-siswa');
        Route::view('/penilaian', 'guru.penilaian')->name('penilaian');
        Route::view('/capaian-kompetensi', 'guru.capaian-kompetensi')->name('capaian-kompetensi');
        Route::view('/laporan-nilai', 'guru.laporan-nilai')->name('laporan-nilai');
        Route::view('/rekap-nilai', 'guru.rekap-nilai')->name('rekap-nilai');
        Route::view('/profil', 'guru.profil')->name('profil');
    });

    Route::resource('sekolah', SekolahController::class);
    Route::resource('guru', GuruController::class);
    Route::get('/siswa/tampilkan', [SiswaController::class, 'tampilkan'])->name('siswa.tampilkan');

    // ─── Walikelas Pages ─────────────────────────────────
    Route::prefix('walikelas')->name('walikelas.')->group(function () {
        Route::view('/dashboard', 'walikelas.dashboard-walikelas')->name('dashboard');
        Route::view('/profil-kelas', 'walikelas.profil-kelas')->name('profil-kelas');
        Route::view('/jadwal-kelas', 'walikelas.jadwal-kelas')->name('jadwal-kelas');
        Route::view('/kehadiran', 'walikelas.kehadiran')->name('kehadiran');
        Route::view('/penilaian', 'walikelas.penilaian')->name('penilaian');
        Route::view('/rapor', 'walikelas.rapor')->name('rapor');
        Route::view('/rapor/lihat', 'walikelas.lihat-rapor')->name('rapor.lihat');
        Route::view('/laporan-nilai', 'walikelas.laporan-nilai')->name('laporan-nilai');
        Route::view('/rekap-nilai', 'walikelas.rekap-nilai')->name('rekap-nilai');
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
        Route::view('/mata-pelajaran', 'admin.mata-pelajaran')->name('mata-pelajaran');
        Route::view('/jadwal-pelajaran', 'admin.jadwal-pelajaran')->name('jadwal-pelajaran');
        Route::view('/aturan-nilai', 'admin.aturan-nilai')->name('aturan-nilai');
        Route::view('/laporan-nilai', 'admin.laporan-nilai')->name('laporan-nilai');
        Route::view('/rekap-nilai', 'admin.rekap-nilai')->name('rekap-nilai');
        Route::view('/profil', 'admin.profil-admin')->name('profil');
    });
});