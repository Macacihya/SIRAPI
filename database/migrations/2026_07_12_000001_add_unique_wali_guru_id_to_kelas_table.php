<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bersihkan duplikat wali_guru_id sebelum menambah unique constraint
        // (jaga-jaga jika ada data inkonsisten)
        $duplicates = \Illuminate\Support\Facades\DB::table('kelas')
            ->select('wali_guru_id')
            ->whereNotNull('wali_guru_id')
            ->groupBy('wali_guru_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('wali_guru_id');

        foreach ($duplicates as $guruId) {
            // Pertahankan hanya assignment pertama, null-kan sisanya
            $kelasIds = \Illuminate\Support\Facades\DB::table('kelas')
                ->where('wali_guru_id', $guruId)
                ->orderBy('id')
                ->pluck('id');

            \Illuminate\Support\Facades\DB::table('kelas')
                ->whereIn('id', $kelasIds->skip(1)->values())
                ->update(['wali_guru_id' => null]);
        }

        Schema::table('kelas', function (Blueprint $table) {
            $table->unique('wali_guru_id');
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropUnique(['wali_guru_id']);
        });
    }
};
