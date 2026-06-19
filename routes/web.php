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
use App\Http\Controllers\RaportController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\LaporanNilaiController;
use App\Http\Controllers\CapaianKompetensiController;
use App\Http\Controllers\NilaiSikapController;
use App\Http\Controllers\EkstrakurikulerController;
use App\Http\Controllers\RaportEkskulController;
use App\Http\Controllers\RekapKehadiranController;
use App\Http\Controllers\ProfilKelasController;
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
    Route::post('/profil/update', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('profil.update');
    Route::post('/profil/ubah-sandi', [App\Http\Controllers\UserController::class, 'changePassword'])->name('profil.change-password');
    Route::get('/laporan-nilai', [LaporanNilaiController::class, 'index'])->name('laporan-nilai');
    Route::get('/rekap-nilai', [NilaiController::class, 'index'])->name('rekap-nilai');

    // ── Admin Only ───────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('/manajemen-user', [App\Http\Controllers\UserController::class, 'index'])->name('manajemen-user');
        Route::post('/manajemen-user', [App\Http\Controllers\UserController::class, 'store'])->name('manajemen-user.store');
        Route::put('/manajemen-user/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('manajemen-user.update');
        Route::delete('/manajemen-user/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('manajemen-user.destroy');

        Route::get('/data-sekolah', [App\Http\Controllers\SekolahController::class, 'index'])->name('data-sekolah');
        Route::post('/data-sekolah/update', [App\Http\Controllers\SekolahController::class, 'updateAjax'])->name('data-sekolah.update-ajax');
        Route::get('/akademik', [App\Http\Controllers\AkademikController::class, 'index'])->name('akademik');
        Route::post('/akademik/tahun-ajaran', [App\Http\Controllers\AkademikController::class, 'storeTahunAjaran'])->name('akademik.tahun-ajaran.store');
        Route::put('/akademik/tahun-ajaran/{tahunAjaran}', [App\Http\Controllers\AkademikController::class, 'updateTahunAjaran'])->name('akademik.tahun-ajaran.update');
        Route::delete('/akademik/tahun-ajaran/{tahunAjaran}', [App\Http\Controllers\AkademikController::class, 'destroyTahunAjaran'])->name('akademik.tahun-ajaran.destroy');
        Route::post('/akademik/tahun-ajaran/{tahunAjaran}/set-active', [App\Http\Controllers\AkademikController::class, 'setActiveTahunAjaran'])->name('akademik.tahun-ajaran.set-active');
        Route::post('/akademik/kelas', [App\Http\Controllers\AkademikController::class, 'storeKelas'])->name('akademik.kelas.store');
        Route::post('/akademik/plotting-otomatis', [App\Http\Controllers\AkademikController::class, 'runPlottingOtomatis'])->name('akademik.plotting-otomatis');

        // Guru & Tendik
        Route::get('/guru-tendik', [GuruController::class, 'index'])->name('guru-tendik');
        Route::get('/guru/tampilkan', [GuruController::class, 'tampilkan'])->name('guru.tampilkan');
        Route::get('/guru/export', [GuruController::class, 'export'])->name('guru.export');
        Route::post('/guru/import', [GuruController::class, 'import'])->name('guru.import');
        Route::resource('guru', GuruController::class);

        // Sekolah
        Route::resource('sekolah', SekolahController::class);

        // Guru AJAX
        Route::post('/guru-ajax', [GuruController::class, 'storeAjax'])->name('guru.store-ajax');
        Route::post('/guru-ajax/bulk-delete', [GuruController::class, 'bulkDestroyAjax'])->name('guru.bulk-destroy-ajax');
        Route::put('/guru-ajax/{id}', [GuruController::class, 'updateAjax'])->name('guru.update-ajax');
        Route::delete('/guru-ajax/{id}', [GuruController::class, 'destroyAjax'])->name('guru.destroy-ajax');

        // Siswa (tampilkan harus sebelum resource)
        Route::get('/siswa/tampilkan', [SiswaController::class, 'tampilkan'])->name('siswa.tampilkan');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::patch('/siswa/{siswa}/toggle-status', [SiswaController::class, 'toggleStatus'])->name('siswa.toggle-status');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
        Route::post('/siswa/penempatan', [SiswaController::class, 'penempatanStore'])->name('siswa.penempatan.store');
        Route::post('/siswa/penempatan/remove', [SiswaController::class, 'penempatanRemove'])->name('siswa.penempatan.remove');

        // Kelas
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');

        // Mata Pelajaran
        Route::get('/mata-pelajaran', [MataPelajaranController::class, 'index'])->name('mata-pelajaran');
        Route::post('/mata-pelajaran', [MataPelajaranController::class, 'store'])->name('mata-pelajaran.store');
        Route::put('/mata-pelajaran/{kodeMapel}', [MataPelajaranController::class, 'update'])->name('mata-pelajaran.update');
        Route::delete('/mata-pelajaran/{kodeMapel}', [MataPelajaranController::class, 'destroy'])->name('mata-pelajaran.destroy');

        // Aturan Penilaian
        Route::get('/aturan-nilai', [AturanPenilaianController::class, 'index'])->name('aturan-nilai');
        Route::post('/aturan-nilai', [AturanPenilaianController::class, 'store'])->name('aturan-nilai.store');
        Route::put('/aturan-nilai/{aturanPenilaian}', [AturanPenilaianController::class, 'update'])->name('aturan-nilai.update');
        Route::delete('/aturan-nilai-destroy-all', [AturanPenilaianController::class, 'destroyAll'])->name('aturan-nilai.destroy-all');
        Route::delete('/aturan-nilai/{aturanPenilaian}', [AturanPenilaianController::class, 'destroy'])->name('aturan-nilai.destroy');

        // Guru Pengampu
        Route::post('/guru-pengampu', [GuruPengampuController::class, 'store'])->name('guru-pengampu.store');
        Route::delete('/guru-pengampu/{guruPengampu}', [GuruPengampuController::class, 'destroy'])->name('guru-pengampu.destroy');
    });

    // ── Admin & Guru ─────────────────────────────────────────
    Route::middleware('role:admin,guru')->group(function () {
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa');
    });

    // ── Admin, Guru & Walikelas ──────────────────────────────
    Route::middleware('role:admin,guru,walikelas')->group(function () {
        Route::get('/penilaian', [NilaiController::class, 'penilaian'])->name('penilaian');

        // Nilai
        Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
        Route::post('/nilai/batch', [NilaiController::class, 'storeBatch'])->name('nilai.store-batch');
        Route::put('/nilai/{nilai}', [NilaiController::class, 'update'])->name('nilai.update');
        Route::delete('/nilai/{nilai}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
    });

    // ── Guru Only ────────────────────────────────────────────
    Route::middleware('role:guru')->group(function () {
        Route::get('/capaian-kompetensi', [CapaianKompetensiController::class, 'index'])->name('capaian-kompetensi');
        Route::post('/capaian-kompetensi', [CapaianKompetensiController::class, 'store'])->name('capaian-kompetensi.store');
        Route::put('/capaian-kompetensi/{capaianKompetensi}', [CapaianKompetensiController::class, 'update'])->name('capaian-kompetensi.update');
        Route::delete('/capaian-kompetensi/{capaianKompetensi}', [CapaianKompetensiController::class, 'destroy'])->name('capaian-kompetensi.destroy');
    });

    // ── Walikelas Only ───────────────────────────────────────
    Route::middleware('role:walikelas')->group(function () {
        Route::get('/profil-kelas', [ProfilKelasController::class, 'index'])->name('profil-kelas');

        Route::get('/rapor', [RaportController::class, 'index'])->name('rapor');
        Route::post('/rapor', [RaportController::class, 'store'])->name('rapor.store');
        Route::post('/rapor/generate', [RaportController::class, 'generate'])->name('rapor.generate');
        Route::post('/rapor/{raport}/save-form', [RaportController::class, 'saveForm'])->name('rapor.save-form');
        Route::get('/rapor/{raport}/lihat', [RaportController::class, 'show'])->name('rapor.show');
        Route::put('/rapor/{raport}', [RaportController::class, 'update'])->name('rapor.update');
        Route::delete('/rapor/{raport}', [RaportController::class, 'destroy'])->name('rapor.destroy');

        Route::post('/nilai-sikap', [NilaiSikapController::class, 'store'])->name('nilai-sikap.store');
        Route::put('/nilai-sikap/{nilaiSikap}', [NilaiSikapController::class, 'update'])->name('nilai-sikap.update');
        Route::delete('/nilai-sikap/{nilaiSikap}', [NilaiSikapController::class, 'destroy'])->name('nilai-sikap.destroy');

        Route::post('/ekstrakurikuler', [EkstrakurikulerController::class, 'store'])->name('ekstrakurikuler.store');
        Route::put('/ekstrakurikuler/{ekstrakurikuler}', [EkstrakurikulerController::class, 'update'])->name('ekstrakurikuler.update');
        Route::delete('/ekstrakurikuler/{ekstrakurikuler}', [EkstrakurikulerController::class, 'destroy'])->name('ekstrakurikuler.destroy');

        Route::post('/rapor-ekskul', [RaportEkskulController::class, 'store'])->name('rapor-ekskul.store');
        Route::put('/rapor-ekskul/{raportEkskul}', [RaportEkskulController::class, 'update'])->name('rapor-ekskul.update');
        Route::delete('/rapor-ekskul/{raportEkskul}', [RaportEkskulController::class, 'destroy'])->name('rapor-ekskul.destroy');

        // Rekap Kehadiran (per entri, disimpan menggunakan mode sync)
        Route::post('/rekap-kehadiran/sync', [RekapKehadiranController::class, 'sync'])->name('rekap-kehadiran.sync');

        Route::view('/rapor/lihat', 'pages.rapor.lihat')->name('rapor.lihat');
    });
});
