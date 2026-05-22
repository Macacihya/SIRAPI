<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot relasi Many-to-Many antara Raport dan Sikap.
     * Satu raport dapat memiliki banyak nilai sikap (Spiritual, Sosial, dll),
     * dan satu jenis sikap dapat dicatat di banyak raport.
     */
    public function up(): void
    {
        Schema::create('raport_sikaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raports')->cascadeOnDelete();
            $table->foreignId('sikap_id')->constrained('sikaps')->cascadeOnDelete();
            $table->enum('predikat', ['A', 'B', 'C', 'D'])->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            // Satu raport hanya boleh punya satu nilai per jenis sikap
            $table->unique(['raport_id', 'sikap_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raport_sikaps');
    }
};