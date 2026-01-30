# DOKUMENTASI SKEMA DATABASE MONIKA

## 📊 OVERVIEW DATABASE

**Database Name:** `monika`  
**Engine:** InnoDB  
**Charset:** utf8mb4  
**Collation:** utf8mb4_general_ci  

### Ringkasan Tabel
| Tabel | Fungsi | Records | Relasi |
|-------|--------|---------|--------|
| `roles` | Master peran pengguna | 5 | Parent untuk users |
| `users` | Data pengguna sistem | Dynamic | Child dari roles, parent untuk dokumen |
| `master_kegiatan` | Master kegiatan survei | Dynamic | Parent untuk dokumen_survei |
| `dokumen_survei` | Transaksi dokumen | Dynamic | Child dari kegiatan & users |
| `anomali_log` | Log error/anomali | Dynamic | Child dari dokumen_survei |

---

## 🏗️ STRUKTUR TABEL DETAIL

### 1. Tabel `roles`
**Fungsi:** Menyimpan definisi peran/role dalam sistem

```sql
CREATE TABLE `roles` (
  `id_role` INT(11) NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(50) NOT NULL,
  `description` TEXT,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Data Seed:**
```sql
INSERT INTO `roles` (`id_role`, `role_name`, `description`) VALUES
(1, 'Administrator', 'Super user with full access'),
(3, 'Petugas Pendataan (PCL)', 'Mitra Lapangan - Field Enumerator'),
(4, 'Petugas Pengolahan', 'Mitra Entry/Editing - Data Processor'),
(5, 'Pengawas Lapangan (PML)', 'Field Supervisor'),
(6, 'Pengawas Pengolahan', 'Processing Supervisor');
```

**Catatan Penting:**
- ID 2 tidak digunakan (reserved untuk future use)
- Role ID 1 = Admin dengan akses penuh
- Role ID 3,4 = Mitra yang bisa self-register
- Role ID 5,6 = Supervisor dengan akses monitoring

---

### 2. Tabel `users`
**Fungsi:** Menyimpan data pengguna dan hierarki supervisor

```sql
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
```

**Field Descriptions:**
- `fullname`: Nama lengkap pengguna
- `username`: Username unik untuk login (bisa email atau custom)
- `email`: Email unik untuk login alternatif
- `password`: Hash Bcrypt dari password asli
- `nik_ktp`: Nomor Induk Kependudukan (16 digit)
- `sobat_id`: ID Mitra Statistik (untuk PCL/Processor)
- `id_role`: Foreign key ke tabel roles
- `id_supervisor`: Self-referencing FK untuk hierarki supervisor-subordinate
- `is_active`: Flag aktif/non-aktif (0=non-aktif, 1=aktif)

**Constraints & Indexes:**
- UNIQUE: username, email
- INDEX: id_role, id_supervisor
- FK CASCADE: id_role (hapus role = hapus user)
- FK SET NULL: id_supervisor (hapus supervisor = set NULL)

---

### 3. Tabel `master_kegiatan`
**Fungsi:** Master data kegiatan/periode survei

```sql
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
```

**Field Descriptions:**
- `nama_kegiatan`: Nama lengkap kegiatan (contoh: "Sakernas Februari 2026")
- `kode_kegiatan`: Kode unik kegiatan (contoh: "SAK26FEB")
- `tanggal_mulai`: Tanggal mulai periode kegiatan
- `tanggal_selesai`: Tanggal selesai periode kegiatan
- `status`: Status kegiatan (Aktif/Selesai)

**Business Rules:**
- Hanya kegiatan dengan status 'Aktif' yang bisa menerima dokumen baru
- Kode kegiatan harus unik di seluruh sistem
- Admin bisa mengubah status dari 'Aktif' ke 'Selesai' untuk mengarsipkan

**Sample Data:**
```sql
INSERT INTO `master_kegiatan` (`nama_kegiatan`, `kode_kegiatan`, `tanggal_mulai`, `tanggal_selesai`, `status`) VALUES
('Sakernas Februari 2026', 'SAK26FEB', '2026-02-01', '2026-02-28', 'Aktif'),
('Susenas Maret 2026', 'SSN26MAR', '2026-03-01', '2026-03-31', 'Aktif');
```

---

### 4. Tabel `dokumen_survei`
**Fungsi:** Transaksi dokumen survei dan tracking status

```sql
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
```

**Field Descriptions:**
- `id_kegiatan`: FK ke master_kegiatan (WAJIB)
- `kode_wilayah`: Kode wilayah/region dokumen
- `id_petugas_pendataan`: FK ke users dengan role PCL (Role 3)
- `processed_by`: FK ke users dengan role Processor (Role 4)
- `status`: Status dokumen dalam workflow
- `pernah_error`: Flag permanen jika dokumen pernah error (0=tidak pernah, 1=pernah)
- `tanggal_setor`: Tanggal penyetoran dokumen oleh PCL

**Status Flow:**
```
Uploaded → Sudah Entry → [End]
    ↓
  Error → Valid → [End]
