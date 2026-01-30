# DOKUMENTASI ARSITEKTUR FRONTEND MONIKA

## 📋 OVERVIEW

Dokumentasi ini menyediakan analisis mendalam tentang arsitektur frontend aplikasi MONIKA. Frontend menggunakan template AdminLTE 3.2 berbasis Bootstrap 4 dengan pendekatan server-side rendering menggunakan PHP views dari CodeIgniter 4.

**Tech Stack:**
- **HTML5**: Markup structure
- **CSS3**: Styling dengan Bootstrap 4.6.1
- **JavaScript**: Vanilla JS dengan jQuery 3.6.0
- **UI Framework**: AdminLTE 3.2
- **Data Tables**: DataTables 1.11.5
- **Icons**: Font Awesome 5.15.4

---

## 🏗️ ARSITEKTUR FRONTEND

### Pattern: Server-Side Rendering (SSR)

Aplikasi MONIKA menggunakan pendekatan server-side rendering di mana:
1. Views dirender di server menggunakan PHP
2. HTML lengkap dikirim ke client
3. JavaScript digunakan untuk interaktivitas minimal

### Layer Architecture

```
┌─────────────────────────────────────┐
│     Browser (Client Side)           │
│  ┌───────────────────────────────┐  │
│  │  HTML/CSS/JS Resources       │  │
│  │  - AdminLTE CSS              │  │
│  │  - Bootstrap JS              │  │
│  │  - DataTables                │  │
│  │  - jQuery                    │  │
│  └───────────────────────────────┘  │
└─────────────────────────────────────┘
              ↓ HTTP Request
┌─────────────────────────────────────┐
│     CodeIgniter 4 (Server Side)     │
│  ┌───────────────────────────────┐  │
│  │  Controllers                  │  │
│  │  - Auth                       │  │
│  │  - Home                       │  │
│  │  - Dokumen                    │  │
│  │  - Kegiatan                   │  │
│  │  - Laporan                    │  │
│  │  - Monitoring                 │  │
│  └───────────────────────────────┘  │
│              ↓                       │
│  ┌───────────────────────────────┐  │
│  │  Views (PHP Templates)        │  │
│  │  - auth/                     │  │
│  │  - dashboard/                │  │
│  │  - dokumen/                  │  │
│  │  - kegiatan/                 │  │
│  │  - laporan/                  │  │
│  │  - monitoring/               │  │
│  └───────────────────────────────┘  │
└─────────────────────────────────────┘
```

---

## 📁 STRUKTUR FILE FRONTEND

### Directory Structure

```
app/Views/
├── auth/
│   ├── login.php              # Halaman login
│   └── register.php           # Halaman registrasi
├── dashboard/
│   └── index.php             # Dashboard utama
├── dokumen/
│   ├── index.php             # List dokumen
│   ├── create.php            # Form setor dokumen
│   └── modal_error.php       # Modal lapor error
├── kegiatan/
│   ├── index.php             # List kegiatan
│   └── modal_create.php      # Modal create kegiatan
├── laporan/
│   ├── pcl.php               # Laporan PCL
│   └── pengolahan.php        # Laporan Pengolahan
├── monitoring/
│   └── dashboard.php         # Dashboard monitoring
└── errors/
    ├── html/
    │   ├── error_404.php
    │   ├── error_exception.php
    │   └── production.php
    └── cli/
        ├── error_404.php
        └── error_exception.php
```

---

## 🎨 KOMPONEN UI UTAMA

### 1. Layout Components

#### Navbar Component
**Location:** Embedded in each view

**Features:**
- Toggle sidebar button
- User info display
- Logout button

**Code Structure:**
```html
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" href="/logout" role="button">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </li>
  </ul>
</nav>
```

#### Sidebar Component
**Location:** Embedded in each view

**Features:**
- Brand link
- User panel
- Navigation menu (role-based)

