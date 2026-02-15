-- Database: monika
-- Created: 2026-01-27
-- Description: Full schema for MONIKA (Monitoring Nilai Kinerja & Anomali)

SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- 1. Table: roles
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id_role` INT(11) NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(50) NOT NULL,
  `description` TEXT,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data for roles
INSERT INTO `roles` (`id_role`, `role_name`, `description`) VALUES
(1, 'Administrator', 'Super user with full access'),
(3, 'Petugas Pendataan (PCL)', 'Mitra Lapangan - Field Enumerator'),
(4, 'Petugas Pengolahan', 'Mitra Entry/Editing - Data Processor'),
(5, 'Pengawas Lapangan (PML)', 'Field Supervisor'),
(6, 'Pengawas Pengolahan', 'Processing Supervisor');

-- -----------------------------------------------------------------------------
-- 2. Table: users
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
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

-- -----------------------------------------------------------------------------
-- 3. Table: master_kegiatan
-- -----------------------------------------------------------------------------
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

-- Seed Data for master_kegiatan (Sample)
INSERT INTO `master_kegiatan` (`nama_kegiatan`, `kode_kegiatan`, `tanggal_mulai`, `tanggal_selesai`, `status`) VALUES
('Sakernas Februari 2026', 'SAK26FEB', '2026-02-01', '2026-02-28', 'Aktif'),
('Susenas Maret 2026', 'SSN26MAR', '2026-03-01', '2026-03-31', 'Aktif');

-- -----------------------------------------------------------------------------
-- 4. Table: dokumen_survei
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `dokumen_survei`;
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

-- -----------------------------------------------------------------------------
-- 5. Table: anomali_log
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `anomali_log`;
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

SET FOREIGN_KEY_CHECKS = 1;
