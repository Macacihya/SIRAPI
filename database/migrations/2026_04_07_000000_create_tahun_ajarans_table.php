<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun_ajarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun_mulai');
            $table->unsignedSmallInteger('tahun_selesai');
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['tahun_mulai', 'tahun_selesai', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_ajarans');
    }
};