```

**Business Rules:**
1. **Permanent Error Flag**: Field `pernah_error` tidak pernah direset ke 0 setelah diset ke 1
2. **Status Transitions**:
   - `Uploaded` → `Sudah Entry` (normal flow)
   - `Uploaded` → `Error` (anomali ditemukan)
   - `Error` → `Valid` (setelah perbaikan)
3. **Role Restrictions**:
   - PCL (Role 3) hanya bisa create dengan status 'Uploaded'
   - Processor (Role 4) bisa update status dan report error

---

### 5. Tabel `anomali_log`
**Fungsi:** Audit trail untuk semua error/anomali yang ditemukan

```sql
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
```

**Field Descriptions:**
- `id_dokumen`: FK ke dokumen_survei yang bermasalah
- `id_petugas_pengolahan`: FK ke users yang melaporkan error (Role 4)
- `jenis_error`: Kategori/tipe error (contoh: "Data Tidak Lengkap")
- `keterangan`: Deskripsi detail error yang ditemukan

**Characteristics:**
- **Immutable Records**: Tidak ada updated_at (data tidak boleh diubah)
- **Audit Trail**: Semua error tercatat dengan timestamp
- **Multiple Errors**: Satu dokumen bisa punya multiple anomali_log entries

**Common Error Types:**
- "Data Tidak Lengkap"
- "Format Salah"
- "Inkonsistensi Data"
- "Nilai di Luar Range"
- "Duplikasi Data"

---

## 🔗 RELASI ANTAR TABEL

### Entity Relationship Diagram (ERD)
```
roles (1) ←→ (M) users (1) ←→ (M) dokumen_survei (M) ←→ (1) master_kegiatan
                ↑                        ↓
                └── (supervisor)    anomali_log (M)
```

### Detailed Relationships

#### 1. roles → users (One-to-Many)
```sql
-- Constraint
CONSTRAINT `fk_user_role` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`) 
ON DELETE CASCADE ON UPDATE CASCADE
```
- Satu role bisa dimiliki banyak users
- Hapus role akan menghapus semua users dengan role tersebut

#### 2. users → users (Self-Referencing)
```sql
-- Constraint
CONSTRAINT `fk_user_supervisor` FOREIGN KEY (`id_supervisor`) REFERENCES `users` (`id_user`) 
ON DELETE SET NULL ON UPDATE CASCADE
```
- Hierarki supervisor-subordinate
- Hapus supervisor akan set NULL pada field id_supervisor

#### 3. master_kegiatan → dokumen_survei (One-to-Many)
```sql
-- Constraint
CONSTRAINT `fk_dok_kegiatan` FOREIGN KEY (`id_kegiatan`) REFERENCES `master_kegiatan` (`id_kegiatan`) 
ON DELETE CASCADE ON UPDATE CASCADE
```
- Satu kegiatan bisa punya banyak dokumen
- Hapus kegiatan akan menghapus semua dokumen terkait

