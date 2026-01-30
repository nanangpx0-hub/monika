/*
 Navicat Premium Dump SQL

 Source Server         : Laragon
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : monika

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 30/01/2026 16:47:11
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
) ENGINE = InnoDB AUTO_INCREMENT = 201 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of anomali_log
-- ----------------------------
INSERT INTO `anomali_log` VALUES (1, 144, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 1', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (2, 305, 7, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 2', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (3, 297, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 3', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (4, 322, 7, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 4', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (5, 23, 7, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 5', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (6, 171, 7, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 6', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (7, 471, 3, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 7', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (8, 31, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 8', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (9, 233, 7, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 9', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (10, 48, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 10', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (11, 31, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 11', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (12, 385, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 12', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (13, 198, 7, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 13', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (14, 291, 3, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 14', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (15, 287, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 15', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (16, 337, 7, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 16', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (17, 41, 7, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 17', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (18, 469, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 18', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (19, 470, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 19', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (20, 362, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 20', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (21, 347, 7, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 21', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (22, 431, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 22', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (23, 192, 7, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 23', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (24, 74, 7, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 24', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (25, 220, 3, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 25', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (26, 42, 3, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 26', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (27, 410, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 27', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (28, 33, 3, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 28', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (29, 135, 7, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 29', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (30, 209, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 30', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (31, 30, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 31', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (32, 409, 3, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 32', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (33, 267, 3, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 33', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (34, 310, 7, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 34', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (35, 21, 3, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 35', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (36, 230, 3, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 36', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (37, 29, 7, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 37', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (38, 405, 3, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 38', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (39, 137, 7, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 39', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (40, 171, 7, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 40', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (41, 268, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 41', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (42, 475, 7, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 42', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (43, 358, 3, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 43', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (44, 184, 7, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 44', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (45, 83, 7, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 45', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (46, 416, 7, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 46', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (47, 106, 3, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 47', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (48, 399, 7, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 48', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (49, 226, 3, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 49', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (50, 142, 7, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 50', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (51, 98, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 51', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (52, 469, 3, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 52', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (53, 9, 3, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 53', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (54, 407, 7, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 54', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (55, 61, 7, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 55', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (56, 266, 7, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 56', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (57, 34, 7, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 57', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (58, 359, 7, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 58', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (59, 256, 7, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 59', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (60, 23, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 60', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (61, 456, 3, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 61', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (62, 285, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 62', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (63, 43, 3, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 63', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (64, 204, 7, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 64', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (65, 349, 7, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 65', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (66, 161, 7, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 66', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (67, 326, 7, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 67', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (68, 183, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 68', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (69, 16, 3, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 69', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (70, 289, 7, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 70', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (71, 174, 7, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 71', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (72, 351, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 72', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (73, 101, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 73', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (74, 147, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 74', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (75, 67, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 75', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (76, 260, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 76', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (77, 270, 7, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 77', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (78, 494, 7, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 78', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (79, 235, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 79', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (80, 264, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 80', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (81, 331, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 81', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (82, 54, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 82', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (83, 96, 7, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 83', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (84, 229, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 84', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (85, 454, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 85', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (86, 229, 7, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 86', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (87, 115, 7, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 87', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (88, 247, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 88', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (89, 359, 3, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 89', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (90, 337, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 90', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (91, 48, 3, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 91', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (92, 491, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 92', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (93, 25, 7, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 93', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (94, 100, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 94', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (95, 156, 3, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 95', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (96, 351, 7, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 96', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (97, 294, 3, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 97', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (98, 256, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 98', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (99, 123, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 99', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (100, 104, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 100', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (101, 478, 3, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 101', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (102, 402, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 102', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (103, 1, 3, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 103', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (104, 359, 7, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 104', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (105, 170, 7, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 105', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (106, 353, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 106', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (107, 114, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 107', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (108, 461, 7, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 108', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (109, 101, 3, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 109', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (110, 185, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 110', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (111, 81, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 111', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (112, 21, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 112', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (113, 206, 3, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 113', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (114, 268, 7, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 114', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (115, 200, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 115', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (116, 326, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 116', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (117, 209, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 117', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (118, 191, 7, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 118', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (119, 261, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 119', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (120, 402, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 120', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (121, 126, 7, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 121', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (122, 83, 3, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 122', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (123, 500, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 123', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (124, 253, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 124', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (125, 400, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 125', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (126, 189, 7, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 126', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (127, 357, 7, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 127', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (128, 132, 7, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 128', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (129, 217, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 129', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (130, 172, 7, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 130', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (131, 272, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 131', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (132, 281, 7, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 132', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (133, 348, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 133', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (134, 92, 7, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 134', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (135, 405, 7, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 135', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (136, 366, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 136', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (137, 437, 7, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 137', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (138, 365, 7, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 138', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (139, 3, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 139', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (140, 249, 3, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 140', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (141, 113, 7, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 141', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (142, 424, 7, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 142', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (143, 104, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 143', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (144, 356, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 144', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (145, 469, 3, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 145', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (146, 365, 3, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 146', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (147, 38, 3, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 147', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (148, 35, 3, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 148', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (149, 399, 7, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 149', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (150, 299, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 150', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (151, 86, 7, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 151', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (152, 359, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 152', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (153, 201, 3, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 153', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (154, 31, 3, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 154', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (155, 142, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 155', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (156, 152, 7, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 156', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (157, 411, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 157', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (158, 453, 3, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 158', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (159, 499, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 159', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (160, 57, 3, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 160', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (161, 189, 7, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 161', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (162, 465, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 162', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (163, 471, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 163', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (164, 219, 7, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 164', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (165, 241, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 165', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (166, 326, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 166', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (167, 369, 3, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 167', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (168, 105, 7, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 168', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (169, 342, 7, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 169', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (170, 275, 3, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 170', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (171, 33, 7, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 171', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (172, 378, 7, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 172', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (173, 24, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 173', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (174, 178, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 174', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (175, 200, 7, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 175', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (176, 56, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 176', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (177, 196, 7, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 177', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (178, 495, 7, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 178', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (179, 272, 3, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 179', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (180, 348, 3, 'Duplikasi entri', 'Anomali ditemukan dalam pengujian skala besar: 180', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (181, 265, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 181', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (182, 386, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 182', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (183, 15, 3, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 183', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (184, 330, 3, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 184', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (185, 267, 3, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 185', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (186, 409, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 186', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (187, 293, 7, 'Kode kegiatan salah', 'Anomali ditemukan dalam pengujian skala besar: 187', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (188, 241, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 188', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (189, 210, 7, 'Format file tidak sesuai', 'Anomali ditemukan dalam pengujian skala besar: 189', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (190, 128, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 190', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (191, 182, 3, 'Data tidak lengkap', 'Anomali ditemukan dalam pengujian skala besar: 191', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (192, 483, 7, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 192', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (193, 186, 7, 'Kesalahan input manual', 'Anomali ditemukan dalam pengujian skala besar: 193', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (194, 329, 3, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 194', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (195, 210, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 195', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (196, 56, 3, 'Wilayah tidak ditemukan', 'Anomali ditemukan dalam pengujian skala besar: 196', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (197, 296, 3, 'Nilai tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 197', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (198, 21, 3, 'NIK tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 198', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (199, 15, 7, 'Data melebihi batas wajar', 'Anomali ditemukan dalam pengujian skala besar: 199', '2026-01-29 14:17:55');
INSERT INTO `anomali_log` VALUES (200, 305, 3, 'Tanggal tidak valid', 'Anomali ditemukan dalam pengujian skala besar: 200', '2026-01-29 14:17:55');

-- ----------------------------
-- Table structure for audit_logs
-- ----------------------------
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of audit_logs
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
) ENGINE = InnoDB AUTO_INCREMENT = 501 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of dokumen_survei
-- ----------------------------
INSERT INTO `dokumen_survei` VALUES (1, 1, 'ID-001', 6, 7, 'Valid', 1, '2026-01-27', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (2, 3, 'ID-002', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (3, 4, 'ID-003', 2, 7, 'Uploaded', 1, '2026-01-22', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (4, 3, 'ID-004', 2, 7, 'Sudah Entry', 0, '2026-01-08', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (5, 3, 'ID-005', 6, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (6, 2, 'ID-006', 2, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (7, 1, 'ID-007', 6, 3, 'Error', 0, '2026-01-12', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (8, 1, 'ID-008', 6, 3, 'Error', 0, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (9, 5, 'ID-009', 6, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (10, 2, 'ID-010', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (11, 3, 'ID-011', 6, 3, 'Valid', 0, '2026-01-28', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (12, 3, 'ID-012', 6, 3, 'Valid', 1, '2026-01-03', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (13, 1, 'ID-013', 2, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (14, 4, 'ID-014', 2, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (15, 1, 'ID-015', 6, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (16, 3, 'ID-016', 2, 7, 'Sudah Entry', 0, '2026-01-04', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (17, 2, 'ID-017', 2, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (18, 4, 'ID-018', 6, 3, 'Error', 1, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (19, 5, 'ID-019', 6, 7, 'Error', 0, '2026-01-04', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (20, 5, 'ID-020', 2, 3, 'Sudah Entry', 1, '2026-01-11', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (21, 4, 'ID-021', 2, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (22, 2, 'ID-022', 2, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (23, 3, 'ID-023', 2, 7, 'Sudah Entry', 0, '2026-01-13', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (24, 5, 'ID-024', 6, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (25, 5, 'ID-025', 6, 7, 'Uploaded', 1, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (26, 1, 'ID-026', 2, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (27, 4, 'ID-027', 6, 7, 'Sudah Entry', 1, '2026-01-08', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (28, 1, 'ID-028', 2, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (29, 1, 'ID-029', 6, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (30, 2, 'ID-030', 6, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (31, 5, 'ID-031', 6, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (32, 3, 'ID-032', 6, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (33, 2, 'ID-033', 2, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (34, 5, 'ID-034', 6, 3, 'Valid', 0, '2026-01-02', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (35, 3, 'ID-035', 2, 7, 'Error', 1, '2026-01-14', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (36, 4, 'ID-036', 6, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (37, 4, 'ID-037', 2, 7, 'Valid', 0, '2026-01-07', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (38, 2, 'ID-038', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (39, 4, 'ID-039', 6, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (40, 4, 'ID-040', 2, 3, 'Uploaded', 1, '2026-01-09', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (41, 4, 'ID-041', 6, 7, 'Sudah Entry', 0, '2026-01-12', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (42, 5, 'ID-042', 2, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (43, 3, 'ID-043', 6, 7, 'Uploaded', 0, '2026-01-09', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (44, 5, 'ID-044', 2, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (45, 2, 'ID-045', 2, 7, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (46, 3, 'ID-046', 2, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (47, 3, 'ID-047', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (48, 2, 'ID-048', 2, 7, 'Sudah Entry', 0, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (49, 3, 'ID-049', 2, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (50, 3, 'ID-050', 6, 3, 'Sudah Entry', 1, '2026-01-23', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (51, 5, 'ID-051', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (52, 3, 'ID-052', 6, 7, 'Valid', 0, '2026-01-10', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (53, 5, 'ID-053', 6, 7, 'Error', 0, '2025-12-30', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (54, 4, 'ID-054', 6, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (55, 3, 'ID-055', 6, 3, 'Error', 1, '2026-01-14', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (56, 5, 'ID-056', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (57, 4, 'ID-057', 2, 3, 'Valid', 1, '2026-01-13', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (58, 1, 'ID-058', 2, 3, 'Sudah Entry', 0, '2025-12-31', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (59, 2, 'ID-059', 2, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (60, 5, 'ID-060', 6, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (61, 1, 'ID-061', 2, 7, 'Error', 0, '2026-01-13', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (62, 2, 'ID-062', 2, 7, 'Uploaded', 1, '2026-01-09', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (63, 4, 'ID-063', 2, 3, 'Valid', 0, '2026-01-09', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (64, 3, 'ID-064', 6, 7, 'Uploaded', 1, '2026-01-12', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (65, 4, 'ID-065', 2, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (66, 4, 'ID-066', 6, 3, 'Sudah Entry', 0, '2026-01-08', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (67, 5, 'ID-067', 6, 3, 'Sudah Entry', 0, '2026-01-24', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (68, 2, 'ID-068', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (69, 5, 'ID-069', 6, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (70, 4, 'ID-070', 6, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (71, 4, 'ID-071', 2, 3, 'Uploaded', 1, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (72, 3, 'ID-072', 2, 3, 'Valid', 0, '2026-01-27', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (73, 3, 'ID-073', 2, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (74, 1, 'ID-074', 2, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (75, 2, 'ID-075', 6, 3, 'Valid', 1, '2026-01-17', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (76, 5, 'ID-076', 6, 7, 'Uploaded', 1, '2026-01-03', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (77, 3, 'ID-077', 2, 3, 'Valid', 0, '2026-01-22', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (78, 5, 'ID-078', 6, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (79, 2, 'ID-079', 2, 3, 'Error', 0, '2026-01-16', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (80, 2, 'ID-080', 2, 3, 'Valid', 0, '2025-12-30', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (81, 1, 'ID-081', 6, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (82, 1, 'ID-082', 2, 3, 'Error', 1, '2026-01-15', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (83, 3, 'ID-083', 6, 7, 'Valid', 1, '2026-01-14', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (84, 2, 'ID-084', 2, 3, 'Error', 1, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (85, 1, 'ID-085', 6, 7, 'Uploaded', 0, '2025-12-31', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (86, 4, 'ID-086', 6, 7, 'Valid', 1, '2026-01-19', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (87, 5, 'ID-087', 2, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (88, 2, 'ID-088', 2, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (89, 2, 'ID-089', 6, 7, 'Error', 0, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (90, 3, 'ID-090', 2, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (91, 4, 'ID-091', 2, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (92, 3, 'ID-092', 2, 3, 'Valid', 0, '2026-01-16', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (93, 3, 'ID-093', 2, 3, 'Error', 0, '2026-01-26', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (94, 3, 'ID-094', 2, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (95, 4, 'ID-095', 6, 3, 'Sudah Entry', 0, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (96, 3, 'ID-096', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (97, 2, 'ID-097', 2, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (98, 5, 'ID-098', 6, 3, 'Uploaded', 1, '2026-01-23', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (99, 1, 'ID-099', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (100, 5, 'ID-000', 2, 3, 'Sudah Entry', 1, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (101, 3, 'ID-001', 6, 3, 'Error', 0, '2026-01-01', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (102, 3, 'ID-002', 6, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (103, 4, 'ID-003', 2, 3, 'Valid', 1, '2026-01-11', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (104, 1, 'ID-004', 6, 7, 'Valid', 1, '2025-12-30', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (105, 4, 'ID-005', 6, 3, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (106, 4, 'ID-006', 6, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (107, 4, 'ID-007', 2, 3, 'Valid', 0, '2026-01-06', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (108, 2, 'ID-008', 2, 7, 'Valid', 1, '2026-01-03', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (109, 3, 'ID-009', 6, 3, 'Uploaded', 1, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (110, 1, 'ID-010', 6, 7, 'Uploaded', 1, '2026-01-24', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (111, 1, 'ID-011', 6, 3, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (112, 5, 'ID-012', 6, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (113, 4, 'ID-013', 2, 7, 'Uploaded', 0, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (114, 4, 'ID-014', 6, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (115, 2, 'ID-015', 2, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (116, 4, 'ID-016', 2, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (117, 4, 'ID-017', 2, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (118, 2, 'ID-018', 2, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (119, 2, 'ID-019', 2, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (120, 4, 'ID-020', 2, 3, 'Error', 0, '2026-01-12', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (121, 5, 'ID-021', 2, 7, 'Uploaded', 1, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (122, 5, 'ID-022', 6, 3, 'Error', 1, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (123, 2, 'ID-023', 2, 7, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (124, 1, 'ID-024', 2, 7, 'Error', 1, '2026-01-13', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (125, 1, 'ID-025', 2, 7, 'Uploaded', 0, '2026-01-23', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (126, 5, 'ID-026', 2, 3, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (127, 1, 'ID-027', 6, 3, 'Sudah Entry', 1, '2026-01-11', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (128, 3, 'ID-028', 2, 7, 'Sudah Entry', 0, '2026-01-07', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (129, 2, 'ID-029', 6, 7, 'Uploaded', 1, '2026-01-09', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (130, 1, 'ID-030', 6, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (131, 1, 'ID-031', 2, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (132, 2, 'ID-032', 2, 7, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (133, 3, 'ID-033', 2, 3, 'Error', 0, '2026-01-28', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (134, 1, 'ID-034', 6, 7, 'Sudah Entry', 0, '2026-01-23', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (135, 3, 'ID-035', 2, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (136, 5, 'ID-036', 2, 7, 'Error', 1, '2026-01-23', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (137, 5, 'ID-037', 2, 3, 'Uploaded', 1, '2025-12-31', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (138, 5, 'ID-038', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (139, 2, 'ID-039', 6, 3, 'Valid', 1, '2026-01-10', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (140, 3, 'ID-040', 2, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (141, 5, 'ID-041', 2, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (142, 3, 'ID-042', 6, 7, 'Sudah Entry', 0, '2026-01-15', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (143, 3, 'ID-043', 2, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (144, 5, 'ID-044', 2, 3, 'Uploaded', 1, '2026-01-26', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (145, 3, 'ID-045', 6, 3, 'Uploaded', 1, '2026-01-27', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (146, 2, 'ID-046', 2, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (147, 1, 'ID-047', 6, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (148, 2, 'ID-048', 2, 3, 'Sudah Entry', 0, '2026-01-22', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (149, 2, 'ID-049', 6, 7, 'Error', 0, '2026-01-22', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (150, 1, 'ID-050', 2, 3, 'Sudah Entry', 1, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (151, 4, 'ID-051', 2, 7, 'Uploaded', 0, '2026-01-20', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (152, 2, 'ID-052', 2, 3, 'Valid', 1, '2026-01-09', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (153, 5, 'ID-053', 6, 7, 'Uploaded', 1, '2026-01-19', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (154, 3, 'ID-054', 6, 7, 'Error', 0, '2026-01-10', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (155, 1, 'ID-055', 6, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (156, 2, 'ID-056', 6, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (157, 5, 'ID-057', 6, 7, 'Uploaded', 1, '2026-01-19', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (158, 4, 'ID-058', 6, 7, 'Valid', 0, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (159, 2, 'ID-059', 2, 3, 'Valid', 0, '2026-01-08', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (160, 2, 'ID-060', 6, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (161, 3, 'ID-061', 6, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (162, 3, 'ID-062', 2, 7, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (163, 3, 'ID-063', 6, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (164, 2, 'ID-064', 2, 3, 'Uploaded', 0, '2026-01-24', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (165, 4, 'ID-065', 6, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (166, 1, 'ID-066', 6, 7, 'Uploaded', 0, '2026-01-04', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (167, 5, 'ID-067', 2, 7, 'Sudah Entry', 1, '2026-01-26', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (168, 3, 'ID-068', 6, 7, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (169, 4, 'ID-069', 6, 3, 'Valid', 0, '2026-01-07', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (170, 5, 'ID-070', 2, 3, 'Uploaded', 0, '2026-01-23', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (171, 3, 'ID-071', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (172, 5, 'ID-072', 6, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (173, 4, 'ID-073', 2, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (174, 4, 'ID-074', 6, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (175, 3, 'ID-075', 6, 7, 'Sudah Entry', 0, '2025-12-30', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (176, 3, 'ID-076', 6, 7, 'Error', 1, '2025-12-30', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (177, 5, 'ID-077', 6, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (178, 3, 'ID-078', 6, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (179, 1, 'ID-079', 2, 7, 'Valid', 0, '2026-01-20', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (180, 3, 'ID-080', 2, 3, 'Uploaded', 0, '2026-01-22', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (181, 5, 'ID-081', 6, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (182, 5, 'ID-082', 6, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (183, 4, 'ID-083', 6, 3, 'Uploaded', 0, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (184, 3, 'ID-084', 6, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (185, 3, 'ID-085', 6, 7, 'Sudah Entry', 0, '2026-01-17', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (186, 5, 'ID-086', 6, 7, 'Sudah Entry', 0, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (187, 4, 'ID-087', 6, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (188, 3, 'ID-088', 6, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (189, 2, 'ID-089', 2, 7, 'Sudah Entry', 0, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (190, 1, 'ID-090', 2, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (191, 5, 'ID-091', 2, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (192, 5, 'ID-092', 2, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (193, 3, 'ID-093', 2, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (194, 5, 'ID-094', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (195, 3, 'ID-095', 2, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (196, 4, 'ID-096', 6, 3, 'Error', 1, '2026-01-02', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (197, 3, 'ID-097', 2, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (198, 5, 'ID-098', 6, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (199, 4, 'ID-099', 2, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (200, 3, 'ID-000', 6, 7, 'Sudah Entry', 1, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (201, 2, 'ID-001', 2, 7, 'Error', 0, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (202, 5, 'ID-002', 6, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (203, 5, 'ID-003', 2, 7, 'Uploaded', 0, '2026-01-12', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (204, 3, 'ID-004', 2, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (205, 4, 'ID-005', 6, 3, 'Valid', 1, '2026-01-08', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (206, 2, 'ID-006', 2, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (207, 1, 'ID-007', 6, 7, 'Error', 0, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (208, 2, 'ID-008', 2, 7, 'Error', 0, '2026-01-02', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (209, 1, 'ID-009', 2, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (210, 4, 'ID-010', 6, 7, 'Error', 0, '2026-01-08', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (211, 1, 'ID-011', 6, 7, 'Sudah Entry', 1, '2026-01-17', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (212, 4, 'ID-012', 6, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (213, 4, 'ID-013', 2, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (214, 5, 'ID-014', 2, 7, 'Uploaded', 0, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (215, 5, 'ID-015', 6, 7, 'Uploaded', 1, '2026-01-18', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (216, 2, 'ID-016', 6, 7, 'Valid', 0, '2026-01-23', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (217, 3, 'ID-017', 6, 7, 'Error', 0, '2026-01-06', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (218, 1, 'ID-018', 6, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (219, 5, 'ID-019', 2, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (220, 2, 'ID-020', 2, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (221, 3, 'ID-021', 2, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (222, 2, 'ID-022', 6, 7, 'Uploaded', 0, '2026-01-23', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (223, 1, 'ID-023', 2, 3, 'Error', 0, '2026-01-08', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (224, 4, 'ID-024', 6, 3, 'Uploaded', 0, '2026-01-16', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (225, 3, 'ID-025', 6, 3, 'Error', 1, '2026-01-10', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (226, 5, 'ID-026', 2, 7, 'Error', 1, '2026-01-06', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (227, 1, 'ID-027', 6, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (228, 4, 'ID-028', 2, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (229, 3, 'ID-029', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (230, 1, 'ID-030', 6, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (231, 5, 'ID-031', 6, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (232, 5, 'ID-032', 2, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (233, 1, 'ID-033', 2, 7, 'Sudah Entry', 1, '2026-01-28', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (234, 2, 'ID-034', 2, 7, 'Sudah Entry', 0, '2026-01-08', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (235, 1, 'ID-035', 2, 3, 'Error', 1, '2026-01-10', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (236, 1, 'ID-036', 2, 3, 'Sudah Entry', 1, '2026-01-18', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (237, 2, 'ID-037', 6, 3, 'Valid', 0, '2026-01-06', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (238, 3, 'ID-038', 2, 7, 'Error', 1, '2026-01-13', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (239, 5, 'ID-039', 6, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (240, 4, 'ID-040', 2, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (241, 2, 'ID-041', 6, 7, 'Valid', 0, '2026-01-20', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (242, 1, 'ID-042', 6, 3, 'Uploaded', 1, '2025-12-30', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (243, 3, 'ID-043', 2, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (244, 3, 'ID-044', 6, 3, 'Error', 1, '2026-01-16', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (245, 2, 'ID-045', 6, 7, 'Valid', 1, '2026-01-03', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (246, 4, 'ID-046', 2, 7, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (247, 4, 'ID-047', 2, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (248, 4, 'ID-048', 6, 7, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (249, 3, 'ID-049', 6, 3, 'Valid', 0, '2026-01-06', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (250, 5, 'ID-050', 2, 3, 'Error', 0, '2026-01-11', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (251, 1, 'ID-051', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (252, 2, 'ID-052', 2, 3, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (253, 5, 'ID-053', 2, 3, 'Error', 1, '2026-01-12', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (254, 3, 'ID-054', 6, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (255, 5, 'ID-055', 2, 7, 'Error', 1, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (256, 1, 'ID-056', 2, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (257, 5, 'ID-057', 2, 3, 'Valid', 0, '2025-12-31', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (258, 3, 'ID-058', 2, 7, 'Error', 0, '2026-01-10', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (259, 5, 'ID-059', 6, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (260, 3, 'ID-060', 6, 7, 'Sudah Entry', 1, '2026-01-06', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (261, 1, 'ID-061', 2, 7, 'Error', 1, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (262, 2, 'ID-062', 2, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (263, 5, 'ID-063', 2, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (264, 1, 'ID-064', 6, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (265, 5, 'ID-065', 2, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (266, 2, 'ID-066', 6, 3, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (267, 3, 'ID-067', 6, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (268, 3, 'ID-068', 2, 7, 'Sudah Entry', 0, '2025-12-31', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (269, 3, 'ID-069', 2, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (270, 3, 'ID-070', 6, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (271, 3, 'ID-071', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (272, 3, 'ID-072', 6, 7, 'Valid', 0, '2026-01-18', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (273, 3, 'ID-073', 2, 3, 'Valid', 0, '2026-01-18', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (274, 2, 'ID-074', 2, 7, 'Valid', 0, '2026-01-28', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (275, 1, 'ID-075', 6, 3, 'Valid', 1, '2026-01-09', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (276, 5, 'ID-076', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (277, 4, 'ID-077', 2, 3, 'Uploaded', 1, '2026-01-24', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (278, 3, 'ID-078', 2, 3, 'Valid', 1, '2026-01-01', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (279, 1, 'ID-079', 6, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (280, 4, 'ID-080', 2, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (281, 4, 'ID-081', 6, 3, 'Valid', 0, '2026-01-18', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (282, 1, 'ID-082', 6, 3, 'Error', 1, '2026-01-12', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (283, 4, 'ID-083', 6, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (284, 3, 'ID-084', 2, 3, 'Sudah Entry', 0, '2026-01-09', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (285, 1, 'ID-085', 6, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (286, 2, 'ID-086', 2, 3, 'Valid', 1, '2026-01-04', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (287, 1, 'ID-087', 6, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (288, 4, 'ID-088', 6, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (289, 5, 'ID-089', 6, 7, 'Uploaded', 0, '2026-01-27', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (290, 1, 'ID-090', 6, 3, 'Uploaded', 0, '2026-01-08', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (291, 5, 'ID-091', 2, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (292, 1, 'ID-092', 6, 3, 'Error', 0, '2025-12-31', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (293, 1, 'ID-093', 2, 7, 'Sudah Entry', 0, '2026-01-01', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (294, 5, 'ID-094', 6, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (295, 2, 'ID-095', 6, 7, 'Error', 1, '2026-01-19', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (296, 4, 'ID-096', 2, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (297, 1, 'ID-097', 2, 7, 'Valid', 0, '2026-01-23', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (298, 2, 'ID-098', 2, 3, 'Error', 0, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (299, 3, 'ID-099', 2, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (300, 3, 'ID-000', 6, 7, 'Valid', 0, '2026-01-06', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (301, 2, 'ID-001', 6, 3, 'Uploaded', 0, '2026-01-01', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (302, 3, 'ID-002', 6, 7, 'Valid', 0, '2026-01-07', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (303, 2, 'ID-003', 6, 7, 'Error', 0, '2026-01-04', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (304, 5, 'ID-004', 6, 3, 'Error', 1, '2026-01-20', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (305, 4, 'ID-005', 2, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (306, 5, 'ID-006', 6, 3, 'Sudah Entry', 1, '2026-01-09', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (307, 4, 'ID-007', 2, 7, 'Uploaded', 0, '2026-01-09', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (308, 5, 'ID-008', 6, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (309, 4, 'ID-009', 6, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (310, 1, 'ID-010', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (311, 1, 'ID-011', 2, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (312, 4, 'ID-012', 2, 7, 'Valid', 1, '2026-01-28', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (313, 5, 'ID-013', 6, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (314, 4, 'ID-014', 2, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (315, 3, 'ID-015', 6, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (316, 5, 'ID-016', 2, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (317, 2, 'ID-017', 6, 3, 'Sudah Entry', 1, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (318, 3, 'ID-018', 6, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (319, 1, 'ID-019', 2, 7, 'Error', 1, '2026-01-10', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (320, 4, 'ID-020', 6, 7, 'Uploaded', 1, '2026-01-16', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (321, 3, 'ID-021', 2, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (322, 1, 'ID-022', 6, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (323, 3, 'ID-023', 6, 3, 'Error', 0, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (324, 2, 'ID-024', 6, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (325, 5, 'ID-025', 6, 3, 'Sudah Entry', 1, '2026-01-10', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (326, 1, 'ID-026', 2, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (327, 3, 'ID-027', 2, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (328, 5, 'ID-028', 2, 3, 'Sudah Entry', 0, '2026-01-16', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (329, 2, 'ID-029', 2, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (330, 4, 'ID-030', 6, 7, 'Valid', 0, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (331, 4, 'ID-031', 2, 3, 'Uploaded', 1, '2026-01-04', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (332, 2, 'ID-032', 6, 7, 'Error', 0, '2026-01-12', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (333, 2, 'ID-033', 2, 3, 'Uploaded', 0, '2026-01-28', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (334, 2, 'ID-034', 6, 7, 'Uploaded', 1, '2026-01-17', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (335, 2, 'ID-035', 2, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (336, 4, 'ID-036', 2, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (337, 1, 'ID-037', 2, 7, 'Valid', 1, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (338, 4, 'ID-038', 2, 7, 'Valid', 0, '2025-12-30', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (339, 3, 'ID-039', 6, 3, 'Uploaded', 0, '2026-01-01', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (340, 3, 'ID-040', 2, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (341, 3, 'ID-041', 2, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (342, 5, 'ID-042', 6, 7, 'Sudah Entry', 0, '2026-01-02', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (343, 4, 'ID-043', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (344, 4, 'ID-044', 2, 3, 'Error', 1, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (345, 1, 'ID-045', 6, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (346, 5, 'ID-046', 6, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (347, 5, 'ID-047', 2, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (348, 5, 'ID-048', 2, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (349, 3, 'ID-049', 6, 7, 'Uploaded', 1, '2025-12-31', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (350, 2, 'ID-050', 2, 7, 'Error', 1, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (351, 3, 'ID-051', 2, 3, 'Error', 1, '2025-12-31', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (352, 1, 'ID-052', 6, 7, 'Error', 0, '2026-01-07', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (353, 1, 'ID-053', 2, 7, 'Sudah Entry', 0, '2026-01-08', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (354, 5, 'ID-054', 6, 3, 'Sudah Entry', 1, '2026-01-18', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (355, 2, 'ID-055', 6, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (356, 5, 'ID-056', 6, 3, 'Valid', 1, '2026-01-15', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (357, 3, 'ID-057', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (358, 4, 'ID-058', 6, 7, 'Error', 0, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (359, 3, 'ID-059', 2, 3, 'Error', 1, '2026-01-16', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (360, 1, 'ID-060', 6, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (361, 2, 'ID-061', 6, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (362, 3, 'ID-062', 6, 3, 'Uploaded', 0, '2026-01-03', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (363, 5, 'ID-063', 2, 7, 'Sudah Entry', 0, '2026-01-27', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (364, 3, 'ID-064', 2, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (365, 4, 'ID-065', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (366, 4, 'ID-066', 6, 3, 'Uploaded', 0, '2026-01-14', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (367, 2, 'ID-067', 2, 7, 'Error', 1, '2026-01-28', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (368, 2, 'ID-068', 6, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (369, 2, 'ID-069', 2, 7, 'Valid', 1, '2026-01-04', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (370, 1, 'ID-070', 6, 3, 'Error', 0, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (371, 1, 'ID-071', 2, 7, 'Uploaded', 1, '2026-01-19', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (372, 1, 'ID-072', 6, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (373, 5, 'ID-073', 6, 3, 'Sudah Entry', 1, '2026-01-04', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (374, 4, 'ID-074', 2, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (375, 1, 'ID-075', 6, 7, 'Error', 1, '2026-01-15', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (376, 4, 'ID-076', 2, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (377, 5, 'ID-077', 2, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (378, 5, 'ID-078', 6, 3, 'Valid', 1, '2026-01-26', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (379, 3, 'ID-079', 6, 7, 'Uploaded', 1, '2026-01-14', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (380, 2, 'ID-080', 2, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (381, 1, 'ID-081', 2, 3, 'Uploaded', 0, '2026-01-03', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (382, 5, 'ID-082', 2, 3, 'Error', 0, '2026-01-03', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (383, 3, 'ID-083', 6, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (384, 3, 'ID-084', 6, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (385, 3, 'ID-085', 6, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (386, 3, 'ID-086', 2, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (387, 5, 'ID-087', 6, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (388, 2, 'ID-088', 6, 7, 'Valid', 1, '2026-01-19', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (389, 2, 'ID-089', 6, 7, 'Valid', 1, '2026-01-19', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (390, 5, 'ID-090', 6, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (391, 1, 'ID-091', 6, 7, 'Uploaded', 1, '2026-01-07', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (392, 2, 'ID-092', 2, 7, 'Sudah Entry', 0, '2026-01-05', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (393, 5, 'ID-093', 2, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (394, 5, 'ID-094', 2, 3, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (395, 1, 'ID-095', 6, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (396, 5, 'ID-096', 6, 3, 'Uploaded', 0, '2026-01-23', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (397, 1, 'ID-097', 2, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (398, 5, 'ID-098', 2, 3, 'Sudah Entry', 1, '2026-01-23', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (399, 1, 'ID-099', 6, 7, 'Valid', 1, '2026-01-15', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (400, 5, 'ID-000', 2, 3, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (401, 1, 'ID-001', 2, 3, 'Uploaded', 0, '2026-01-22', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (402, 1, 'ID-002', 2, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (403, 2, 'ID-003', 6, 7, 'Uploaded', 1, '2026-01-04', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (404, 1, 'ID-004', 2, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (405, 3, 'ID-005', 2, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (406, 5, 'ID-006', 2, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (407, 4, 'ID-007', 6, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (408, 1, 'ID-008', 2, 7, 'Error', 1, '2026-01-08', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (409, 2, 'ID-009', 6, 3, 'Uploaded', 1, '2026-01-22', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (410, 5, 'ID-010', 6, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (411, 2, 'ID-011', 2, 7, 'Sudah Entry', 1, '2026-01-11', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (412, 1, 'ID-012', 2, 7, 'Sudah Entry', 1, '2026-01-20', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (413, 1, 'ID-013', 6, 7, 'Valid', 1, '2026-01-01', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (414, 4, 'ID-014', 6, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (415, 3, 'ID-015', 2, 7, 'Sudah Entry', 0, '2026-01-03', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (416, 3, 'ID-016', 2, 7, 'Uploaded', 1, '2026-01-06', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (417, 5, 'ID-017', 6, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (418, 2, 'ID-018', 2, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (419, 2, 'ID-019', 2, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (420, 5, 'ID-020', 2, 3, 'Error', 0, '2026-01-28', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (421, 5, 'ID-021', 2, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (422, 3, 'ID-022', 6, 3, 'Uploaded', 1, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (423, 2, 'ID-023', 6, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (424, 4, 'ID-024', 2, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (425, 3, 'ID-025', 6, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (426, 5, 'ID-026', 2, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (427, 3, 'ID-027', 6, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (428, 2, 'ID-028', 2, 7, 'Sudah Entry', 1, '2026-01-04', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (429, 3, 'ID-029', 6, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (430, 3, 'ID-030', 2, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (431, 2, 'ID-031', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (432, 2, 'ID-032', 2, 7, 'Valid', 1, '2026-01-27', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (433, 1, 'ID-033', 2, 3, 'Sudah Entry', 1, '2026-01-14', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (434, 1, 'ID-034', 6, 7, 'Error', 0, '2026-01-14', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (435, 1, 'ID-035', 2, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (436, 2, 'ID-036', 6, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (437, 2, 'ID-037', 6, 3, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (438, 4, 'ID-038', 6, 3, 'Uploaded', 0, '2026-01-28', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (439, 1, 'ID-039', 6, 3, 'Sudah Entry', 0, '2026-01-20', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (440, 4, 'ID-040', 2, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (441, 5, 'ID-041', 2, 3, 'Uploaded', 1, '2026-01-18', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (442, 5, 'ID-042', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (443, 4, 'ID-043', 2, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (444, 5, 'ID-044', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (445, 2, 'ID-045', 6, 3, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (446, 3, 'ID-046', 2, 7, 'Error', 0, '2026-01-14', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (447, 2, 'ID-047', 6, 7, 'Valid', 1, '2026-01-15', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (448, 2, 'ID-048', 6, 3, 'Uploaded', 1, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (449, 3, 'ID-049', 2, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (450, 5, 'ID-050', 2, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (451, 5, 'ID-051', 6, 3, 'Sudah Entry', 0, '2026-01-26', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (452, 5, 'ID-052', 6, 3, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (453, 3, 'ID-053', 2, 7, 'Error', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (454, 4, 'ID-054', 2, 3, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (455, 5, 'ID-055', 2, 7, 'Error', 1, '2025-12-31', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (456, 4, 'ID-056', 2, 3, 'Valid', 1, '2026-01-22', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (457, 1, 'ID-057', 2, 3, 'Valid', 1, '2025-12-30', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (458, 3, 'ID-058', 6, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (459, 3, 'ID-059', 2, 3, 'Valid', 0, '2026-01-04', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (460, 5, 'ID-060', 6, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (461, 5, 'ID-061', 6, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (462, 4, 'ID-062', 2, 3, 'Sudah Entry', 1, '2026-01-13', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (463, 5, 'ID-063', 2, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (464, 2, 'ID-064', 6, 3, 'Sudah Entry', 0, '2026-01-12', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (465, 3, 'ID-065', 2, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (466, 3, 'ID-066', 2, 7, 'Uploaded', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (467, 3, 'ID-067', 6, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (468, 2, 'ID-068', 6, 3, 'Sudah Entry', 0, '2026-01-21', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (469, 3, 'ID-069', 6, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (470, 5, 'ID-070', 6, 3, 'Valid', 1, '2026-01-15', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (471, 2, 'ID-071', 2, 7, 'Valid', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (472, 5, 'ID-072', 6, 3, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (473, 5, 'ID-073', 6, 3, 'Uploaded', 1, '2026-01-26', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (474, 4, 'ID-074', 6, 7, 'Error', 0, '2026-01-07', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (475, 3, 'ID-075', 2, 3, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (476, 2, 'ID-076', 2, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (477, 1, 'ID-077', 6, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (478, 2, 'ID-078', 2, 7, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (479, 3, 'ID-079', 6, 7, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (480, 2, 'ID-080', 2, 3, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (481, 1, 'ID-081', 6, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (482, 5, 'ID-082', 6, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (483, 3, 'ID-083', 2, 3, 'Uploaded', 0, '2026-01-06', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (484, 1, 'ID-084', 6, 7, 'Sudah Entry', 0, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (485, 3, 'ID-085', 6, 3, 'Error', 1, '2026-01-24', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (486, 4, 'ID-086', 6, 7, 'Error', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (487, 4, 'ID-087', 2, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (488, 2, 'ID-088', 6, 7, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (489, 1, 'ID-089', 2, 3, 'Error', 0, '2026-01-18', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (490, 1, 'ID-090', 6, 3, 'Error', 0, '2026-01-24', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (491, 2, 'ID-091', 6, 3, 'Sudah Entry', 1, '2026-01-13', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (492, 5, 'ID-092', 2, 7, 'Error', 0, '2026-01-24', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (493, 2, 'ID-093', 2, 3, 'Sudah Entry', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (494, 5, 'ID-094', 6, 3, 'Sudah Entry', 0, '2026-01-18', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (495, 4, 'ID-095', 2, 7, 'Error', 1, '2026-01-10', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (496, 2, 'ID-096', 6, 7, 'Uploaded', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (497, 5, 'ID-097', 6, 3, 'Sudah Entry', 1, '2026-01-17', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (498, 1, 'ID-098', 6, 3, 'Valid', 0, '2026-01-25', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (499, 2, 'ID-099', 6, 7, 'Uploaded', 1, '2026-01-01', '2026-01-29 14:17:55', '2026-01-29 14:17:55');
INSERT INTO `dokumen_survei` VALUES (500, 4, 'ID-000', 6, 7, 'Valid', 1, NULL, '2026-01-29 14:17:55', '2026-01-29 14:17:55');

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
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of master_kegiatan
-- ----------------------------
INSERT INTO `master_kegiatan` VALUES (1, 'Sakernas Februari 2026', 'SAK26FEB', '2026-02-01', '2026-02-28', 'Aktif', '2026-01-29 13:57:43');
INSERT INTO `master_kegiatan` VALUES (2, 'Susenas Maret 2026', 'SSN26MAR', '2026-03-01', '2026-03-31', 'Aktif', '2026-01-29 13:57:43');
INSERT INTO `master_kegiatan` VALUES (3, 'Pemutakhiran Registrasi Sosial Ekonomi (RSE) April 2026', 'RSE26APR', '2026-04-01', '2026-04-30', 'Aktif', '2026-01-29 13:57:43');
INSERT INTO `master_kegiatan` VALUES (4, 'Survei Triwulanan Angkatan Kerja Nasional (Sakernas) Juni 2026', 'STAKERNAS26JUN', '2026-06-01', '2026-06-30', 'Aktif', '2026-01-29 13:57:43');
INSERT INTO `master_kegiatan` VALUES (5, 'Survei Sosial Ekonomi Nasional (Susenas) September 2026', 'SSN26SEP', '2026-09-01', '2026-09-30', 'Aktif', '2026-01-29 13:57:43');

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2026-01-30-100000', 'App\\Database\\Migrations\\CreateAuditLogsTable', 'default', 'App', 1769763387, 1);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id_role`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'Administrator', 'Super user with full access');
INSERT INTO `roles` VALUES (3, 'Petugas Pendataan (PCL)', 'Mitra Lapangan - Field Enumerator');
INSERT INTO `roles` VALUES (4, 'Petugas Pengolahan', 'Mitra Entry/Editing - Data Processor');
INSERT INTO `roles` VALUES (5, 'Pengawas Lapangan (PML)', 'Field Supervisor');
INSERT INTO `roles` VALUES (6, 'Pengawas Pengolahan', 'Processing Supervisor');

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
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Administrator', 'admin', 'admin@monika.test', '$2y$10$DUX9A2pvf1qMAEeWAe7mzOdCMN1FCXKvdSTYG1JnNZsUVN.7DefWa', NULL, NULL, 1, NULL, 1, '2026-01-29 14:19:58', '2026-01-29 14:19:58');
INSERT INTO `users` VALUES (2, 'John Doe', 'johndoe', 'john.doe@monika.test', '$2y$10$f.m/.FJ7TxUd3kijFa2ahu7un4hFyjcp8QeQ5wMJLK/sKkRDhzF1G', '1234567890123456', 'SOB001', 3, NULL, 1, '2026-01-29 14:19:58', '2026-01-29 14:19:58');
INSERT INTO `users` VALUES (3, 'Jane Smith', 'janesmith', 'jane.smith@monika.test', '$2y$10$LKvAQky8XnWmaYn3gm6b5.Nj5oHNRS1ooq2W/kAf4QxqIAon0GLGq', '1234567890123457', 'SOB002', 4, NULL, 1, '2026-01-29 14:19:58', '2026-01-29 14:19:58');
INSERT INTO `users` VALUES (4, 'Robert Johnson', 'robertj', 'robert.johnson@monika.test', '$2y$10$z.GnpZun708lkpSl8UEriu/Vip3ogpTA4u/SpKbzzBnKAHKAtRKLG', '1234567890123458', 'SOB003', 5, NULL, 1, '2026-01-29 14:19:58', '2026-01-29 14:19:58');
INSERT INTO `users` VALUES (5, 'Emily Davis', 'emilyd', 'emily.davis@monika.test', '$2y$10$.kJ/g1Z/lFtrOdbHa7QiRuOqb3kDzGWuyBCd8wT7N5PPQRE6YoRPC', '1234567890123459', 'SOB004', 6, NULL, 1, '2026-01-29 14:19:58', '2026-01-29 14:19:58');
INSERT INTO `users` VALUES (6, 'Michael Wilson', 'michaelw', 'michael.wilson@monika.test', '$2y$10$maVjNLq8TYzuElhDQ/Y6CuAPbh4ad9nyBxt7t1NKusIohTNQaZmda', '1234567890123460', 'SOB005', 3, 4, 1, '2026-01-29 14:19:58', '2026-01-29 14:19:58');
INSERT INTO `users` VALUES (7, 'Sarah Brown', 'sarahb', 'sarah.brown@monika.test', '$2y$10$FxhdZluWWOA9nqxk82HTB.VwpmLjlT/rtNiZOp3dT3ejSH9BbYWRm', '1234567890123461', 'SOB006', 4, 5, 1, '2026-01-29 14:19:58', '2026-01-29 14:19:58');

SET FOREIGN_KEY_CHECKS = 1;
