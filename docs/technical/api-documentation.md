# DOKUMENTASI API MONIKA

## 📋 OVERVIEW

Dokumentasi ini menyediakan referensi lengkap untuk semua endpoint API yang tersedia dalam aplikasi MONIKA (Monitoring Nilai Kinerja & Anomali). Aplikasi ini menggunakan CodeIgniter 4 sebagai framework backend dengan pola arsitektur MVC.

**Base URL:** `http://localhost/monika` (sesuaikan dengan environment Anda)

**Authentication:** Session-based authentication dengan filter `auth`

**Response Format:** HTML (untuk web views) dan JSON (untuk API calls)

---

## 🔐 AUTHENTICATION ENDPOINTS

### 1. Login

**Endpoint:** `GET /login`

**Deskripsi:** Menampilkan halaman login

**Authentication:** Tidak diperlukan

**Response:** HTML view form login

---

### 2. Process Login

**Endpoint:** `POST /login`

**Deskripsi:** Memproses autentikasi user

**Authentication:** Tidak diperlukan

**Request Body:**
```json
{
  "login_id": "string",  // Bisa username atau email
  "password": "string"
}
```

**Response:**
- **Success (302 Redirect):** Redirect ke `/dashboard`
- **Error:** Redirect ke `/login` dengan flash message error

**Error Messages:**
- `Username/Email tidak ditemukan.` - User tidak ada di database
- `Password salah.` - Password tidak match
- `Akun Anda non-aktif. Hubungi Administrator.` - User di-disable

**Session Data Set:**
```php
[
  'id_user' => int,
  'username' => string,
  'fullname' => string,
  'id_role' => int,
  'active_kegiatan_id' => int|null,
  'active_kegiatan_name' => string,
  'is_logged_in' => true
]
```

---

### 3. Register

**Endpoint:** `GET /register`

**Deskripsi:** Menampilkan halaman registrasi mitra

**Authentication:** Tidak diperlukan

**Response:** HTML view form registrasi

---

### 4. Process Register

**Endpoint:** `POST /register`

**Deskripsi:** Membuat akun mitra baru (PCL atau Pengolahan)

**Authentication:** Tidak diperlukan

**Request Body:**
```json
{
  "fullname": "string (3-100 chars)",
  "username": "string (3-50 chars, unique)",
  "email": "string (valid email, unique)",
  "password": "string (min 6 chars)",
  "confpassword": "string (must match password)",
  "nik_ktp": "string (16 digits)",
  "sobat_id": "string",
  "id_role": "int (3=PCL, 4=Pengolahan)"
}
```

**Validation Rules:**
- `fullname`: required, min_length[3], max_length[100]
- `username`: required, min_length[3], max_length[50], is_unique[users.username]
- `email`: required, valid_email, is_unique[users.email]
- `password`: required, min_length[6]
- `confpassword`: matches[password]
- `nik_ktp`: required, min_length[16], max_length[16], numeric
- `sobat_id`: required
- `id_role`: required, in_list[3,4]

**Response:**
- **Success (302 Redirect):** Redirect ke `/login` dengan flash message success
- **Error:** Redirect back dengan validation errors

**Auto-Processing:**
- Password otomatis di-hash menggunakan Bcrypt
- `is_active` otomatis diset ke 1

---

### 5. Logout

**Endpoint:** `GET /logout`

**Deskripsi:** Menghapus session dan logout user

**Authentication:** Diperlukan

**Response:** Redirect ke `/login`

---

## 📊 DASHBOARD ENDPOINTS

### 1. Main Dashboard

**Endpoint:** `GET /dashboard`

**Deskripsi:** Menampilkan dashboard utama dengan statistik

**Authentication:** Diperlukan (filter: `auth`)

**Query Parameters:**
- `kegiatan` (optional): Filter berdasarkan ID kegiatan

**Response Data:**
```php
[
  'title' => 'Dashboard',
  'list_kegiatan' => Array of active activities,
  'selected_kegiatan' => int|null,
  'stat_total' => int,      // Total dokumen
  'stat_error' => int,      // Dokumen dengan status Error
  'stat_entry' => int,      // Dokumen dengan status Sudah Entry
  'ranking' => Array of top 5 error contributors
]
```