**Code Structure:**
```html
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="/dashboard" class="brand-link">
    <span class="brand-text font-weight-light">MONIKA System</span>
  </a>
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info">
        <a href="#" class="d-block"><?= session()->get('fullname') ?></a>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
        <!-- Menu items based on role -->
      </ul>
    </nav>
  </div>
</aside>
```

**Role-Based Menu:**
```php
<?php if(session()->get('id_role') == 1): ?>
  <li class="nav-item">
    <a href="/kegiatan" class="nav-link">
      <i class="nav-icon fas fa-calendar-alt"></i>
      <p>Master Kegiatan</p>
    </a>
  </li>
<?php endif; ?>
```

#### Footer Component
**Location:** Embedded in each view

**Code Structure:**
```html
<footer class="main-footer">
  <div class="float-right d-none d-sm-inline">Beta Version</div>
  <strong>Copyright &copy; 2026 <a href="#">MONIKA</a>.</strong> All rights reserved.
</footer>
```

---

### 2. Data Display Components

#### DataTables Component
**Location:** [`app/Views/dokumen/index.php`](app/Views/dokumen/index.php:120)

**Features:**
- Responsive design
- Search functionality
- Pagination
- Export options (copy, csv, excel, pdf, print)

**Initialization:**
```javascript
$("#table-dokumen").DataTable({
  "responsive": true,
  "lengthChange": false,
  "autoWidth": false,
  "buttons": ["copy", "csv", "excel", "pdf", "print"]
});
```

**Table Structure:**
```html
<table id="table-dokumen" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Kegiatan</th>
      <th>Wilayah</th>
      <th>PCL</th>
      <th>Tgl Setor</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <!-- Data rows -->
  </tbody>
</table>
```

#### Status Badge Component
**Location:** [`app/Views/dokumen/index.php`](app/Views/dokumen/index.php:141)

**Logic:**
```php
<?php 
  $badge = 'secondary';
  if($d['status'] == 'Uploaded') $badge = 'info';
  if($d['status'] == 'Sudah Entry') $badge = 'primary';
  if($d['status'] == 'Error') $badge = 'danger';
  if($d['status'] == 'Valid') $badge = 'success';
?>
<span class="badge badge-<?= $badge ?>"><?= $d['status'] ?></span>
```

**Badge Colors:**
- `Uploaded`: Blue (info)
- `Sudah Entry`: Blue (primary)
- `Error`: Red (danger)
- `Valid`: Green (success)

---

### 3. Form Components

#### Login Form
**Location:** [`app/Views/auth/login.php`](app/Views/auth/login.php)

**Features:**
- Username/Email input
- Password input
- Flash message display
- CSRF protection

**Code Structure:**
```html
<form action="/login" method="post">
  <?= csrf_field() ?>
  <div class="form-group">
    <label>Username/Email</label>
    <input type="text" name="login_id" class="form-control" required>
  </div>
  <div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary btn-block">Login</button>
</form>
```

#### Register Form
**Location:** [`app/Views/auth/register.php`](app/Views/auth/register.php)

**Fields:**
- Fullname
- Username
- Email
- Password
- Confirm Password
- NIK KTP
- Sobat ID
- Role (PCL/Pengolahan)

**Validation Display:**
```php
<?php if(session()->getFlashdata('errors')): ?>
  <div class="alert alert-danger">
    <ul>
      <?php foreach(session()->getFlashdata('errors') as $error): ?>
        <li><?= $error ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>
```

#### Document Creation Form
**Location:** [`app/Views/dokumen/create.php`](app/Views/dokumen/create.php)

**Fields:**
- Kegiatan (dropdown)
- Kode Wilayah
- Tanggal Setor

**Code Structure:**
```html
<form action="/dokumen/store" method="post">
  <?= csrf_field() ?>
  <div class="form-group">
    <label>Kegiatan</label>
    <select name="id_kegiatan" class="form-control" required>
      <option value="">Pilih Kegiatan</option>
      <?php foreach($kegiatan as $k): ?>
        <option value="<?= $k['id_kegiatan'] ?>"><?= $k['nama_kegiatan'] ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <!-- More fields -->
</form>
```

