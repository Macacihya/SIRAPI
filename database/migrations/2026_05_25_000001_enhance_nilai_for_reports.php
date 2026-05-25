<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hubungkan nilai dengan mata pelajaran agar laporan dan rapor bisa menampilkan nama mapel.
        if (!Schema::hasColumn('nilais', 'mapel_id')) {
            Schema::table('nilais', function (Blueprint $table) {
                $table->string('mapel_id', 20)->nullable()->after('raport_id');
                $table->foreign('mapel_id')->references('kode_mapel')->on('mata_pelajarans')->nullOnDelete();
            });
        }

        // Deskripsi capaian kompetensi dipakai pada detail nilai dan cetak rapor.
        if (!Schema::hasColumn('capaian_kompetensis', 'deskripsi')) {
            Schema::table('capaian_kompetensis', function (Blueprint $table) {
                $table->text('deskripsi')->nullable()->after('nilai_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('nilais', 'mapel_id')) {
            Schema::table('nilais', function (Blueprint $table) {
                $table->dropForeign(['mapel_id']);
                $table->dropColumn('mapel_id');
            });
        }

        if (Schema::hasColumn('capaian_kompetensis', 'deskripsi')) {
            Schema::table('capaian_kompetensis', function (Blueprint $table) {
                $table->dropColumn('deskripsi');
            });
        }
    }
};