**Ranking Query:**
```sql
SELECT users.fullname, COUNT(dokumen_survei.id_dokumen) as error_count
FROM users
JOIN dokumen_survei ON users.id_user = dokumen_survei.id_petugas_pendataan
WHERE dokumen_survei.status = 'Error'
GROUP BY dokumen_survei.id_petugas_pendataan
ORDER BY error_count DESC
LIMIT 5
```

---

## 📄 DOKUMEN ENDPOINTS

### 1. List Dokumen

**Endpoint:** `GET /dokumen`

**Deskripsi:** Menampilkan daftar dokumen survei (role-filtered)

**Authentication:** Diperlukan (filter: `auth`)

**Role-Based Access:**
- **PCL (Role 3):** Hanya melihat dokumen miliknya sendiri
- **Admin (Role 1), Pengolahan (Role 4), Supervisor:** Melihat semua dokumen

**Response Data:**
```php
[
  'title' => 'Dokumen Survei',
  'dokumen' => Array of documents with relations,
  'role_id' => int
]
```

**Document Data Structure:**
```php
[
  'id_dokumen' => int,
  'id_kegiatan' => int,
  'nama_kegiatan' => string,
  'kode_wilayah' => string,
  'id_petugas_pendataan' => int,
  'nama_pcl' => string,
  'processed_by' => int|null,
  'nama_pengolah' => string|null,
  'status' => string,  // Uploaded, Sudah Entry, Error, Valid
  'pernah_error' => int,  // 0 or 1
  'tanggal_setor' => date,
  'created_at' => timestamp,
  'updated_at' => timestamp
]
```

---

### 2. Create Dokumen Form

**Endpoint:** `GET /dokumen/create`

**Deskripsi:** Menampilkan form untuk setor dokumen baru

**Authentication:** Diperlukan (filter: `auth`)

**Role-Based Access:**
- **Admin (Role 1), PCL (Role 3):** Diizinkan
- **Role lainnya:** Ditolak, redirect ke `/dokumen`

**Response Data:**
```php
[
  'title' => 'Setor Dokumen',
  'kegiatan' => Array of active activities
]
```

---

### 3. Store Dokumen

**Endpoint:** `POST /dokumen/store`

**Deskripsi:** Menyimpan dokumen survei baru

**Authentication:** Diperlukan (filter: `auth`)

**Role-Based Access:**
- **Admin (Role 1), PCL (Role 3):** Diizinkan
- **Role lainnya:** Ditolak, redirect ke `/dokumen`

**Request Body:**
```json
{
  "id_kegiatan": "int (required)",
  "kode_wilayah": "string (required, 3-20 chars)",
  "tanggal_setor": "date (required, valid_date)"
}
```

**Validation Rules:**
- `id_kegiatan`: required, integer
- `kode_wilayah`: required, min_length[3], max_length[20]
- `tanggal_setor`: required, valid_date

**Auto-Processing:**
- `id_petugas_pendataan` otomatis diset ke ID user yang sedang login
- `status` otomatis diset ke 'Uploaded'

**Response:**
- **Success (302 Redirect):** Redirect ke `/dokumen` dengan flash message success
- **Error:** Redirect back dengan validation errors

---

### 4. Mark as Entry

**Endpoint:** `POST /dokumen/mark-entry/{id}`

**Deskripsi:** Menandai dokumen sebagai sudah di-entry

**Authentication:** Diperlukan (filter: `auth`)

**URL Parameters:**
- `id` (required): ID dokumen

**Role-Based Access:**
- **Admin (Role 1), Pengolahan (Role 4):** Diizinkan
- **Role lainnya:** Ditolak, redirect ke `/dokumen`

**Request Body:** Tidak ada (CSRF token required)

**Auto-Processing:**
- `status` diupdate ke 'Sudah Entry'
- `processed_by` diset ke ID user yang sedang login

