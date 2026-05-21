<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Log riwayat perubahan status mengajar guru.
     */
    public function up(): void
    {
        Schema::create('riwayat_status_gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')
                  ->references('user_id')->on('gurus')
                  ->onDelete('cascade');
            $table->string('status', 50);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_perubahan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_status_gurus');
    }
};
