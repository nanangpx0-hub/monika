# Quick Start Guide - Modul Kartu Kendali Digital

## 🚀 Setup dalam 5 Menit

### 1. Run Migration
```bash
php spark migrate
```

### 2. Seed Test Data
```bash
php spark db:seed KartuKendaliTestSeeder
```

### 3. Login ke Aplikasi
- URL: `http://localhost/monika/`
- Username: `admin`
- Password: `admin123` (atau sesuai seeder Anda)

### 4. Akses Modul
- Klik menu **"Kartu Kendali"** di sidebar
- Atau akses langsung: `http://localhost/monika/kartu-kendali`

### 5. Test Functionality
1. Pilih salah satu NKS (contoh: 26001)
2. Klik "Buka Rincian"
3. Klik kotak putih (ruta yang siap dikerjakan)
4. Isi form:
   - Status: Clean atau Error
   - Patch Issue: Centang jika ada masalah aplikasi
5. Klik "Simpan"
6. Verifikasi kotak berubah warna

## 📁 File Structure

```
app/
├── Controllers/
│   └── KartuKendali.php          # Main controller
├── Models/
│   └── KartuKendaliModel.php     # Data model
├── Views/
│   └── kartu_kendali/
│       ├── index.php              # List NKS
│       └── detail.php             # Grid 10 ruta
├── Database/
│   ├── Migrations/
│   │   └── 2026-02-15-164058_CreateKartuKendaliTable.php
│   └── Seeds/
│       └── KartuKendaliTestSeeder.php
└── Config/
    └── Routes.php                 # Route definitions

public/
└── assets/
    └── css/
        └── monika-ui.css          # Custom styles

docs/
├── KARTU_KENDALI_MODULE.md        # Technical docs
├── KARTU_KENDALI_USER_GUIDE.md    # User manual
└── KARTU_KENDALI_QUICKSTART.md    # This file
```

## 🔧 Configuration

### Database
Pastikan konfigurasi database di `.env`:
```env
database.default.hostname = localhost
database.default.database = monika
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

### Routes
Routes sudah dikonfigurasi di `app/Config/Routes.php`:
```php
$routes->group('kartu-kendali', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'KartuKendali::index');
    $routes->get('detail/(:segment)', 'KartuKendali::detail/$1');
    $routes->post('store', 'KartuKendali::store', ['filter' => 'csrf']);
    $routes->post('delete', 'KartuKendali::delete', ['filter' => 'csrf']);
});
```

## 🧪 Testing Scenarios

### Scenario 1: Entry Data Baru
```
1. Login sebagai petugas
2. Buka Kartu Kendali
3. Pilih NKS 26001
4. Klik ruta nomor 1 (putih)
5. Pilih status "Clean"
6. Simpan
7. ✅ Kotak berubah hijau
```

### Scenario 2: Edit Data Existing
```
1. Klik ruta yang sudah dikerjakan (hijau/merah)
2. Ubah status dari Clean ke Error
3. Centang "Patch Issue"
4. Simpan
5. ✅ Kotak berubah merah dengan badge "Patch Issue"
```

### Scenario 3: Delete Entry
```
1. Klik ruta yang sudah dikerjakan
2. Klik tombol "Hapus"
3. Konfirmasi
4. ✅ Kotak kembali putih (siap dikerjakan)
```

### Scenario 4: Validasi Lock
```
1. Login sebagai User A
2. Entry ruta nomor 1
3. Logout
4. Login sebagai User B
5. Buka NKS yang sama
6. ✅ Ruta nomor 1 berwarna kuning (locked)
7. ✅ Menampilkan nama User A
```

## 🐛 Troubleshooting

### Error: Table 'kartu_kendali' doesn't exist
```bash
# Solution: Run migration
php spark migrate
```

### Error: No data in NKS list
```bash
# Solution: Run seeder
php spark db:seed KartuKendaliTestSeeder
```

### Error: 404 Not Found
```bash
# Solution: Check .htaccess and base URL
# Ensure mod_rewrite is enabled
```

### Error: CSRF Token Mismatch
```bash
# Solution: Clear browser cache and cookies
# Or disable CSRF temporarily for testing (not recommended for production)
```

### Error: Permission Denied
```bash
# Solution: Check auth filter
# Ensure user is logged in
# Check user role permissions
```

## 📊 Sample Data

Seeder akan membuat:

### NKS Master
| NKS | Kecamatan | Desa | Target Ruta |
|-----|-----------|------|-------------|
| 26001 | Kencong | Kencong | 10 |
| 26002 | Kencong | Paseban | 10 |
| 26003 | Gumukmas | Karangharjo | 10 |

### Tanda Terima
| NKS | Jumlah Diterima | Status |
|-----|-----------------|--------|
| 26001 | 10 ruta | Semua bisa dikerjakan |
| 26002 | 6 ruta | 4 ruta terkunci |
| 26003 | 4 ruta | 6 ruta terkunci |

## 🔐 Security Checklist

- [x] CSRF protection enabled
- [x] Auth filter applied
- [x] XSS prevention with esc()
- [x] SQL injection prevention (prepared statements)
- [x] Ownership validation
- [x] Unique constraint on nks+no_ruta
- [x] Foreign key constraints

## 📱 Browser Compatibility

Tested on:
- ✅ Chrome 120+
- ✅ Firefox 120+
- ✅ Edge 120+
- ✅ Safari 17+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## 🎯 Performance Tips

1. **DataTables Pagination**: Default 25 rows per page
2. **AJAX Operations**: No page reload needed
3. **Caching**: Browser caching enabled for assets
4. **Database Indexes**: Unique key on nks+no_ruta
5. **Foreign Keys**: Cascade delete for data integrity

## 📚 Additional Resources

- **Technical Documentation**: `docs/KARTU_KENDALI_MODULE.md`
- **User Guide**: `docs/KARTU_KENDALI_USER_GUIDE.md`
- **Implementation Summary**: `KARTU_KENDALI_IMPLEMENTATION.md`
- **CodeIgniter 4 Docs**: https://codeigniter.com/user_guide/
- **AdminLTE Docs**: https://adminlte.io/docs/

## 💡 Pro Tips

1. **Keyboard Shortcuts**:
   - `Ctrl + F5`: Hard refresh
   - `F12`: Open DevTools
   - `Ctrl + Shift + I`: Inspect element

2. **Development Mode**:
   ```env
   CI_ENVIRONMENT = development
   ```

3. **Debug Mode**:
   ```php
   // In controller
   dd($data); // Dump and die
   log_message('debug', 'Debug info');
   ```

4. **Database Query Log**:
   ```php
   // In controller
   $db = \Config\Database::connect();
   echo $db->getLastQuery();
   ```

## 🎓 Learning Path

1. ✅ Setup & Installation
2. ✅ Basic CRUD Operations
3. ✅ Status Logic Understanding
4. ⏭️ Advanced Features (Export, Analytics)
5. ⏭️ API Integration
6. ⏭️ Mobile App Development

## 🤝 Contributing

Jika ingin berkontribusi:
1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## 📞 Support

- **Email**: support@bpsjember.go.id
- **Documentation**: `/docs/`
- **Issue Tracker**: GitHub Issues

---

**Happy Coding! 🚀**

*Last Updated: 15 Februari 2026*