---

### 4. Modal Components

#### Error Reporting Modal
**Location:** [`app/Views/dokumen/modal_error.php`](app/Views/dokumen/modal_error.php)

**Features:**
- Dynamic document ID assignment
- Error type dropdown
- Description textarea
- CSRF protection

**Code Structure:**
```html
<div class="modal fade" id="modal-error">
  <div class="modal-dialog">
    <form action="/dokumen/report-error" method="post">
      <?= csrf_field() ?>
      <div class="modal-content">
        <div class="modal-header">
          <h4>Laporkan Anomali</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_dokumen" id="error_id_dokumen">
          <div class="form-group">
            <label>Jenis Error</label>
            <select name="jenis_error" class="form-control" required>
              <option value="">Pilih Jenis Error</option>
              <option value="Data Tidak Lengkap">Data Tidak Lengkap</option>
              <option value="Format Salah">Format Salah</option>
              <option value="Inkonsistensi Data">Inkonsistensi Data</option>
            </select>
          </div>
          <div class="form-group">
            <label>Keterangan Detail</label>
            <textarea name="keterangan" class="form-control" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Laporkan Error</button>
        </div>
      </div>
    </form>
  </div>
</div>
```

**JavaScript Integration:**
```javascript
$('.btn-error').on('click', function() {
  var id = $(this).data('id');
  $('#error_id_dokumen').val(id);
});
```

---

### 5. Dashboard Widgets

#### Statistics Cards
**Location:** [`app/Views/dashboard/index.php`](app/Views/dashboard/index.php:110)

**Types:**
- Total Dokumen (info/blue)
- Sudah Entry (success/green)
- Error (danger/red)
- Data Clean (warning/yellow)

**Code Structure:**
```html
<div class="col-lg-4 col-6">
  <div class="small-box bg-info">
    <div class="inner">
      <h3><?= $stat_total ?></h3>
      <p>Total Dokumen Masuk</p>
    </div>
    <div class="icon">
      <i class="fas fa-file"></i>
    </div>
  </div>
</div>
```

#### Ranking Table
**Location:** [`app/Views/dashboard/index.php`](app/Views/dashboard/index.php:148)

**Features:**
- Top 5 error contributors
- Badge display for error count

**Code Structure:**
```html
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Nama PCL</th>
      <th>Jumlah Error</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($ranking as $i => $r): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= $r['fullname'] ?></td>
        <td><span class="badge badge-danger"><?= $r['error_count'] ?></span></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
```

---

## 🎯 STATE MANAGEMENT

### Session-Based State

Aplikasi MONIKA menggunakan session-based state management di mana state disimpan di server.

**Session Data:**
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

**Accessing Session in Views:**
```php
<?= session()->get('fullname') ?>
<?= session()->get('id_role') ?>
```

### Flash Messages

Flash messages digunakan untuk notifikasi satu-time (success/error).

**Setting Flash Message:**
```php
session()->setFlashdata('success', 'Dokumen berhasil disetor.');
session()->setFlashdata('error', 'Anomali berhasil dilaporkan.');
```

**Displaying Flash Message:**
```php
<?php if(session()->getFlashdata('success')): ?>
  <div class="alert alert-success">
    <?= session()->getFlashdata('success') ?>
  </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
  <div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
  </div>
<?php endif; ?>
```

---

## 🔧 JAVASCRIPT FUNCTIONALITY

### DataTables Initialization

**Basic Setup:**
```javascript
$(function () {
  $("#table-dokumen").DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
    "buttons": ["copy", "csv", "excel", "pdf", "print"]
  });
});
```

**With Custom Options:**
```javascript
$("#table-pcl").DataTable({
  "pageLength": 5,
  "lengthChange": false
});
```

