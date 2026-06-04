<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raports', function (Blueprint $table) {
            // Status dipakai halaman rapor untuk membedakan draft dan rapor yang sudah dikunci.
            if (!Schema::hasColumn('raports', 'status')) {
                $table->enum('status', ['draft', 'final'])->default('draft')->after('tahun_ajaran_id');
            }

            // Catatan wali kelas dicetak di halaman akhir rapor.
            if (!Schema::hasColumn('raports', 'catatan_wali')) {
                $table->text('catatan_wali')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('raports', function (Blueprint $table) {
            if (Schema::hasColumn('raports', 'catatan_wali')) {
                $table->dropColumn('catatan_wali');
            }

            if (Schema::hasColumn('raports', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