**Response:**
- **Success (302 Redirect):** Redirect ke `/dokumen` dengan flash message success
- **Error:** Redirect ke `/dokumen` dengan flash message error

---

### 5. Report Error

**Endpoint:** `POST /dokumen/report-error`

**Deskripsi:** Melaporkan anomali/error pada dokumen

**Authentication:** Diperlukan (filter: `auth`)

**Role-Based Access:**
- **Admin (Role 1), Pengolahan (Role 4):** Diizinkan
- **Role lainnya:** Ditolak, redirect ke `/dokumen`

**Request Body:**
```json
{
  "id_dokumen": "int (required)",
  "jenis_error": "string (required, max 100 chars)",
  "keterangan": "string (required)"
}
```

**Validation Rules:**
- `id_dokumen`: required, integer
- `jenis_error`: required, max_length[100]
- `keterangan`: required

**Auto-Processing:**
1. Membuat entry baru di tabel `anomali_log`
2. Update dokumen:
   - `status` diupdate ke 'Error'
   - `processed_by` diset ke ID user yang sedang login
   - `pernah_error` diset ke 1 (permanent flag)

**Response:**
- **Success (302 Redirect):** Redirect ke `/dokumen` dengan flash message error
- **Error:** Redirect back dengan validation errors

**Common Error Types:**
- "Data Tidak Lengkap"
- "Format Salah"
- "Inkonsistensi Data"
- "Nilai di Luar Range"
- "Duplikasi Data"

---

## 📅 KEGIATAN ENDPOINTS

### 1. List Kegiatan

**Endpoint:** `GET /kegiatan`

**Deskripsi:** Menampilkan daftar semua kegiatan survei

**Authentication:** Diperlukan (filter: `auth`)

**Role-Based Access:**
- **Admin (Role 1):** Diizinkan
- **Role lainnya:** Ditolak, redirect ke `/dashboard`

**Response Data:**
```php
[
  'title' => 'Master Kegiatan',
  'kegiatan' => Array of all activities
]
```

---

### 2. Store Kegiatan

**Endpoint:** `POST /kegiatan/store`

**Deskripsi:** Membuat kegiatan survei baru

**Authentication:** Diperlukan (filter: `auth`)

**Role-Based Access:**
- **Admin (Role 1):** Diizinkan
- **Role lainnya:** Ditolak, redirect ke `/dashboard`

**Request Body:**
```json
{
  "nama_kegiatan": "string (required, 3-100 chars)",
  "kode_kegiatan": "string (required, 3-20 chars, unique)",
  "tanggal_mulai": "date (required, valid_date)",
  "tanggal_selesai": "date (required, valid_date)"
}
```

**Validation Rules:**
- `nama_kegiatan`: required, min_length[3], max_length[100]
- `kode_kegiatan`: required, min_length[3], max_length[20], is_unique[master_kegiatan.kode_kegiatan]
- `tanggal_mulai`: required, valid_date
- `tanggal_selesai`: required, valid_date

**Auto-Processing:**
- `kode_kegiatan` otomatis di-convert ke uppercase
- `status` otomatis diset ke 'Aktif'

**Response:**
- **Success (302 Redirect):** Redirect ke `/kegiatan` dengan flash message success
- **Error:** Redirect back dengan validation errors

---

### 3. Update Status Kegiatan

**Endpoint:** `POST /kegiatan/status/{id}`

**Deskripsi:** Mengubah status kegiatan (Aktif/Selesai)

**Authentication:** Diperlukan (filter: `auth`)

**URL Parameters:**
- `id` (required): ID kegiatan

**Role-Based Access:**
- **Admin (Role 1):** Diizinkan
- **Role lainnya:** Ditolak, redirect ke `/dashboard`

**Request Body:**
```json
{
  "status": "string (Aktif or Selesai)"
}
```

**Response:**
- **Success (302 Redirect):** Redirect ke `/kegiatan` dengan flash message success

---

### 4. Delete Kegiatan

**Endpoint:** `GET /kegiatan/delete/{id}`

**Deskripsi:** Menghapus kegiatan beserta semua dokumen terkait (CASCADE)

