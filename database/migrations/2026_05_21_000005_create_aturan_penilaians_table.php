<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aturan_penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komponen', 100);
            $table->string('mapel_id', 20);
            $table->timestamps();

            $table->foreign('mapel_id')->references('kode_mapel')->on('mata_pelajarans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan_penilaians');
    }
};
