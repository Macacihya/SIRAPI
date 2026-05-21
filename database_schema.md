# 📋 TEXT SCHEMA (Format Tabel)

## 🔵 KELOMPOK USERS & AUTENTIKASI

### users (Tabel Induk ISA)

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `nama` | VARCHAR(255) | Nama lengkap |
| `username` | VARCHAR(255) | UNIQUE |
| `email` | VARCHAR(255) | UNIQUE |
| `password` | VARCHAR(255) | Bcrypt Hash |
| `role` | ENUM('admin','guru','walikelas') | Jenis pengguna |
| `jenis_kelamin` | VARCHAR(255) | Nullable |
| `no_hp` | VARCHAR(255) | Nullable |
| `alamat` | TEXT | Nullable |
| `email_verified_at`| TIMESTAMP | Nullable |
| `remember_token` | VARCHAR(100) | Nullable |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

### admins (Tabel Anak ISA — Admin)

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `user_id` | BIGINT UNSIGNED | PK + FK → users.id (CASCADE) |
| `jabatan_admin` | VARCHAR(255) | Nullable |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

### gurus (Tabel Anak ISA — Guru & Walikelas)

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `user_id` | BIGINT UNSIGNED | PK + FK → users.id (CASCADE) |
| `nip` | VARCHAR(255) | UNIQUE |
| `jabatan` | VARCHAR(255) | Nullable (Jabatan fungsional guru) |
| `sekolah_id` | BIGINT UNSIGNED | FK → sekolahs.id |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

> ⚠️ **Catatan:** Kolom `mata_pelajaran` (string lama) di migration asli perlu dihapus karena tugasnya sudah digantikan oleh tabel `guru_pengampus`.

### log_aktivitas

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `user_id` | BIGINT UNSIGNED | FK → users.id |
| `judul` | VARCHAR(255) | Judul aktivitas |
| `deskripsi` | TEXT | Penjelasan detail |
| `waktu` | TIMESTAMP | Waktu kejadian |
| `tipe_icon` | VARCHAR(50) | Untuk ikon di tampilan |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

---

## 🔵 KELOMPOK SEKOLAH & AKADEMIK

### sekolahs

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `npsn` | VARCHAR(20) | UNIQUE |
| `nama_sekolah` | VARCHAR(255) | - |
| `alamat` | TEXT | - |
| `kode_pos` | VARCHAR(10) | Nullable |
| `telepon` | VARCHAR(20) | Nullable |
| `email` | VARCHAR(255) | Nullable |
| `logo` | VARCHAR(255) | Path/URL logo, Nullable |
| `nip_kepsek` | VARCHAR(50) | Nullable |
| `status_sekolah` | VARCHAR(50) | Contoh: Negeri / Swasta |
| `nama_kepala_sekolah`| VARCHAR(255) | Nullable |
| `bentuk_pendidikan` | VARCHAR(50) | Contoh: SD, SMP, SMA |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

> ⚠️ **Catatan:** Tabel ini perlu di-update migrasinya karena versi lama sangat minimal (hanya 3 kolom).

### tahun_ajarans

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `tahun_mulai` | SMALLINT UNSIGNED | Contoh: 2025 |
| `tahun_selesai` | SMALLINT UNSIGNED | Contoh: 2026 |
| `semester` | ENUM('Ganjil','Genap') | - |
| `is_active` | BOOLEAN | Default: false |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |
| `UNIQUE` | `(tahun_mulai, tahun_selesai, semester)` | Kombinasi unik |

### kelas

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `nama_kelas` | VARCHAR(50) | Contoh: A, B, C |
| `tingkat` | VARCHAR(10) | Contoh: 1, 2, 3 |
| `tahun_ajaran_id` | BIGINT UNSIGNED | FK → tahun_ajarans.id |
| `wali_guru_id` | BIGINT UNSIGNED | FK → gurus.user_id (Nullable) |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

### siswas

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `nisn` | VARCHAR(20) | UNIQUE |
| `nis` | VARCHAR(20) | UNIQUE, Nullable |
| `nama_siswa` | VARCHAR(255) | - |
| `tempat_lahir` | VARCHAR(100) | Nullable |
| `tgl_lahir` | DATE | Nullable |
| `jenis_kelamin` | ENUM('L','P') | Nullable |
| `alamat` | TEXT | Nullable |
| `status_aktif` | BOOLEAN | Default: true |
| `jabatan_kelas` | VARCHAR(50) | Contoh: Ketua, Anggota, Nullable |
| `sekolah_id` | BIGINT UNSIGNED | FK → sekolahs.id |
| `kelas_id` | BIGINT UNSIGNED | FK → kelas.id, Nullable |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

