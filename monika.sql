/*
 Navicat Premium Dump SQL

 Source Server         : laragon
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : monika

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 16/02/2026 10:14:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for anomali_log
-- ----------------------------
DROP TABLE IF EXISTS `anomali_log`;
CREATE TABLE `anomali_log`  (
  `id_anomali` int NOT NULL AUTO_INCREMENT,
  `id_dokumen` int NOT NULL,
  `id_petugas_pengolahan` int NULL DEFAULT NULL COMMENT 'Role 4',
  `jenis_error` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_anomali`) USING BTREE,
  INDEX `fk_anomali_dok`(`id_dokumen` ASC) USING BTREE,
  INDEX `fk_anomali_petugas`(`id_petugas_pengolahan` ASC) USING BTREE,
  CONSTRAINT `fk_anomali_dok` FOREIGN KEY (`id_dokumen`) REFERENCES `dokumen_survei` (`id_dokumen`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_anomali_petugas` FOREIGN KEY (`id_petugas_pengolahan`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of anomali_log
-- ----------------------------

-- ----------------------------
-- Table structure for dokumen_survei
-- ----------------------------
DROP TABLE IF EXISTS `dokumen_survei`;
CREATE TABLE `dokumen_survei`  (
  `id_dokumen` int NOT NULL AUTO_INCREMENT,
  `id_kegiatan` int NOT NULL,
  `kode_wilayah` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_petugas_pendataan` int NULL DEFAULT NULL COMMENT 'Role 3',
  `processed_by` int NULL DEFAULT NULL COMMENT 'Role 4',
  `status` enum('Uploaded','Sudah Entry','Error','Valid') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Uploaded',
  `pernah_error` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal_setor` date NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`) USING BTREE,
  INDEX `fk_dok_kegiatan`(`id_kegiatan` ASC) USING BTREE,
  INDEX `fk_dok_pcl`(`id_petugas_pendataan` ASC) USING BTREE,
  INDEX `fk_dok_proc`(`processed_by` ASC) USING BTREE,
  CONSTRAINT `fk_dok_kegiatan` FOREIGN KEY (`id_kegiatan`) REFERENCES `master_kegiatan` (`id_kegiatan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dok_pcl` FOREIGN KEY (`id_petugas_pendataan`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_dok_proc` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dokumen_survei
-- ----------------------------

-- ----------------------------
-- Table structure for kartu_kendali
-- ----------------------------
DROP TABLE IF EXISTS `kartu_kendali`;
CREATE TABLE `kartu_kendali`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nks` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_ruta` tinyint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `status_entry` enum('Clean','Error') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `is_patch_issue` tinyint(1) NOT NULL DEFAULT 0,
  `tgl_entry` date NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_nks_ruta`(`nks` ASC, `no_ruta` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kartu_kendali
-- ----------------------------

-- ----------------------------
-- Table structure for logistik
-- ----------------------------
DROP TABLE IF EXISTS `logistik`;
CREATE TABLE `logistik`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_barang` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `satuan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `stok` int NOT NULL DEFAULT 0,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Baik',
  `lokasi` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode_barang`(`kode_barang` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of logistik
-- ----------------------------

-- ----------------------------
-- Table structure for master_kegiatan
-- ----------------------------
DROP TABLE IF EXISTS `master_kegiatan`;
CREATE TABLE `master_kegiatan`  (
  `id_kegiatan` int NOT NULL AUTO_INCREMENT,
  `nama_kegiatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kode_kegiatan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` enum('Aktif','Selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kegiatan`) USING BTREE,
  UNIQUE INDEX `kode_kegiatan`(`kode_kegiatan` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of master_kegiatan
-- ----------------------------
INSERT INTO `master_kegiatan` VALUES (1, 'Sakernas Februari 2026', 'SAK26FEB', '2026-02-01', '2026-02-28', 'Aktif', '2026-02-16 01:20:24');
INSERT INTO `master_kegiatan` VALUES (2, 'Susenas Maret 2026', 'SSN26MAR', '2026-03-01', '2026-03-31', 'Aktif', '2026-02-16 01:20:24');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2026-02-15-151500', 'App\\Database\\Migrations\\CreatePresensiTable', 'default', 'App', 1771169314, 1);
INSERT INTO `migrations` VALUES (2, '2026-02-15-161000', 'App\\Database\\Migrations\\EnsureUsersAuthColumns', 'default', 'App', 1771170569, 2);
INSERT INTO `migrations` VALUES (3, '2026-02-15-164058', 'App\\Database\\Migrations\\CreateKartuKendaliTable', 'default', 'App', 1771174190, 3);
INSERT INTO `migrations` VALUES (4, '2026-02-15-170528', 'App\\Database\\Migrations\\CreateUjiPetikTable', 'default', 'App', 1771175281, 4);
INSERT INTO `migrations` VALUES (5, '2026-02-15-173500', 'App\\Database\\Migrations\\CreateLogistikTable', 'default', 'App', 1771178219, 5);

-- ----------------------------
-- Table structure for nks_master
-- ----------------------------
DROP TABLE IF EXISTS `nks_master`;
CREATE TABLE `nks_master`  (
  `nks` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kd_bs` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kecamatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `desa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `target_ruta` int NULL DEFAULT 10,
  PRIMARY KEY (`nks`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of nks_master
-- ----------------------------
INSERT INTO `nks_master` VALUES ('260001', NULL, 'Kaliwates', 'Tegal Besar', 10);
INSERT INTO `nks_master` VALUES ('260002', NULL, 'Patrang', 'Jember Lor', 10);
INSERT INTO `nks_master` VALUES ('260003', NULL, 'Sumbersari', 'Kebonsari', 10);
INSERT INTO `nks_master` VALUES ('26001', '001', 'Kencong', 'Kencong', 10);
INSERT INTO `nks_master` VALUES ('26002', '002', 'Kencong', 'Paseban', 10);
INSERT INTO `nks_master` VALUES ('26003', '003', 'Gumukmas', 'Karangharjo', 10);

-- ----------------------------
-- Table structure for presensi
-- ----------------------------
DROP TABLE IF EXISTS `presensi`;
CREATE TABLE `presensi`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tgl` date NOT NULL,
  `jam_masuk` time NULL DEFAULT NULL,
  `jam_pulang` time NULL DEFAULT NULL,
  `foto_masuk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `foto_pulang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `lokasi_masuk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `lokasi_pulang` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_user_date`(`user_id` ASC, `tgl` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of presensi
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id_role`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'Administrator', 'Super user with full access');
INSERT INTO `roles` VALUES (3, 'Petugas Pendataan (PCL)', 'Mitra Lapangan - Field Enumerator');
INSERT INTO `roles` VALUES (4, 'Petugas Pengolahan', 'Mitra Entry/Editing - Data Processor');
INSERT INTO `roles` VALUES (5, 'Pengawas Lapangan (PML)', 'Field Supervisor');
INSERT INTO `roles` VALUES (6, 'Pengawas Pengolahan', 'Processing Supervisor');

-- ----------------------------
-- Table structure for tanda_terima
-- ----------------------------
DROP TABLE IF EXISTS `tanda_terima`;
CREATE TABLE `tanda_terima`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nks` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `jml_ruta_terima` int NULL DEFAULT NULL,
  `tgl_terima` date NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tanda_terima
-- ----------------------------
INSERT INTO `tanda_terima` VALUES (1, '260001', 10, '2026-02-14', '2026-02-15 21:25:37');
INSERT INTO `tanda_terima` VALUES (2, '260002', 4, '2026-02-15', '2026-02-15 21:25:37');
INSERT INTO `tanda_terima` VALUES (3, '260001', 10, '2026-02-14', '2026-02-15 21:31:45');
INSERT INTO `tanda_terima` VALUES (4, '260002', 4, '2026-02-15', '2026-02-15 21:31:45');
INSERT INTO `tanda_terima` VALUES (5, '26001', 10, '2026-02-15', '2026-02-15 23:51:36');
INSERT INTO `tanda_terima` VALUES (6, '26002', 6, '2026-02-15', '2026-02-15 23:51:36');
INSERT INTO `tanda_terima` VALUES (7, '26003', 4, '2026-02-15', '2026-02-15 23:51:36');

-- ----------------------------
-- Table structure for uji_petik
-- ----------------------------
DROP TABLE IF EXISTS `uji_petik`;
CREATE TABLE `uji_petik`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nks` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_ruta` int NOT NULL,
  `variabel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isian_k` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Isian Dokumen Fisik',
  `isian_c` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Isian Komputer',
  `alasan_kesalahan` enum('Salah Ketik','Salah Baca','Terlewat','Salah Kode','Lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `nks`(`nks` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uji_petik
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nik_ktp` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `sobat_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_role` int NOT NULL,
  `id_supervisor` int NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE,
  INDEX `fk_user_role`(`id_role` ASC) USING BTREE,
  INDEX `fk_user_supervisor`(`id_supervisor` ASC) USING BTREE,
  CONSTRAINT `fk_user_role` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_supervisor` FOREIGN KEY (`id_supervisor`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Administrator', 'admin', 'admin@monika.local', '$2y$10$2NQx.WubMuShjmGCzJBd8OqJMME7kTCIPe2STz4uhoVQd1V7ZY/1q', NULL, NULL, 1, NULL, 1, '2026-02-16 01:20:25', '2026-02-16 01:20:25');

SET FOREIGN_KEY_CHECKS = 1;
