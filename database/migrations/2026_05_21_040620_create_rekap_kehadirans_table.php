<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekap_kehadirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raports')->cascadeOnDelete();
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('alpha')->default(0);
            $table->timestamps();

            $table->unique('raport_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_kehadirans');
    }
};