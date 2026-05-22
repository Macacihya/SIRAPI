<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rekap kehadiran siswa — satu baris per entri ketidakhadiran.
     * Status: sakit / izin / alpha. Keterangan bersifat opsional.
     */
    public function up(): void
    {
        Schema::create('rekap_kehadirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raports')->cascadeOnDelete();
            $table->enum('status', ['sakit', 'izin', 'alpha']);
            $table->text('keterangan')->nullable(); // opsional, cth: "sakit perut"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_kehadirans');
    }
};