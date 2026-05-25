# 🛠️ PANDUAN LENGKAP MIGRATION & SQL — SIRAPI-2



Dokumen ini berisi **semua kode yang kamu butuhkan** untuk membangun ulang seluruh database SIRAPI-2 dari nol.

---

## LANGKAH 1 — Hapus Tabel Lama (Opsional jika mau rebuild)

Jalankan perintah ini di terminal project-mu:
```bash
php artisan migrate:fresh
```

> Perintah ini otomatis DROP semua tabel lama dan buat ulang dari awal.

---

## LANGKAH 2 — Update/Buat File Migration

Salin setiap blok kode di bawah ini ke file migration yang sesuai di folder `database/migrations/`.

---

### 📄 FILE 1: `0001_01_01_000000_create_users_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel induk ISA — menyimpan atribut umum semua pengguna.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'guru', 'walikelas']);
            $table->string('jenis_kelamin')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
```

---

### 📄 FILE 2: `2026_04_02_023215_create_sekolahs_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel profil instansi sekolah.
     * Dibuat sebelum gurus dan siswas karena keduanya FK ke sini.
     */
    public function up(): void
    {
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 20)->unique();
            $table->string('nama_sekolah');
            $table->text('alamat');
            $table->string('kode_pos', 10)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('nip_kepsek', 50)->nullable();
            $table->string('status_sekolah', 50)->nullable();
            $table->string('nama_kepala_sekolah')->nullable();
            $table->string('bentuk_pendidikan', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};
```

---

### 📄 FILE 3: `2026_04_02_023216_create_admins_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel anak ISA — spesialisasi untuk role Admin.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('users')->onDelete('cascade');
            $table->string('jabatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
```

---

### 📄 FILE 4: `2026_04_02_023217_create_gurus_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel anak ISA — spesialisasi untuk role Guru & Walikelas.
     */
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('users')->onDelete('cascade');
            $table->string('nip')->unique();
            $table->string('jabatan')->nullable();
            $table->foreignId('sekolah_id')->constrained('sekolahs')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
```

---

### 📄 FILE 5: `2026_04_02_023218_create_log_aktivitas_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Log histori aktivitas pengguna di dalam aplikasi.
     */
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->timestamp('waktu')->useCurrent();
            $table->string('tipe_icon', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
```

---

### 📄 FILE 6: `2026_04_02_023219_create_riwayat_status_gurus_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Log riwayat perubahan status mengajar guru.
     */
    public function up(): void
    {
        Schema::create('riwayat_status_gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')
                  ->references('user_id')->on('gurus')
                  ->onDelete('cascade');
            $table->string('status', 50);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_perubahan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_status_gurus');
    }
};
```

---

### 📄 FILE 7: `2026_04_07_000000_create_tahun_ajarans_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel periode tahun ajaran aktif dan semester.
     */
    public function up(): void
    {
        Schema::create('tahun_ajarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun_mulai');
            $table->unsignedSmallInteger('tahun_selesai');
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['tahun_mulai', 'tahun_selesai', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_ajarans');
    }
};
```

---

### 📄 FILE 8: `2026_04_07_000001_create_kelas_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel rombongan belajar kelas yang terikat tahun ajaran.
     */
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 50);
            $table->string('tingkat', 10);
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('restrict');
            $table->unsignedBigInteger('wali_guru_id')->nullable();
            $table->timestamps();

            $table->foreign('wali_guru_id')
                  ->references('user_id')->on('gurus')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
```

---

### 📄 FILE 9: `2026_04_07_000002_create_siswas_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel biodata murid.
     */
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20)->unique();
            $table->string('nis', 20)->unique()->nullable();
            $table->string('nama_siswa');
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->string('jabatan_kelas', 50)->nullable();
            $table->foreignId('sekolah_id')->constrained('sekolahs')->onDelete('restrict');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
