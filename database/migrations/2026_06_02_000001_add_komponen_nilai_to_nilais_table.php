<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom komponen nilai (UH, UTS, UAS) dan status ke tabel nilais.
     * Ini memungkinkan guru menyimpan nilai per komponen dan melacak
     * status pengisian (belum / draft / final).
     */
    public function up(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            if (!Schema::hasColumn('nilais', 'nilai_uh')) {
                $table->decimal('nilai_uh', 5, 2)->nullable()->after('nilai_akhir');
            }
            if (!Schema::hasColumn('nilais', 'nilai_uts')) {
                $table->decimal('nilai_uts', 5, 2)->nullable()->after('nilai_uh');
            }
            if (!Schema::hasColumn('nilais', 'nilai_uas')) {
                $table->decimal('nilai_uas', 5, 2)->nullable()->after('nilai_uts');
            }
            if (!Schema::hasColumn('nilais', 'status')) {
                $table->enum('status', ['belum', 'draft', 'final'])->default('belum')->after('mapel_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            $columns = ['nilai_uh', 'nilai_uts', 'nilai_uas', 'status'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('nilais', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
