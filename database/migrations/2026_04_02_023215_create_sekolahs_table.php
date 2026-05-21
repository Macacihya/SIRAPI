<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel profil instansi sekolah.
     * Dibuat sebelum gurus dan siswas karena keduanya FK ke sini.
     */
    public function up(): void
    {
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 20)->unique();
            $table->string('nama_sekolah');
            $table->text('alamat');
            $table->string('kode_pos', 10)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('nip_kepsek', 50)->nullable();
            $table->string('status_sekolah', 50)->nullable();
            $table->string('nama_kepala_sekolah')->nullable();
            $table->string('bentuk_pendidikan', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};