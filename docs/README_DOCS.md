# Dokumentasi MONIKA — Entry Point

> **MONIKA** (Monitoring Nilai Kinerja & Anomali)
> Aplikasi Web Monitoring Penerimaan & Pengolahan Dokumen Survei
> BPS Kabupaten Jember

---

## 📚 Daftar Dokumentasi

### Role & Hak Akses
| Dokumen | Deskripsi | Audiens |
|---------|-----------|---------|
| [ROLES.md](ROLES.md) | Definisi role, matriks permission, hierarki supervisi | Semua |
| [PANDUAN_PENGGUNAAN.md](PANDUAN_PENGGUNAAN.md) | Panduan penggunaan per role dengan FAQ | End User |
| [CHANGELOG_ROLES.md](CHANGELOG_ROLES.md) | Riwayat perubahan role & permission | Developer |

### Teknis & Pengembangan
| Dokumen | Deskripsi | Audiens |
|---------|-----------|---------|
| [TEKNIS_PENGEMBANGAN.md](TEKNIS_PENGEMBANGAN.md) | DB schema, API endpoints, contoh kode RBAC | Developer |
| [STANDAR_KONVENSI.md](STANDAR_KONVENSI.md) | Naming convention, best practices, template fitur baru | Developer |
| [PANDUAN_AI_AGENT.md](PANDUAN_AI_AGENT.md) | Guideline untuk AI assistant, decision tree, testing | AI Agent |

### Referensi Lainnya
| Dokumen | Deskripsi |
|---------|-----------|
| [AKUN_PENGGUNA.md](AKUN_PENGGUNA.md) | Daftar akun pengguna dummy |
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | Dokumentasi API lengkap |
| [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) | Struktur folder proyek |
| [FEATURES_LIST.md](FEATURES_LIST.md) | Daftar fitur aplikasi |
| [TEKNIS_ADMINLTE_MONIKA.md](TEKNIS_ADMINLTE_MONIKA.md) | Panduan frontend AdminLTE |

---

## 🏗 Arsitektur Singkat

```
Browser → Routes.php → AuthFilter → Controller → Model → MySQL
                                         ↓
                              session('id_role') check
                                         ↓
                              View (AdminLTE 3 template)
```

## 👥 Role Summary

| Role | ID | Akses Utama |
|------|----|-------------|
| Administrator | 1 | Full access |
| PCL | 3 | Setor dokumen, tanda terima |
| Pengolahan | 4 | Entry, konfirmasi |
| PML | 5 | Uji petik, awasi PCL |
| Pengawas Pengolahan | 6 | Uji petik, awasi entry |

## 🔑 Login Testing

Password: `Monika@2026!`

```
admin_01  │ pcl_01  │ pengolah_01  │ pml_01  │ pengawas_01
```

## 📁 Lokasi Penting

| Path | Fungsi |
|------|--------|
| `app/Config/Routes.php` | Semua endpoint |
| `app/Views/layout/sidebar.php` | Menu visibility |
| `app/Filters/AuthFilter.php` | Auth middleware |
| `app/Database/Seeds/` | Data dummy |
| `docs/` | Dokumentasi |
