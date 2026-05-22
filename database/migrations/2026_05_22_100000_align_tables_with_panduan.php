<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menyelaraskan tabel-tabel yang sudah ada dengan spesifikasi
     * PANDUAN_MIGRATION.md file 8-13.
     */
    public function up(): void
    {
        // ── FILE 8: kelas — tambah kolom tingkat ──
        if (!Schema::hasColumn('kelas', 'tingkat')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->string('tingkat', 10)->nullable()->after('nama_kelas');
            });
        }

        // ── FILE 9: siswas — tambah kolom biodata lengkap ──
        Schema::table('siswas', function (Blueprint $table) {
            if (!Schema::hasColumn('siswas', 'nis')) {
                $table->string('nis', 20)->unique()->nullable()->after('nisn');
            }
            if (!Schema::hasColumn('siswas', 'tempat_lahir')) {
                $table->string('tempat_lahir', 100)->nullable()->after('nama_siswa');
            }
            if (!Schema::hasColumn('siswas', 'tgl_lahir')) {
                $table->date('tgl_lahir')->nullable()->after('tempat_lahir');
            }
            if (!Schema::hasColumn('siswas', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tgl_lahir');
            }
            if (!Schema::hasColumn('siswas', 'alamat')) {
                $table->text('alamat')->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('siswas', 'status_aktif')) {
                $table->boolean('status_aktif')->default(true)->after('alamat');
            }
            if (!Schema::hasColumn('siswas', 'jabatan_kelas')) {
                $table->string('jabatan_kelas', 50)->nullable()->after('status_aktif');
            }
            if (!Schema::hasColumn('siswas', 'sekolah_id')) {
                $table->unsignedBigInteger('sekolah_id')->nullable()->after('jabatan_kelas');
                $table->foreign('sekolah_id')->references('id')->on('sekolahs')->onDelete('restrict');
            }
        });

        // Tambahkan unique index ke nisn jika belum ada
        try {
            Schema::table('siswas', function (Blueprint $table) {
                $table->unique('nisn');
            });
        } catch (\Exception $e) {
            // Index sudah ada, skip
        }

        // ── FILE 10: riwayat_status_siswas — tambah keterangan & tanggal ──
        Schema::table('riwayat_status_siswas', function (Blueprint $table) {
            if (!Schema::hasColumn('riwayat_status_siswas', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('status');
            }
            if (!Schema::hasColumn('riwayat_status_siswas', 'tanggal_perubahan')) {
                $table->date('tanggal_perubahan')->nullable()->after('keterangan');
            }
        });

        // ── FILE 11: mata_pelajarans — tambah kkm ──
        if (!Schema::hasColumn('mata_pelajarans', 'kkm')) {
            Schema::table('mata_pelajarans', function (Blueprint $table) {
                $table->unsignedTinyInteger('kkm')->default(70)->after('nama_mapel');
            });
        }

        // ── FILE 12: aturan_penilaians — tambah bobot ──
        if (!Schema::hasColumn('aturan_penilaians', 'bobot')) {
            Schema::table('aturan_penilaians', function (Blueprint $table) {
                $table->decimal('bobot', 5, 2)->default(0)->after('nama_komponen');
            });
        }
    }

    public function down(): void
    {
        // aturan_penilaians
        if (Schema::hasColumn('aturan_penilaians', 'bobot')) {
            Schema::table('aturan_penilaians', function (Blueprint $table) {
                $table->dropColumn('bobot');
            });
        }

        // mata_pelajarans
        if (Schema::hasColumn('mata_pelajarans', 'kkm')) {
            Schema::table('mata_pelajarans', function (Blueprint $table) {
                $table->dropColumn('kkm');
            });
        }

        // riwayat_status_siswas
        Schema::table('riwayat_status_siswas', function (Blueprint $table) {
            if (Schema::hasColumn('riwayat_status_siswas', 'tanggal_perubahan')) {
                $table->dropColumn('tanggal_perubahan');
            }
            if (Schema::hasColumn('riwayat_status_siswas', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
        });

        // siswas
        Schema::table('siswas', function (Blueprint $table) {
            if (Schema::hasColumn('siswas', 'sekolah_id')) {
                $table->dropForeign(['sekolah_id']);
                $table->dropColumn('sekolah_id');
            }
        });
        Schema::table('siswas', function (Blueprint $table) {
            $columns = ['jabatan_kelas', 'status_aktif', 'alamat', 'jenis_kelamin', 'tgl_lahir', 'tempat_lahir', 'nis'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('siswas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // kelas
        if (Schema::hasColumn('kelas', 'tingkat')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->dropColumn('tingkat');
            });
        }
    }
};