**Authentication:** Diperlukan (filter: `auth`)

**URL Parameters:**
- `id` (required): ID kegiatan

**Role-Based Access:**
- **Admin (Role 1):** Diizinkan
- **Role lainnya:** Ditolak, redirect ke `/dashboard`

**Cascade Delete:**
- Menghapus semua dokumen_survei terkait
- Menghapus semua anomali_log terkait

**Response:**
- **Success (302 Redirect):** Redirect ke `/kegiatan` dengan flash message success

---

## 📈 LAPORAN ENDPOINTS

### 1. Laporan PCL

**Endpoint:** `GET /laporan/pcl`

**Deskripsi:** Menampilkan laporan kinerja PCL

**Authentication:** Diperlukan (filter: `auth`)

**Role-Based Access:**
- **Admin (Role 1):** Diizinkan
- **Role lainnya:** Ditolak, redirect ke `/dashboard`

**Query Parameters:**
- `kegiatan` (optional): Filter berdasarkan ID kegiatan

**Response Data:**
```php
[
  'title' => 'Laporan Kinerja PCL',
  'list_kegiatan' => Array of all activities,
  'selected_kegiatan' => int|null,
  'laporan' => Array of PCL performance data
]
```

**Performance Metrics:**
```sql
SELECT 
  users.fullname,
  users.nik_ktp,
  users.sobat_id,
  COUNT(dokumen_survei.id_dokumen) as total_dokumen,
  SUM(CASE WHEN dokumen_survei.status = "Error" OR dokumen_survei.pernah_error = 1 THEN 1 ELSE 0 END) as total_error
FROM users
LEFT JOIN dokumen_survei ON dokumen_survei.id_petugas_pendataan = users.id_user
WHERE users.id_role = 3
GROUP BY users.id_user
ORDER BY total_dokumen DESC
```

---

### 2. Laporan Pengolahan

**Endpoint:** `GET /laporan/pengolahan`

**Deskripsi:** Menampilkan laporan kinerja petugas pengolahan

**Authentication:** Diperlukan (filter: `auth`)

**Role-Based Access:**
- **Admin (Role 1):** Diizinkan
- **Role lainnya:** Ditolak, redirect ke `/dashboard`

**Query Parameters:**
- `kegiatan` (optional): Filter berdasarkan ID kegiatan

**Response Data:**
```php
[
  'title' => 'Laporan Kinerja Pengolahan',
  'list_kegiatan' => Array of all activities,
  'selected_kegiatan' => int|null,
  'laporan' => Array of processor performance data
]
```

**Performance Metrics:**
```sql
SELECT 
  users.fullname,
  users.sobat_id,
  COUNT(dokumen_survei.id_dokumen) as total_entry
FROM users
LEFT JOIN dokumen_survei ON dokumen_survei.processed_by = users.id_user
WHERE users.id_role = 4
GROUP BY users.id_user
ORDER BY total_entry DESC
```

---

## 📊 MONITORING ENDPOINTS

### 1. Monitoring Dashboard

**Endpoint:** `GET /monitoring`

**Deskripsi:** Menampilkan dashboard monitoring komprehensif

**Authentication:** Diperlukan (filter: `auth`)

**Query Parameters:**
- `kegiatan` (optional): Filter berdasarkan ID kegiatan

**Response Data:**
```php
[
  'title' => 'Monitoring & Evaluasi',
  'list_kegiatan' => Array of active activities,
  'selected_kegiatan' => int|null,
  
  // Statistics
  'stat_total' => int,      // Total dokumen
  'stat_entry' => int,      // Sudah Entry
  'stat_error' => int,      // Active Errors
  'stat_clean' => int,      // Data Clean (no error, entered)
  
  // Tables
  'ranking_error' => Array of top 5 error contributors,
  'eval_pcl' => Array of PCL evaluation,
  'eval_proc' => Array of processor evaluation,
  'eval_spv' => Array of supervisor evaluation
]
```