### riwayat_status_siswas

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `siswa_id` | BIGINT UNSIGNED | FK → siswas.id |
| `status` | VARCHAR(50) | Contoh: Aktif, Pindah, Lulus |
| `keterangan` | TEXT | Nullable |
| `tanggal_perubahan` | DATE | - |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

### riwayat_status_gurus

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `guru_id` | BIGINT UNSIGNED | FK → gurus.user_id |
| `status` | VARCHAR(50) | Contoh: Aktif, Cuti, Non-aktif |
| `keterangan` | TEXT | Nullable |
| `tanggal_perubahan` | DATE | - |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

---

## 🔵 KELOMPOK PENGAJARAN & PENILAIAN

### mata_pelajarans

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `kode_mapel` | VARCHAR(20) | Primary Key |
| `nama_mapel` | VARCHAR(100) | - |
| `kkm` | TINYINT UNSIGNED | Nilai minimum (0-100) |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

### aturan_penilaians

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `nama_komponen` | VARCHAR(100) | Contoh: Ulangan Harian, UTS |
| `bobot` | DECIMAL(5,2) | Persentase bobot nilai |
| `mapel_id` | VARCHAR(20) | FK → mata_pelajarans.kode_mapel |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

### guru_pengampus (Tabel Pivot 3-Arah)

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `guru_id` | BIGINT UNSIGNED | FK → gurus.user_id |
| `kelas_id` | BIGINT UNSIGNED | FK → kelas.id |
| `mapel_id` | VARCHAR(20) | FK → mata_pelajarans.kode_mapel |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |
| `UNIQUE` | `(guru_id, kelas_id, mapel_id)` | Tidak boleh duplikat |

### nilais

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `nilai_uh` | DECIMAL(5,2) | Nilai Ulangan Harian, Nullable |
| `nilai_uts` | DECIMAL(5,2) | Nilai UTS, Nullable |
| `nilai_uas` | DECIMAL(5,2) | Nilai UAS, Nullable |
| `nilai_akhir` | DECIMAL(5,2) | Hasil akhir perhitungan, Nullable |
| `siswa_id` | BIGINT UNSIGNED | FK → siswas.id |
| `guru_pengampu_id` | BIGINT UNSIGNED | FK → guru_pengampus.id |
| `raport_id` | BIGINT UNSIGNED | FK → raports.id |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

### capaian_kompetensis

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `deskripsi_capaian` | TEXT | Paragraf deskripsi capaian |
| `nilai_id` | BIGINT UNSIGNED | FK → nilais.id, UNIQUE (1:1) |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

---

## 🔵 KELOMPOK RAPORT

### raports

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `tanggal_cetak` | DATE | Nullable |
| `catatan_wali` | TEXT | Catatan dari wali kelas, Nullable |
| `status_kenaikan` | ENUM('Naik','Tinggal','Lulus') | Status akhir |
| `siswa_id` | BIGINT UNSIGNED | FK → siswas.id |
| `tahun_ajaran_id` | BIGINT UNSIGNED | FK → tahun_ajarans.id |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |
| `UNIQUE` | `(siswa_id, tahun_ajaran_id)` | 1 siswa = 1 raport per semester |

### rekap_kehadirans (Entitas Lemah - Relasi 1:M dengan Raport)

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `keterangan` | TEXT | Nullable |
| `status` | ENUM('izin','sakit','alpha') | Nullable |
| `raport_id` | BIGINT UNSIGNED | FK → raports.id |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

### nilai_sikaps

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `nama_sikap` | VARCHAR(100) | Contoh: Sikap Spiritual, Sosial |
| `predikat` | VARCHAR(20) | Contoh: A, B, C / SB, B, C, PB |
| `deskripsi` | TEXT | Deskripsi naratif |
| `raport_id` | BIGINT UNSIGNED | FK → raports.id |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

### ekstrakurikulers

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `nama_eskul` | VARCHAR(100) | Contoh: Pramuka, PMR |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

### raport_ekskuls (Tabel Pivot M:M Raport ↔ Ekskul)

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key |
| `raport_id` | BIGINT UNSIGNED | FK → raports.id |
| `ekstrakurikuler_id` | BIGINT UNSIGNED | Foreign Key → ekstrakurikulers.id |
| `deskripsi` | TEXT | Deskripsi keikutsertaan, Nullable |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |
