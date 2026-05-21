<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_pengampus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guru_id');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('mapel_id', 20);
            $table->timestamps();

            $table->foreign('guru_id')->references('user_id')->on('gurus')->onDelete('cascade');
            $table->foreign('mapel_id')->references('kode_mapel')->on('mata_pelajarans')->onDelete('cascade');

            // Satu guru hanya bisa mengampu satu mapel di satu kelas
            $table->unique(['guru_id', 'kelas_id', 'mapel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_pengampus');
    }
};