**Supervisor Evaluation Query:**
```sql
SELECT 
  spv.fullname,
  COUNT(DISTINCT pcl.id_user) as team_size,
  COUNT(doc.id_dokumen) as total_team_docs,
  SUM(CASE WHEN doc.status = "Error" OR doc.pernah_error = 1 THEN 1 ELSE 0 END) as total_team_errors
FROM users as spv
LEFT JOIN users as pcl ON pcl.id_supervisor = spv.id_user
LEFT JOIN dokumen_survei as doc ON doc.id_petugas_pendataan = pcl.id_user
WHERE spv.id_role = 5  -- PML
GROUP BY spv.id_user
```

---

## 🔒 SECURITY & AUTHENTICATION

### CSRF Protection

Semua POST request harus menyertakan CSRF token:

```html
<?= csrf_field() ?>
```

Atau dalam AJAX:
```javascript
headers: {
  'X-CSRF-TOKEN': csrf_token
}
```

### Session Management

**Session Configuration:** [`app/Config/Session.php`](app/Config/Session.php)

**Session Data Structure:**
```php
[
  'id_user' => int,
  'username' => string,
  'fullname' => string,
  'id_role' => int,
  'active_kegiatan_id' => int|null,
  'active_kegiatan_name' => string,
  'is_logged_in' => true
]
```

### Role-Based Access Control (RBAC)

| Role ID | Role Name | Access Level |
|---------|-----------|--------------|
| 1 | Administrator | Full access |
| 3 | Petugas Pendataan (PCL) | Create documents, view own documents |
| 4 | Petugas Pengolahan | Process documents, report errors |
| 5 | Pengawas Lapangan (PML) | Monitor PCL team |
| 6 | Pengawas Pengolahan | Monitor processing team |

### Auth Filter

**Filter Name:** `auth`

**Implementation:** [`app/Filters/AuthFilter.php`](app/Filters/AuthFilter.php)

**Behavior:**
- Cek `session()->get('is_logged_in')`
- Jika false, redirect ke `/login`

---

## 📝 ERROR HANDLING

### HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 302 | Redirect (success or error) |
| 404 | Not Found |
| 500 | Server Error |

### Flash Messages

**Success Message:**
```php
session()->setFlashdata('success', 'Pesan sukses');
```

**Error Message:**
```php
session()->setFlashdata('error', 'Pesan error');
```

**Validation Errors:**
```php
session()->setFlashdata('errors', $this->validator->getErrors());
```

### Common Error Scenarios

1. **Authentication Failed**
   - User tidak ditemukan
   - Password salah
   - Akun non-aktif

2. **Authorization Failed**
   - Role tidak memiliki akses
   - Redirect dengan pesan "Akses ditolak"

3. **Validation Failed**
   - Input tidak memenuhi rules
   - Redirect back dengan error details

---

## 🔄 DATA FLOW DIAGRAMS

### Login Flow

```
User → POST /login → Auth::loginProcess()
  ↓
Check user exists
  ↓
Check is_active = 1
  ↓
Verify password (Bcrypt)
  ↓
Set session data
  ↓
Fetch active kegiatan
  ↓
Redirect to /dashboard
```

### Document Creation Flow

```
PCL → GET /dokumen/create → Dokumen::create()
  ↓
Display form with active kegiatan
  ↓
PCL → POST /dokumen/store → Dokumen::store()
  ↓
Validate input
  ↓
Save to dokumen_survei (auto-assign PCL, status='Uploaded')
  ↓
Redirect to /dokumen with success message
```

### Error Reporting Flow

```
Processor → POST /dokumen/report-error → Dokumen::reportError()
  ↓
Validate input
  ↓
Create anomali_log entry
  ↓
Update dokumen_survei (status='Error', pernah_error=1)
  ↓
Redirect to /dokumen with error message
```

---

## 📚 ADDITIONAL RESOURCES

- [CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/)
- [Database Schema Documentation](./database-schema.md)
- [Main Documentation](../README.md)
- [Troubleshooting Guide](./troubleshooting.md)

---

## 📝 CHANGELOG

### Version 1.0.0 (2026-01-28)
- Initial API documentation
- Complete endpoint coverage
- Security and authentication details
- Error handling documentation
