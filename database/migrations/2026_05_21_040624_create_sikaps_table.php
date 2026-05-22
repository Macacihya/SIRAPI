<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel master jenis sikap (contoh: Spiritual, Sosial).
     * Diisi melalui seeder, tidak perlu CRUD oleh user.
     */
    public function up(): void
    {
        Schema::create('sikaps', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sikap', 100)->unique(); // contoh: Spiritual, Sosial
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sikaps');
    }
};
