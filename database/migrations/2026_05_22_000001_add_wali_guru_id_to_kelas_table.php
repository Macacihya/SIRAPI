<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('kelas', 'wali_guru_id')) {
            return;
        }

        Schema::table('kelas', function (Blueprint $table) {
            $table->unsignedBigInteger('wali_guru_id')->nullable()->after('tahun_ajaran_id');
            $table->foreign('wali_guru_id')->references('user_id')->on('gurus')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('kelas', 'wali_guru_id')) {
            return;
        }

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['wali_guru_id']);
            $table->dropColumn('wali_guru_id');
        });
    }
};
