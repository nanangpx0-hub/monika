# Changelog Role & Permission — MONIKA

> Catatan perubahan terkait role, permission, dan hak akses pengguna.

Format: `[YYYY-MM-DD] Jenis Perubahan — Deskripsi`

---

## 2026-02-17 — Initial Documentation

### Ditambahkan
- Dokumentasi lengkap untuk 5 role: Administrator (1), PCL (3), Pengolahan (4), PML (5), Pengawas Pengolahan (6)
- Matriks permission untuk semua modul
- Panduan penggunaan per role
- Panduan teknis pengembangan RBAC
- Panduan AI Agent
- Standar & konvensi penamaan

### Status Role Saat Ini
| id_role | Nama | Status |
|---------|------|--------|
| 1 | Administrator | ✅ Aktif |
| 2 | *(tidak digunakan)* | ⚪ Reserved |
| 3 | Petugas Pendataan (PCL) | ✅ Aktif |
| 4 | Petugas Pengolahan | ✅ Aktif |
| 5 | Pengawas Lapangan (PML) | ✅ Aktif |
| 6 | Pengawas Pengolahan | ✅ Aktif |

---

## 2026-02-17 — Modul Penyetoran Dokumen

### Ditambahkan
- Endpoint `/penyetoran` dengan 7 route
- Permission: Create → role [1, 3], Confirm → role [1, 4], View → semua role
- Menu sidebar "Penyetoran Dokumen" (visible untuk semua role)

### File Terdampak
- `app/Controllers/PenyetoranDokumen.php`
- `app/Config/Routes.php`
- `app/Views/layout/sidebar.php`

---

## Template untuk Entri Baru

```markdown
## YYYY-MM-DD — Judul Perubahan

### Ditambahkan / Diubah / Dihapus
- Deskripsi perubahan
- Role yang terpengaruh: [daftar role]

### File Terdampak
- `path/ke/file1.php`
- `path/ke/file2.php`
```
