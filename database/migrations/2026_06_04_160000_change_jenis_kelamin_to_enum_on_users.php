<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah kolom jenis_kelamin dari varchar ke enum('Pria','Wanita').
     * Data lama di-mapping: L/Laki-laki → Pria, P/Perempuan → Wanita.
     */
    public function up(): void
    {
        // 1. Konversi data lama ke format baru
        DB::table('users')->where('jenis_kelamin', 'L')->update(['jenis_kelamin' => 'Pria']);
        DB::table('users')->where('jenis_kelamin', 'Laki-laki')->update(['jenis_kelamin' => 'Pria']);
        DB::table('users')->where('jenis_kelamin', 'P')->update(['jenis_kelamin' => 'Wanita']);
        DB::table('users')->where('jenis_kelamin', 'Perempuan')->update(['jenis_kelamin' => 'Wanita']);

        // 2. Ubah kolom ke enum
        DB::statement("ALTER TABLE users MODIFY jenis_kelamin ENUM('Pria','Wanita') NULL DEFAULT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY jenis_kelamin VARCHAR(255) NULL DEFAULT NULL");
    }
};
