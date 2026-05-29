<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Daftarkan tabel roles & user_roles ke sistem migration Laravel.
     * Tabel sudah dibuat manual di MySQL — migration ini hanya memastikan
     * strukturnya benar dan mengisi data awal (roles + user_roles) untuk
     * pengguna yang sudah ada.
     */
    public function up(): void
    {
        // ── 1. Buat tabel roles jika belum ada ─────────────────
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->enum('nama_role', ['admin', 'guru', 'walikelas'])->unique();
            });
        }

        // ── 2. Buat tabel user_roles jika belum ada ────────────
        if (!Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedInteger('role_id');
                $table->primary(['user_id', 'role_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        }

        // ── 3. Seed 3 role dasar ────────────────────────────────
        foreach (['admin', 'guru', 'walikelas'] as $roleName) {
            DB::table('roles')->insertOrIgnore(['nama_role' => $roleName]);
        }

        $adminRoleId     = DB::table('roles')->where('nama_role', 'admin')->value('id');
        $guruRoleId      = DB::table('roles')->where('nama_role', 'guru')->value('id');
        $walikekasRoleId = DB::table('roles')->where('nama_role', 'walikelas')->value('id');

        // ── 4. Isi user_roles untuk pengguna yang sudah ada ────

        // Admin — dari tabel admins
        $adminUserIds = DB::table('admins')->pluck('user_id');
        foreach ($adminUserIds as $userId) {
            DB::table('user_roles')->insertOrIgnore([
                'user_id' => $userId,
                'role_id' => $adminRoleId,
            ]);
        }

        // Guru & Walikelas — dari tabel gurus, deteksi jabatan
        $gurus = DB::table('gurus')->get(['user_id', 'jabatan']);
        foreach ($gurus as $guru) {
            $jabatan = strtolower($guru->jabatan ?? '');

            // Selalu dapat role 'guru'
            DB::table('user_roles')->insertOrIgnore([
                'user_id' => $guru->user_id,
                'role_id' => $guruRoleId,
            ]);

            // Tambah role 'walikelas' jika jabatan mengandung "wali"
            if (str_contains($jabatan, 'wali')) {
                DB::table('user_roles')->insertOrIgnore([
                    'user_id' => $guru->user_id,
                    'role_id' => $walikekasRoleId,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
    }
};
