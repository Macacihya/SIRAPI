<?php

use App\Http\Controllers\SekolahController;
use App\Http\Controllers\GuruController;

Route::resource('sekolah', SekolahController::class);
Route::resource('guru', GuruController::class);
