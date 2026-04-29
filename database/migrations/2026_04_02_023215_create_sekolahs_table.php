<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah');
            $table->text('alamat');
            $table->string('telepon');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};