```

---

### 📄 FILE 10: `2026_04_07_000003_create_riwayat_status_siswas_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Log riwayat mutasi / perubahan status siswa.
     */
    public function up(): void
    {
        Schema::create('riwayat_status_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('status', 50);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_perubahan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_status_siswas');
    }
};
```

---

### 📄 FILE 11: `2026_04_07_000004_create_mata_pelajarans_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel daftar mata pelajaran.
     */
    public function up(): void
    {
        Schema::create('mata_pelajarans', function (Blueprint $table) {
            $table->string('kode_mapel', 20)->primary();
            $table->string('nama_mapel', 100);
            $table->unsignedTinyInteger('kkm')->default(70);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_pelajarans');
    }
};
```

---

### 📄 FILE 12: `2026_04_07_000005_create_aturan_penilaians_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Komponen penilaian beserta bobot persentasenya per mapel.
     */
    public function up(): void
    {
        Schema::create('aturan_penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komponen', 100);
            $table->decimal('bobot', 5, 2);
            $table->string('mapel_id', 20);
            $table->timestamps();

            $table->foreign('mapel_id')
                  ->references('kode_mapel')->on('mata_pelajarans')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan_penilaians');
    }
};
```

---

### 📄 FILE 13: `2026_04_07_000006_create_guru_pengampus_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot 3-arah: Guru mengajar Mapel di Kelas tertentu.
     */
    public function up(): void
    {
        Schema::create('guru_pengampus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guru_id');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('mapel_id', 20);
            $table->timestamps();

            $table->unique(['guru_id', 'kelas_id', 'mapel_id']);

            $table->foreign('guru_id')
                  ->references('user_id')->on('gurus')
                  ->onDelete('cascade');

            $table->foreign('mapel_id')
                  ->references('kode_mapel')->on('mata_pelajarans')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_pengampus');
    }
};
```

---

### 📄 FILE 14: `2026_04_07_000007_create_raports_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel induk dokumen cetak raport per siswa per semester.
     */
    public function up(): void
    {
        Schema::create('raports', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_cetak')->nullable();
            $table->text('catatan_wali')->nullable();
            $table->enum('status_kenaikan', ['Naik', 'Tinggal', 'Lulus'])->nullable();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('restrict');
            $table->timestamps();

            $table->unique(['siswa_id', 'tahun_ajaran_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raports');
    }
};
```

---

### 📄 FILE 15: `2026_04_07_000008_create_nilais_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel nilai siswa per mata pelajaran per semester.
     */
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->decimal('nilai_uh', 5, 2)->nullable();
            $table->decimal('nilai_uts', 5, 2)->nullable();
            $table->decimal('nilai_uas', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('guru_pengampu_id')->constrained('guru_pengampus')->onDelete('cascade');
            $table->foreignId('raport_id')->constrained('raports')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
```

---

### 📄 FILE 16: `2026_04_07_000009_create_capaian_kompetensis_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Deskripsi capaian belajar — relasi 1:1 dengan nilais.
     */
    public function up(): void
    {
        Schema::create('capaian_kompetensis', function (Blueprint $table) {
            $table->id();
            $table->text('deskripsi_capaian');
            $table->foreignId('nilai_id')->unique()->constrained('nilais')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capaian_kompetensis');
    }
};
```

---

### 📄 FILE 17: `2026_04_07_000010_create_rekap_kehadirans_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Entitas lemah — riwayat ketidakhadiran siswa per raport.
     */
    public function up(): void
    {
        Schema::create('rekap_kehadirans', function (Blueprint $table) {
            $table->id();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['izin', 'sakit', 'alpha'])->nullable();
            $table->foreignId('raport_id')->constrained('raports')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_kehadirans');
    }
};
```

---

### 📄 FILE 18: `2026_04_07_000011_create_nilai_sikaps_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel penilaian akhlak/perilaku siswa per raport.
     */
    public function up(): void
    {
        Schema::create('nilai_sikaps', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sikap', 100);
            $table->string('predikat', 20)->nullable();
            $table->text('deskripsi')->nullable();
            $table->foreignId('raport_id')->constrained('raports')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_sikaps');
    }
};
```

---

### 📄 FILE 19: `2026_04_07_000012_create_ekstrakurikulers_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel daftar unit ekstrakurikuler sekolah.
     */
    public function up(): void
    {
        Schema::create('ekstrakurikulers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_eskul', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ekstrakurikulers');
    }
};
```

---

### 📄 FILE 20: `2026_04_07_000013_create_raport_ekskuls_table.php` *(FILE BARU)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot M:M — catatan ekskul siswa di dalam raport.
     */
    public function up(): void
    {
        Schema::create('raport_ekskuls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raports')->onDelete('cascade');
            $table->foreignId('ekstrakurikuler_id')->constrained('ekstrakurikulers')->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raport_ekskuls');
    }
};
```

---

### 📄 FILE 21: `2026_05_21_040625_create_nilai_sikaps_table.php` *(Membuat tabel `raport_sikaps`)*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot relasi Many-to-Many antara Raport dan Sikap.
     * Satu raport dapat memiliki banyak nilai sikap (Spiritual, Sosial, dll),
     * dan satu jenis sikap dapat dicatat di banyak raport.
     */
    public function up(): void
    {
        Schema::create('raport_sikaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raports')->cascadeOnDelete();
            $table->foreignId('sikap_id')->constrained('sikaps')->cascadeOnDelete();
            $table->enum('predikat', ['A', 'B', 'C', 'D'])->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            // Satu raport hanya boleh punya satu nilai per jenis sikap
            $table->unique(['raport_id', 'sikap_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raport_sikaps');
    }
};
```
