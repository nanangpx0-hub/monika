-- Fix script: Recreate missing tables for Monika
-- This drops dependent tables and recreates everything properly

SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables that reference users (they'll be recreated by migrations)
DROP TABLE IF EXISTS `presensi`;
DROP TABLE IF EXISTS `kartu_kendali`;
DROP TABLE IF EXISTS `target_petugas`;
DROP TABLE IF EXISTS `anomali_log`;
DROP TABLE IF EXISTS `dokumen_survei`;
DROP TABLE IF EXISTS `users`;

-- 1. Recreate users
CREATE TABLE `users` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `fullname` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nik_ktp` VARCHAR(16) DEFAULT NULL,
  `sobat_id` VARCHAR(50) DEFAULT NULL,
  `id_role` INT(11) NOT NULL,
  `id_supervisor` INT(11) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  KEY `fk_user_role` (`id_role`),
  KEY `fk_user_supervisor` (`id_supervisor`),
  CONSTRAINT `fk_user_role` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_supervisor` FOREIGN KEY (`id_supervisor`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Recreate master_kegiatan
DROP TABLE IF EXISTS `master_kegiatan`;
CREATE TABLE `master_kegiatan` (
  `id_kegiatan` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_kegiatan` VARCHAR(100) NOT NULL,
  `kode_kegiatan` VARCHAR(20) NOT NULL UNIQUE,
  `tanggal_mulai` DATE NOT NULL,
  `tanggal_selesai` DATE NOT NULL,
  `status` ENUM('Aktif', 'Selesai') NOT NULL DEFAULT 'Aktif',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kegiatan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed kegiatan
INSERT INTO `master_kegiatan` (`nama_kegiatan`, `kode_kegiatan`, `tanggal_mulai`, `tanggal_selesai`, `status`) VALUES
('Sakernas Februari 2026', 'SAK26FEB', '2026-02-01', '2026-02-28', 'Aktif'),
('Susenas Maret 2026', 'SSN26MAR', '2026-03-01', '2026-03-31', 'Aktif');

-- 3. Recreate dokumen_survei
CREATE TABLE `dokumen_survei` (
  `id_dokumen` INT(11) NOT NULL AUTO_INCREMENT,
  `id_kegiatan` INT(11) NOT NULL,
  `kode_wilayah` VARCHAR(20) NOT NULL,
  `id_petugas_pendataan` INT(11) DEFAULT NULL COMMENT 'Role 3',
  `processed_by` INT(11) DEFAULT NULL COMMENT 'Role 4',
  `status` ENUM('Uploaded', 'Sudah Entry', 'Error', 'Valid') NOT NULL DEFAULT 'Uploaded',
  `pernah_error` TINYINT(1) NOT NULL DEFAULT 0,
  `tanggal_setor` DATE DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`),
  KEY `fk_dok_kegiatan` (`id_kegiatan`),
  KEY `fk_dok_pcl` (`id_petugas_pendataan`),
  KEY `fk_dok_proc` (`processed_by`),
  CONSTRAINT `fk_dok_kegiatan` FOREIGN KEY (`id_kegiatan`) REFERENCES `master_kegiatan` (`id_kegiatan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dok_pcl` FOREIGN KEY (`id_petugas_pendataan`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_dok_proc` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Recreate anomali_log
CREATE TABLE `anomali_log` (
  `id_anomali` INT(11) NOT NULL AUTO_INCREMENT,
  `id_dokumen` INT(11) NOT NULL,
  `id_petugas_pengolahan` INT(11) DEFAULT NULL COMMENT 'Role 4',
  `jenis_error` VARCHAR(100) NOT NULL,
  `keterangan` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_anomali`),
  KEY `fk_anomali_dok` (`id_dokumen`),
  KEY `fk_anomali_petugas` (`id_petugas_pengolahan`),
  CONSTRAINT `fk_anomali_dok` FOREIGN KEY (`id_dokumen`) REFERENCES `dokumen_survei` (`id_dokumen`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_anomali_petugas` FOREIGN KEY (`id_petugas_pengolahan`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Recreate presensi (from migration)
CREATE TABLE `presensi` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `tgl` DATE NOT NULL,
  `jam_masuk` TIME DEFAULT NULL,
  `jam_pulang` TIME DEFAULT NULL,
  `foto_masuk` VARCHAR(255) DEFAULT NULL,
  `foto_pulang` VARCHAR(255) DEFAULT NULL,
  `lokasi_masuk` TEXT DEFAULT NULL,
  `lokasi_pulang` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_date` (`user_id`, `tgl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Recreate kartu_kendali (from migration)
CREATE TABLE `kartu_kendali` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nks` VARCHAR(10) NOT NULL,
  `no_ruta` TINYINT UNSIGNED NOT NULL,
  `user_id` INT(11) NOT NULL,
  `status_entry` ENUM('Clean', 'Error') NOT NULL,
  `is_patch_issue` TINYINT(1) NOT NULL DEFAULT 0,
  `tgl_entry` DATE NOT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nks_ruta` (`nks`, `no_ruta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Seed admin user
INSERT INTO `users` (`fullname`, `username`, `email`, `password`, `id_role`, `is_active`) VALUES
('Administrator', 'admin', 'admin@monika.local', '$2y$10$2NQx.WubMuShjmGCzJBd8OqJMME7kTCIPe2STz4uhoVQd1V7ZY/1q', 1, 1);
-- Password: Monika@2026!

SET FOREIGN_KEY_CHECKS = 1;