#### 4. users → dokumen_survei (Multiple Foreign Keys)
```sql
-- PCL Relationship
CONSTRAINT `fk_dok_pcl` FOREIGN KEY (`id_petugas_pendataan`) REFERENCES `users` (`id_user`) 
ON DELETE SET NULL ON UPDATE CASCADE

-- Processor Relationship
CONSTRAINT `fk_dok_proc` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id_user`) 
ON DELETE SET NULL ON UPDATE CASCADE
```
- User bisa jadi PCL (id_petugas_pendataan) atau Processor (processed_by)
- Hapus user akan set NULL pada field terkait

#### 5. dokumen_survei → anomali_log (One-to-Many)
```sql
-- Constraint
CONSTRAINT `fk_anomali_dok` FOREIGN KEY (`id_dokumen`) REFERENCES `dokumen_survei` (`id_dokumen`) 
ON DELETE CASCADE ON UPDATE CASCADE
```
- Satu dokumen bisa punya banyak anomali log
- Hapus dokumen akan menghapus semua anomali log terkait

---

## 📈 QUERY PATTERNS & OPTIMIZATIONS

### 1. Performance Queries

#### Dashboard Statistics
```sql
-- Total dokumen per kegiatan
SELECT 
    mk.nama_kegiatan,
    COUNT(ds.id_dokumen) as total_dokumen,
    COUNT(CASE WHEN ds.status = 'Error' THEN 1 END) as total_error,
    COUNT(CASE WHEN ds.status = 'Sudah Entry' THEN 1 END) as total_entry,
    COUNT(CASE WHEN ds.status = 'Sudah Entry' AND ds.pernah_error = 0 THEN 1 END) as clean_entry
FROM master_kegiatan mk
LEFT JOIN dokumen_survei ds ON mk.id_kegiatan = ds.id_kegiatan
WHERE mk.status = 'Aktif'
GROUP BY mk.id_kegiatan;
```

#### PCL Performance Ranking
```sql
-- Ranking PCL berdasarkan error rate
SELECT 
    u.fullname,
    u.sobat_id,
    COUNT(ds.id_dokumen) as total_dokumen,
    SUM(ds.pernah_error) as total_error,
    ROUND((SUM(ds.pernah_error) / COUNT(ds.id_dokumen)) * 100, 2) as error_rate_percent
FROM users u
LEFT JOIN dokumen_survei ds ON u.id_user = ds.id_petugas_pendataan
WHERE u.id_role = 3 AND u.is_active = 1
GROUP BY u.id_user
HAVING total_dokumen > 0
ORDER BY error_rate_percent ASC, total_dokumen DESC;
```

#### Supervisor Team Performance
```sql
-- Performa tim supervisor
SELECT 
    spv.fullname as supervisor_name,
    COUNT(DISTINCT pcl.id_user) as team_size,
    COUNT(doc.id_dokumen) as total_team_docs,
    SUM(doc.pernah_error) as total_team_errors,
    ROUND((SUM(doc.pernah_error) / COUNT(doc.id_dokumen)) * 100, 2) as team_error_rate
FROM users spv
LEFT JOIN users pcl ON pcl.id_supervisor = spv.id_user AND pcl.id_role = 3
LEFT JOIN dokumen_survei doc ON doc.id_petugas_pendataan = pcl.id_user
WHERE spv.id_role = 5 AND spv.is_active = 1
GROUP BY spv.id_user
HAVING total_team_docs > 0
ORDER BY team_error_rate ASC;
```

### 2. Indexes untuk Performance

#### Recommended Indexes
```sql
-- Composite indexes untuk query yang sering digunakan
CREATE INDEX idx_dokumen_kegiatan_status ON dokumen_survei (id_kegiatan, status);
CREATE INDEX idx_dokumen_pcl_kegiatan ON dokumen_survei (id_petugas_pendataan, id_kegiatan);
CREATE INDEX idx_dokumen_processor_kegiatan ON dokumen_survei (processed_by, id_kegiatan);
CREATE INDEX idx_users_role_active ON users (id_role, is_active);
CREATE INDEX idx_anomali_dokumen_created ON anomali_log (id_dokumen, created_at);

-- Index untuk supervisor hierarchy
CREATE INDEX idx_users_supervisor_role ON users (id_supervisor, id_role);
```

### 3. Data Integrity Checks

#### Validation Queries
```sql
-- Check orphaned records
SELECT 'Orphaned dokumen_survei' as issue, COUNT(*) as count
FROM dokumen_survei ds
LEFT JOIN master_kegiatan mk ON ds.id_kegiatan = mk.id_kegiatan
WHERE mk.id_kegiatan IS NULL

