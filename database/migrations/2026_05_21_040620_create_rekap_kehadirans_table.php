<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rekap kehadiran siswa — satu baris per raport (One-to-One).
     * Menyimpan total hari ketidakhadiran dalam satu semester.
     * Wali kelas menginput angka total, bukan per kejadian.
     */
    public function up(): void
    {
        Schema::create('rekap_kehadirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raports')->cascadeOnDelete();
            $table->unsignedSmallInteger('sakit')->default(0);
            $table->unsignedSmallInteger('izin')->default(0);
            $table->unsignedSmallInteger('alpha')->default(0);
            $table->timestamps();

            // One-to-One: satu raport hanya boleh punya satu rekap kehadiran
            $table->unique('raport_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_kehadirans');
    }
};