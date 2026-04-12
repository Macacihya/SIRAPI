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
Route::get('/siswa/tampilkan', [SiswaController::class, 'tampilkan'])->name('siswa.tampilkan');});