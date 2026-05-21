-- ============================================================
-- DATABASE: SIRAPI-2 (Sistem Informasi Raport API)
-- Dibuat: 2026
-- Deskripsi: Perintah SQL DDL lengkap untuk membuat
--            seluruh struktur tabel database SIRAPI-2.
-- Urutan pembuatan tabel sudah disesuaikan dengan
-- dependensi Foreign Key (tabel induk dibuat lebih dulu).
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. TABEL: users (Tabel Induk ISA)
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama`              VARCHAR(255) NOT NULL,
    `username`          VARCHAR(255) NOT NULL,
    `email`             VARCHAR(255) NOT NULL,
    `password`          VARCHAR(255) NOT NULL,
    `role`              ENUM('admin', 'guru', 'walikelas') NOT NULL,
    `jenis_kelamin`     VARCHAR(255) NULL,
    `no_hp`             VARCHAR(255) NULL,
    `alamat`            TEXT NULL,
    `email_verified_at` TIMESTAMP NULL,
    `remember_token`    VARCHAR(100) NULL,
    `created_at`        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_username_unique` (`username`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. TABEL: admins (Tabel Anak ISA — Admin)
-- ============================================================
CREATE TABLE IF NOT EXISTS `admins` (
    `user_id`       BIGINT UNSIGNED NOT NULL,
    `jabatan_admin` VARCHAR(255) NULL,
    `created_at`    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    CONSTRAINT `fk_admins_user_id`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. TABEL: sekolahs
-- ============================================================
CREATE TABLE IF NOT EXISTS `sekolahs` (
    `id`                    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `npsn`                  VARCHAR(20) NOT NULL,
    `nama_sekolah`          VARCHAR(255) NOT NULL,
    `alamat`                TEXT NOT NULL,
    `kode_pos`              VARCHAR(10) NULL,
    `telepon`               VARCHAR(20) NULL,
    `email`                 VARCHAR(255) NULL,
    `logo`                  VARCHAR(255) NULL,
    `nip_kepsek`            VARCHAR(50) NULL,
    `status_sekolah`        VARCHAR(50) NULL,
    `nama_kepala_sekolah`   VARCHAR(255) NULL,
    `bentuk_pendidikan`     VARCHAR(50) NULL,
    `created_at`            TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`            TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `sekolahs_npsn_unique` (`npsn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. TABEL: gurus (Tabel Anak ISA — Guru & Walikelas)
-- ============================================================
CREATE TABLE IF NOT EXISTS `gurus` (
    `user_id`    BIGINT UNSIGNED NOT NULL,
    `nip`        VARCHAR(255) NOT NULL,
    `jabatan`    VARCHAR(255) NULL,
    `sekolah_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `gurus_nip_unique` (`nip`),
    CONSTRAINT `fk_gurus_user_id`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_gurus_sekolah_id`
        FOREIGN KEY (`sekolah_id`) REFERENCES `sekolahs` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. TABEL: log_aktivitas
-- ============================================================
CREATE TABLE IF NOT EXISTS `log_aktivitas` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    BIGINT UNSIGNED NOT NULL,
    `judul`      VARCHAR(255) NOT NULL,
    `deskripsi`  TEXT NULL,
    `waktu`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `tipe_icon`  VARCHAR(50) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_log_user_id`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. TABEL: riwayat_status_gurus
-- ============================================================
CREATE TABLE IF NOT EXISTS `riwayat_status_gurus` (
    `id`                 BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `guru_id`            BIGINT UNSIGNED NOT NULL,
    `status`             VARCHAR(50) NOT NULL,
    `keterangan`         TEXT NULL,
    `tanggal_perubahan`  DATE NOT NULL,
    `created_at`         TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_riwayat_guru_id`
        FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. TABEL: tahun_ajarans
-- ============================================================
CREATE TABLE IF NOT EXISTS `tahun_ajarans` (
    `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tahun_mulai`   SMALLINT UNSIGNED NOT NULL,
    `tahun_selesai` SMALLINT UNSIGNED NOT NULL,
    `semester`      ENUM('Ganjil', 'Genap') NOT NULL,
    `is_active`     TINYINT(1) NOT NULL DEFAULT 0,
    `created_at`    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `tahun_ajarans_unik` (`tahun_mulai`, `tahun_selesai`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 8. TABEL: kelas
-- ============================================================
CREATE TABLE IF NOT EXISTS `kelas` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama_kelas`      VARCHAR(50) NOT NULL,
    `tingkat`         VARCHAR(10) NOT NULL,
    `tahun_ajaran_id` BIGINT UNSIGNED NOT NULL,
    `wali_guru_id`    BIGINT UNSIGNED NULL,
    `created_at`      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_kelas_tahun_ajaran_id`
        FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_kelas_wali_guru_id`
        FOREIGN KEY (`wali_guru_id`) REFERENCES `gurus` (`user_id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 9. TABEL: siswas
-- ============================================================
CREATE TABLE IF NOT EXISTS `siswas` (
    `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nisn`          VARCHAR(20) NOT NULL,
    `nis`           VARCHAR(20) NULL,
    `nama_siswa`    VARCHAR(255) NOT NULL,
    `tempat_lahir`  VARCHAR(100) NULL,
    `tgl_lahir`     DATE NULL,
    `jenis_kelamin` ENUM('L', 'P') NULL,
    `alamat`        TEXT NULL,
    `status_aktif`  TINYINT(1) NOT NULL DEFAULT 1,
    `jabatan_kelas` VARCHAR(50) NULL,
    `sekolah_id`    BIGINT UNSIGNED NOT NULL,
    `kelas_id`      BIGINT UNSIGNED NULL,
    `created_at`    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `siswas_nisn_unique` (`nisn`),
    UNIQUE KEY `siswas_nis_unique` (`nis`),
    CONSTRAINT `fk_siswas_sekolah_id`
        FOREIGN KEY (`sekolah_id`) REFERENCES `sekolahs` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_siswas_kelas_id`
        FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10. TABEL: riwayat_status_siswas
-- ============================================================
CREATE TABLE IF NOT EXISTS `riwayat_status_siswas` (
    `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `siswa_id`          BIGINT UNSIGNED NOT NULL,
    `status`            VARCHAR(50) NOT NULL,
    `keterangan`        TEXT NULL,
    `tanggal_perubahan` DATE NOT NULL,
    `created_at`        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_riwayat_siswa_id`
        FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 11. TABEL: mata_pelajarans
-- ============================================================
CREATE TABLE IF NOT EXISTS `mata_pelajarans` (
    `kode_mapel` VARCHAR(20) NOT NULL,
    `nama_mapel` VARCHAR(100) NOT NULL,
    `kkm`        TINYINT UNSIGNED NOT NULL DEFAULT 70,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`kode_mapel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 12. TABEL: aturan_penilaians
-- ============================================================
CREATE TABLE IF NOT EXISTS `aturan_penilaians` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama_komponen`  VARCHAR(100) NOT NULL,
    `bobot`          DECIMAL(5,2) NOT NULL,
    `mapel_id`       VARCHAR(20) NOT NULL,
    `created_at`     TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_aturan_mapel_id`
        FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajarans` (`kode_mapel`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 13. TABEL: guru_pengampus (Pivot 3-Arah)
-- ============================================================
CREATE TABLE IF NOT EXISTS `guru_pengampus` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `guru_id`    BIGINT UNSIGNED NOT NULL,
    `kelas_id`   BIGINT UNSIGNED NOT NULL,
    `mapel_id`   VARCHAR(20) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `guru_pengampus_unik` (`guru_id`, `kelas_id`, `mapel_id`),
    CONSTRAINT `fk_pengampu_guru_id`
        FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_pengampu_kelas_id`
        FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_pengampu_mapel_id`
        FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajarans` (`kode_mapel`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 14. TABEL: raports
-- ============================================================
CREATE TABLE IF NOT EXISTS `raports` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tanggal_cetak`   DATE NULL,
    `catatan_wali`    TEXT NULL,
    `status_kenaikan` ENUM('Naik', 'Tinggal', 'Lulus') NULL,
    `siswa_id`        BIGINT UNSIGNED NOT NULL,
    `tahun_ajaran_id` BIGINT UNSIGNED NOT NULL,
    `created_at`      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `raports_siswa_tahun_unik` (`siswa_id`, `tahun_ajaran_id`),
    CONSTRAINT `fk_raports_siswa_id`
        FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_raports_tahun_ajaran_id`
        FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 15. TABEL: nilais
-- ============================================================
CREATE TABLE IF NOT EXISTS `nilais` (
    `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nilai_uh`          DECIMAL(5,2) NULL,
    `nilai_uts`         DECIMAL(5,2) NULL,
    `nilai_uas`         DECIMAL(5,2) NULL,
    `nilai_akhir`       DECIMAL(5,2) NULL,
    `siswa_id`          BIGINT UNSIGNED NOT NULL,
    `guru_pengampu_id`  BIGINT UNSIGNED NOT NULL,
    `raport_id`         BIGINT UNSIGNED NOT NULL,
    `created_at`        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_nilais_siswa_id`
        FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_nilais_pengampu_id`
        FOREIGN KEY (`guru_pengampu_id`) REFERENCES `guru_pengampus` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_nilais_raport_id`
        FOREIGN KEY (`raport_id`) REFERENCES `raports` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 16. TABEL: capaian_kompetensis (Relasi 1:1 dengan nilais)
-- ============================================================
CREATE TABLE IF NOT EXISTS `capaian_kompetensis` (
    `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `deskripsi_capaian` TEXT NOT NULL,
    `nilai_id`          BIGINT UNSIGNED NOT NULL,
    `created_at`        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `capaian_nilai_id_unique` (`nilai_id`),
    CONSTRAINT `fk_capaian_nilai_id`
        FOREIGN KEY (`nilai_id`) REFERENCES `nilais` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 17. TABEL: rekap_kehadirans (Entitas Lemah - 1:M dengan raports)
-- ============================================================
CREATE TABLE IF NOT EXISTS `rekap_kehadirans` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `keterangan` TEXT NULL,
    `status`     ENUM('izin', 'sakit', 'alpha') NULL,
    `raport_id`  BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_kehadiran_raport_id`
        FOREIGN KEY (`raport_id`) REFERENCES `raports` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 18. TABEL: nilai_sikaps
-- ============================================================
CREATE TABLE IF NOT EXISTS `nilai_sikaps` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama_sikap` VARCHAR(100) NOT NULL,
    `predikat`   VARCHAR(20) NULL,
    `deskripsi`  TEXT NULL,
    `raport_id`  BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_sikap_raport_id`
        FOREIGN KEY (`raport_id`) REFERENCES `raports` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 19. TABEL: ekstrakurikulers
-- ============================================================
CREATE TABLE IF NOT EXISTS `ekstrakurikulers` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama_eskul` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 20. TABEL: raport_ekskuls (Pivot M:M Raport ↔ Ekskul)
-- ============================================================
CREATE TABLE IF NOT EXISTS `raport_ekskuls` (
    `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `raport_id`           BIGINT UNSIGNED NOT NULL,
    `ekstrakurikuler_id`  BIGINT UNSIGNED NOT NULL,
    `deskripsi`           TEXT NULL,
    `created_at`          TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`          TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_ekskul_raport_id`
        FOREIGN KEY (`raport_id`) REFERENCES `raports` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_ekskul_ekstrakurikuler_id`
        FOREIGN KEY (`ekstrakurikuler_id`) REFERENCES `ekstrakurikulers` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Selesai. Semua 20 tabel berhasil dibuat.
-- ============================================================

SET FOREIGN_KEY_CHECKS = 1;
