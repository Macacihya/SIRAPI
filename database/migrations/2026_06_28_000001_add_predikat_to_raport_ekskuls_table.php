<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raport_ekskuls', function (Blueprint $table) {
            // Predikat milik tiap siswa, sedangkan nama ekskul tetap berasal dari master admin.
            $table->enum('predikat', ['A', 'B', 'C', 'D'])->nullable()->after('ekstrakurikuler_id');
        });
    }

    public function down(): void
    {
        Schema::table('raport_ekskuls', function (Blueprint $table) {
            $table->dropColumn('predikat');
        });
    }
};
