<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->decimal('nilai_akhir', 5, 2)->nullable()->change();
            $table->decimal('nilai_uh', 5, 2)->nullable()->change();
            $table->decimal('nilai_uts', 5, 2)->nullable()->change();
            $table->decimal('nilai_uas', 5, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->unsignedTinyInteger('nilai_akhir')->nullable()->change();
            $table->unsignedTinyInteger('nilai_uh')->nullable()->change();
            $table->unsignedTinyInteger('nilai_uts')->nullable()->change();
            $table->unsignedTinyInteger('nilai_uas')->nullable()->change();
        });
    }
};
