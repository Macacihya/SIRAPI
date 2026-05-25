<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raport_ekskuls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raports')->cascadeOnDelete();
            $table->foreignId('ekstrakurikuler_id')->constrained('ekstrakurikulers')->cascadeOnDelete();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->unique(['raport_id', 'ekstrakurikuler_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raport_ekskuls');
    }
};