UNION ALL

SELECT 'Orphaned anomali_log' as issue, COUNT(*) as count
FROM anomali_log al
LEFT JOIN dokumen_survei ds ON al.id_dokumen = ds.id_dokumen
WHERE ds.id_dokumen IS NULL

UNION ALL

-- Check inconsistent error flags
SELECT 'Inconsistent pernah_error flag' as issue, COUNT(*) as count
FROM dokumen_survei ds
LEFT JOIN anomali_log al ON ds.id_dokumen = al.id_dokumen
WHERE ds.pernah_error = 0 AND al.id_anomali IS NOT NULL;
```

---

## 🔧 MAINTENANCE & BACKUP

### 1. Regular Maintenance Tasks

#### Database Cleanup
```sql
-- Clean old sessions (if using database sessions)
DELETE FROM ci_sessions WHERE timestamp < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY));

-- Archive completed kegiatan (older than 1 year)
UPDATE master_kegiatan 
SET status = 'Selesai' 
WHERE tanggal_selesai < DATE_SUB(CURDATE(), INTERVAL 1 YEAR) 
AND status = 'Aktif';
```

#### Statistics Update
```sql
-- Update user statistics (if cached)
UPDATE users u SET 
    total_dokumen = (
        SELECT COUNT(*) FROM dokumen_survei ds 
        WHERE ds.id_petugas_pendataan = u.id_user
    ),
    total_error = (
        SELECT COUNT(*) FROM dokumen_survei ds 
        WHERE ds.id_petugas_pendataan = u.id_user AND ds.pernah_error = 1
    )
WHERE u.id_role = 3;
```

### 2. Backup Strategy

#### Full Backup
```bash
# Complete database backup
mysqldump -u username -p --single-transaction --routines --triggers monika > monika_backup_$(date +%Y%m%d_%H%M%S).sql

# Compressed backup
mysqldump -u username -p --single-transaction --routines --triggers monika | gzip > monika_backup_$(date +%Y%m%d_%H%M%S).sql.gz
```

#### Incremental Backup
```bash
# Backup only recent data (last 7 days)
mysqldump -u username -p monika \
  --where="created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)" \
  dokumen_survei anomali_log > incremental_backup_$(date +%Y%m%d).sql
```

### 3. Monitoring Queries

#### System Health Check
```sql
-- Database size monitoring
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb,
    table_rows
FROM information_schema.tables 
WHERE table_schema = 'monika'
ORDER BY size_mb DESC;

-- Growth rate monitoring
SELECT 
    DATE(created_at) as date,
    COUNT(*) as new_documents
FROM dokumen_survei 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DATE(created_at)
ORDER BY date DESC;
```

---

## 📋 TROUBLESHOOTING

### Common Issues

#### 1. Foreign Key Constraint Errors
```sql
-- Check constraint violations
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE CONSTRAINT_SCHEMA = 'monika' AND REFERENCED_TABLE_NAME IS NOT NULL;

-- Disable/Enable foreign key checks (for maintenance)
SET FOREIGN_KEY_CHECKS = 0;
-- Perform maintenance operations
SET FOREIGN_KEY_CHECKS = 1;
```

#### 2. Performance Issues
```sql
-- Find slow queries
SELECT * FROM information_schema.PROCESSLIST 
WHERE COMMAND != 'Sleep' AND TIME > 5;

-- Analyze table statistics
ANALYZE TABLE dokumen_survei, anomali_log, users;

-- Check index usage
SHOW INDEX FROM dokumen_survei;
```

#### 3. Data Consistency Issues
```sql
-- Fix inconsistent pernah_error flags
UPDATE dokumen_survei ds
SET pernah_error = 1
WHERE EXISTS (
    SELECT 1 FROM anomali_log al 
    WHERE al.id_dokumen = ds.id_dokumen
) AND ds.pernah_error = 0;
```

---

**Dokumentasi Database Schema MONIKA**  
**Versi:** 1.0  
**Terakhir Diperbarui:** <?= date('d F Y H:i:s') ?>  
**Status:** Production Ready