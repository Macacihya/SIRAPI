<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_sikaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raports')->cascadeOnDelete();
            $table->enum('predikat', ['A', 'B', 'C', 'D'])->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->unique('raport_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_sikaps');
    }
};