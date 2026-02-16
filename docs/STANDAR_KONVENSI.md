# Standar & Konvensi — MONIKA

> **Terakhir diperbarui**: 17 Februari 2026

---

## 1. Naming Convention

### 1.1 Role

| Konvensi | Format | Contoh |
|----------|--------|--------|
| Nama role di DB | Title Case, deskriptif | `Petugas Pendataan (PCL)` |
| Kode singkat | 3 huruf kapital | `ADM`, `PCL`, `OLH`, `PML`, `PPG` |
| Variabel di kode | `id_role` (integer) | `session()->get('id_role')` |
| Prefix username dummy | lowercase kode | `admin_01`, `pcl_03`, `pengolah_02` |

### 1.2 Permission di Kode

```php
// ✅ BENAR: gunakan helper method dengan prefix can/is
private function canCreate(): bool { ... }
private function canConfirm(): bool { ... }
private function isAdmin(): bool { ... }

// ❌ SALAH: inline check berulang tanpa abstraksi
if (session()->get('id_role') == 1 || session()->get('id_role') == 3) { ... }
```

### 1.3 File & Folder

| Jenis | Konvensi | Contoh |
|-------|----------|--------|
| Controller | PascalCase | `PenyetoranDokumen.php` |
| Model | PascalCase + "Model" | `PenyetoranDokumenModel.php` |
| View folder | lowercase | `penyetoran/` |
| View file | lowercase + underscore | `modal_import.php` |
| Migration | `YYYY-MM-DD-HHMMSS_NamaAction` | `2026-02-17-000000_CreatePenyetoranDokumen.php` |
| Seeder | PascalCase + "Seeder" | `ComprehensiveDummySeeder.php` |
| Route group | kebab-case | `tanda-terima`, `kartu-kendali` |
| Docs | UPPER_SNAKE_CASE.md | `ROLES.md`, `PANDUAN_PENGGUNAAN.md` |

---

## 2. Best Practices

### 2.1 Menambah Role Baru

1. **Database**: Tambah record di tabel `roles`
   ```sql
   INSERT INTO roles (id_role, role_name, description) 
   VALUES (7, 'Koordinator Statistik', 'KSK yang mengkoordinasi PCL dan PML');
   ```

2. **Controller**: Tambahkan `id_role` baru di setiap `in_array()` yang sesuai
   ```php
   // Sebelum
   if (!in_array((int) session()->get('id_role'), [1, 5])) { ... }
   // Sesudah (tambah role 7)
   if (!in_array((int) session()->get('id_role'), [1, 5, 7])) { ... }
   ```

3. **View/Sidebar**: Sesuaikan visibility sidebar jika perlu

4. **Dokumentasi**: Update file-file berikut:
   - `docs/ROLES.md` → tambah definisi & matriks
   - `docs/PANDUAN_PENGGUNAAN.md` → tambah section panduan
   - `docs/CHANGELOG_ROLES.md` → catat perubahan

5. **Testing**: Buat user dummy dengan role baru, test setiap modul

### 2.2 Memodifikasi Permission

1. Identifikasi semua controller yang perlu diubah:
   ```bash
   grep -rn "id_role" app/Controllers/ --include="*.php"
   ```

2. Identifikasi semua view yang perlu diubah:
   ```bash
   grep -rn "role_id\|id_role\|isAdmin" app/Views/ --include="*.php"
   ```

3. Update permission di controller → view → sidebar → docs

> [!WARNING]
> Selalu test dengan akun setiap role setelah mengubah permission. Gunakan akun dummy (`admin_01`, `pcl_01`, `pengolah_01`, dst).

### 2.3 Security Checklist

- [ ] Setiap endpoint sensitif memiliki role check di **controller** (bukan hanya view)
- [ ] CSRF token aktif di setiap form POST
- [ ] Password di-hash dengan bcrypt (cost ≥ 12)
- [ ] Input di-validasi di controller sebelum masuk database
- [ ] Output di-escape dengan `esc()` di view
- [ ] File upload divalidasi extension dan ukuran

---

## 3. Template Dokumentasi Fitur Baru

Gunakan template berikut saat mendokumentasikan fitur baru yang melibatkan multi-role:

```markdown
# Nama Fitur — MONIKA

## Deskripsi
[Deskripsi singkat fitur]

## Akses Role
| Aksi | ADM (1) | PCL (3) | OLH (4) | PML (5) | PPG (6) |
|------|:-------:|:-------:|:-------:|:-------:|:-------:|
| Lihat | ✅ | ✅ | ✅ | ✅ | ✅ |
| Tambah | ✅ | ✅ | ❌ | ❌ | ❌ |
| Edit | ✅ | ❌ | ❌ | ❌ | ❌ |
| Hapus | ✅ | ❌ | ❌ | ❌ | ❌ |

## Endpoint
| Method | URL | Role |
|--------|-----|------|
| GET | /fitur | All |
| POST | /fitur/store | 1, 3 |

## Database
- Tabel: `nama_tabel`
- Relasi: FK ke `users`, `master_kegiatan`

## Catatan Implementasi
[Catatan teknis penting]
```

---

## 4. Konvensi Kode

### 4.1 Controller

```php
class NamaFitur extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new NamaFiturModel();
    }

    // 1. Helper role check di atas
    private function canManage(): bool { ... }

    // 2. Method CRUD urut: index, create, store, edit, update, delete
    public function index() { ... }
    public function create() { ... }
    public function store() { ... }
}
```

### 4.2 View

```php
<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
    <!-- Konten halaman -->
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
    <script>
    $(function () {
        // jQuery/DataTable init
    });
    </script>
<?= $this->endSection(); ?>
```

### 4.3 Route

```php
$routes->group('nama-fitur', static function ($routes): void {
    $routes->get('/', 'NamaFitur::index');
    $routes->get('create', 'NamaFitur::create');
    $routes->post('store', 'NamaFitur::store', ['filter' => 'csrf']);
    $routes->post('delete/(:num)', 'NamaFitur::delete/$1', ['filter' => 'csrf']);
});
```