### Modal Interaction

**Pass Data to Modal:**
```javascript
$('.btn-error').on('click', function() {
  var id = $(this).data('id');
  $('#error_id_dokumen').val(id);
});
```

### Form Submission

**Confirm Before Submit:**
```html
<button type="submit" onclick="return confirm('Tandai sudah entry?')">
  <i class="fas fa-check"></i> Entry
</button>
```

---

## 📱 RESPONSIVE DESIGN

### Breakpoints

AdminLTE menggunakan Bootstrap breakpoints:
- **xs**: <576px
- **sm**: ≥576px
- **md**: ≥768px
- **lg**: ≥992px
- **xl**: ≥1200px

### Responsive Features

1. **Collapsible Sidebar**
   - Mobile: Hidden by default
   - Desktop: Visible by default
   - Toggle via hamburger menu

2. **Responsive Tables**
   - DataTables responsive mode
   - Horizontal scroll on mobile

3. **Responsive Grid**
   - `col-lg-4 col-6` for widgets
   - `col-md-6` for cards

---

## 🎨 STYLING & THEMING

### Color Scheme

**AdminLTE Colors:**
- Primary: Blue (#007bff)
- Success: Green (#28a745)
- Info: Cyan (#17a2b8)
- Warning: Yellow (#ffc107)
- Danger: Red (#dc3545)

### Custom Styling

**Badge Colors:**
```php
$badge = 'secondary';
if($d['status'] == 'Uploaded') $badge = 'info';
if($d['status'] == 'Sudah Entry') $badge = 'primary';
if($d['status'] == 'Error') $badge = 'danger';
if($d['status'] == 'Valid') $badge = 'success';
```

---

## 🔒 SECURITY IN FRONTEND

### CSRF Protection

**CSRF Token in Forms:**
```php
<?= csrf_field() ?>
```

**Output:**
```html
<input type="hidden" name="csrf_token_name" value="token_value">
```

### XSS Protection

**Auto-escaping in Views:**
```php
<?= $variable ?>  <!-- Auto-escaped -->
```

**Manual Escaping:**
```php
<?= esc($variable, 'html') ?>
```

### Input Validation

**Client-side Validation:**
```html
<input type="text" name="username" class="form-control" required>
```

**Server-side Validation:**
```php
$rules = [
  'username' => 'required|min_length[3]|max_length[50]'
];
```

---

## 📊 PERFORMANCE OPTIMIZATION

### CDN Resources

All external resources loaded from CDN:
- jQuery: `https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js`
- Bootstrap: `https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js`
- AdminLTE: `https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css`
- DataTables: `https://cdn.datatables.net/1.11.5/...`

### Optimization Tips

1. **Minify CSS/JS**: Use minified versions from CDN
2. **Lazy Loading**: Consider for large tables
3. **Caching**: Browser caching for static resources
4. **Compression**: Enable gzip compression on server

---

## 🧪 TESTING FRONTEND

### Manual Testing Checklist

- [ ] Login form validation
- [ ] Register form validation
- [ ] Document creation
- [ ] Error reporting modal
- [ ] DataTables functionality
- [ ] Responsive design on mobile
- [ ] Role-based menu display
- [ ] Flash message display
- [ ] CSRF token presence

### Browser Compatibility

- Chrome/Edge (Chromium)
- Firefox
- Safari
- Mobile browsers

---

## 📚 ADDITIONAL RESOURCES

- [AdminLTE Documentation](https://adminlte.io/docs/3.0/)
- [Bootstrap 4 Documentation](https://getbootstrap.com/docs/4.6/)
- [DataTables Documentation](https://datatables.net/)
- [Font Awesome Icons](https://fontawesome.com/v5.15.4/icons)

---

## 📝 CHANGELOG

### Version 1.0.0 (2026-01-28)
- Initial frontend architecture documentation
- Complete component documentation
- State management details
- Security considerations
