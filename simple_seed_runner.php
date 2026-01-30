<?php
// File: simple_seed_runner.php
// File sederhana untuk menjalankan seeder data dummy

// Atur timezone
date_default_timezone_set('Asia/Jakarta');

// Mulai output
echo "<h2>Menjalankan Seeder Data Dummy...</h2>\n";

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Baca konfigurasi dari .env
$envContent = file_get_contents(__DIR__ . '/.env');
$lines = explode("\n", $envContent);

$config = [];
foreach ($lines as $line) {
    if (strpos($line, '=') !== false && !preg_match('/^[\s]*#/', $line)) {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // Hapus kutipan jika ada
        $value = trim($value, '"\'');
        $config[$key] = $value;
    }
}

// Ambil konfigurasi database dari .env
$host = $config['database.default.hostname'] ?? 'localhost';
$username = $config['database.default.username'] ?? 'root';
$password = $config['database.default.password'] ?? '';
$database = $config['database.default.database'] ?? 'monika';
$driver = $config['database.default.DBDriver'] ?? 'MySQLi';
$port = $config['database.default.port'] ?? 3306;

try {
    // Buat koneksi PDO
    $dsn = "$driver:host=$host;dbname=$database;charset=utf8mb4;port=$port";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "<p>Koneksi ke database berhasil.</p>\n";
    
    // Jalankan seeder secara manual
    echo "<p>Menjalankan RolesSeeder...</p>\n";
    $pdo->prepare("DELETE FROM roles")->execute();
    $rolesData = [
        ['id_role' => 1, 'role_name' => 'Administrator', 'description' => 'Super user with full access'],
        ['id_role' => 3, 'role_name' => 'Petugas Pendataan (PCL)', 'description' => 'Mitra Lapangan - Field Enumerator'],
        ['id_role' => 4, 'role_name' => 'Petugas Pengolahan', 'description' => 'Mitra Entry/Editing - Data Processor'],
        ['id_role' => 5, 'role_name' => 'Pengawas Lapangan (PML)', 'description' => 'Field Supervisor'],
        ['id_role' => 6, 'role_name' => 'Pengawas Pengolahan', 'description' => 'Processing Supervisor']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO roles (id_role, role_name, description) VALUES (?, ?, ?)");
    foreach ($rolesData as $role) {
        $stmt->execute([$role['id_role'], $role['role_name'], $role['description']]);
    }
    
    echo "<p>Menjalankan MasterKegiatanSeeder...</p>\n";
    $pdo->prepare("DELETE FROM master_kegiatan")->execute();
    $kegiatanData = [
        ['nama_kegiatan' => 'Sakernas Februari 2026', 'kode_kegiatan' => 'SAK26FEB', 'tanggal_mulai' => '2026-02-01', 'tanggal_selesai' => '2026-02-28', 'status' => 'Aktif'],
        ['nama_kegiatan' => 'Susenas Maret 2026', 'kode_kegiatan' => 'SSN26MAR', 'tanggal_mulai' => '2026-03-01', 'tanggal_selesai' => '2026-03-31', 'status' => 'Aktif'],
        ['nama_kegiatan' => 'Pemutakhiran Registrasi Sosial Ekonomi (RSE) April 2026', 'kode_kegiatan' => 'RSE26APR', 'tanggal_mulai' => '2026-04-01', 'tanggal_selesai' => '2026-04-30', 'status' => 'Aktif'],
        ['nama_kegiatan' => 'Survei Triwulanan Angkatan Kerja Nasional (Sakernas) Juni 2026', 'kode_kegiatan' => 'STAKERNAS26JUN', 'tanggal_mulai' => '2026-06-01', 'tanggal_selesai' => '2026-06-30', 'status' => 'Aktif'],
        ['nama_kegiatan' => 'Survei Sosial Ekonomi Nasional (Susenas) September 2026', 'kode_kegiatan' => 'SSN26SEP', 'tanggal_mulai' => '2026-09-01', 'tanggal_selesai' => '2026-09-30', 'status' => 'Aktif']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO master_kegiatan (nama_kegiatan, kode_kegiatan, tanggal_mulai, tanggal_selesai, status) VALUES (?, ?, ?, ?, ?)");
    foreach ($kegiatanData as $kegiatan) {
        $stmt->execute([$kegiatan['nama_kegiatan'], $kegiatan['kode_kegiatan'], $kegiatan['tanggal_mulai'], $kegiatan['tanggal_selesai'], $kegiatan['status']]);
    }
    
    echo "<p>Menjalankan UsersSeeder...</p>\n";
    // Hapus data pengguna kecuali admin (id=1)
    $pdo->prepare("DELETE FROM users WHERE id_user != 1")->execute();
    $usersData = [
        [
            'fullname' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john.doe@monika.test',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'nik_ktp' => '1234567890123456',
            'sobat_id' => 'SOB001',
            'id_role' => 3,
            'id_supervisor' => null,
            'is_active' => 1
        ],
        [
            'fullname' => 'Jane Smith',
            'username' => 'janesmith',
            'email' => 'jane.smith@monika.test',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'nik_ktp' => '1234567890123457',
            'sobat_id' => 'SOB002',
            'id_role' => 4,
            'id_supervisor' => null,
            'is_active' => 1
        ],
        [
            'fullname' => 'Robert Johnson',
            'username' => 'robertj',
            'email' => 'robert.johnson@monika.test',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'nik_ktp' => '1234567890123458',
            'sobat_id' => 'SOB003',
            'id_role' => 5,
            'id_supervisor' => null,
            'is_active' => 1
        ],
        [
            'fullname' => 'Emily Davis',
            'username' => 'emilyd',
            'email' => 'emily.davis@monika.test',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'nik_ktp' => '1234567890123459',
            'sobat_id' => 'SOB004',
            'id_role' => 6,
            'id_supervisor' => null,
            'is_active' => 1
        ],
        [
            'fullname' => 'Michael Wilson',
            'username' => 'michaelw',
            'email' => 'michael.wilson@monika.test',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'nik_ktp' => '1234567890123460',
            'sobat_id' => 'SOB005',
            'id_role' => 3,
            'id_supervisor' => 3,
            'is_active' => 1
        ],
        [
            'fullname' => 'Sarah Brown',
            'username' => 'sarahb',
            'email' => 'sarah.brown@monika.test',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'nik_ktp' => '1234567890123461',
            'sobat_id' => 'SOB006',
            'id_role' => 4,
            'id_supervisor' => 4,
            'is_active' => 1
        ]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO users (fullname, username, email, password, nik_ktp, sobat_id, id_role, id_supervisor, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($usersData as $user) {
        $stmt->execute([
            $user['fullname'], $user['username'], $user['email'], 
            $user['password'], $user['nik_ktp'], $user['sobat_id'], 
            $user['id_role'], $user['id_supervisor'], $user['is_active']
        ]);
    }
    
    echo "<p>Menjalankan DokumenSurveiSeeder...</p>\n";
    $pdo->prepare("DELETE FROM dokumen_survei")->execute();
    
    // Generate 50 dokumen survei dummy
    $dokumenStmt = $pdo->prepare(
        "INSERT INTO dokumen_survei (id_kegiatan, kode_wilayah, id_petugas_pendataan, processed_by, status, pernah_error, tanggal_setor) VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    
    for ($i = 1; $i <= 50; $i++) {
        $id_kegiatan = rand(1, 5);
        $id_petugas_pendataan = rand(2, 6);
        $processed_by = rand(2, 6);
        $status_list = ['Uploaded', 'Sudah Entry', 'Error', 'Valid'];
        $status = $status_list[array_rand($status_list)];
        $tanggal_setor = null;
        if ($status !== 'Uploaded') {
            $tanggal_setor = date('Y-m-d', strtotime('+'.rand(1, 30).' days'));
        }
        $kode_wilayah = 'ID-' . sprintf('%03d', $i);
        
        $dokumenStmt->execute([
            $id_kegiatan, $kode_wilayah, $id_petugas_pendataan, 
            $processed_by, $status, rand(0, 1), $tanggal_setor
        ]);
    }
    
    echo "<p>Menjalankan AnomaliLogSeeder...</p>\n";
    $pdo->prepare("DELETE FROM anomali_log")->execute();
    
    // Generate 30 anomali log dummy
    $anomaliStmt = $pdo->prepare(
        "INSERT INTO anomali_log (id_dokumen, id_petugas_pengolahan, jenis_error, keterangan) VALUES (?, ?, ?, ?)"
    );
    
    $jenis_errors = [
        'Format file tidak sesuai',
        'Data tidak lengkap',
        'Nilai tidak valid',
        'Duplikasi entri',
        'Tanggal tidak valid',
        'Wilayah tidak ditemukan',
        'Kode kegiatan salah',
        'NIK tidak valid'
    ];
    
    $keterangan_list = [
        'Ditemukan kesalahan pada field nomor rumah tangga',
        'Nilai pada kolom pendapatan tidak sesuai format',
        'Terdapat data ganda untuk kode wilayah yang sama',
        'Tanggal pelaksanaan survei tidak sesuai dengan jadwal kegiatan',
        'Format dokumen tidak sesuai standar yang ditentukan',
        'Kesalahan dalam pengisian data demografi',
        'Nilai ekstrem ditemukan pada variabel pengeluaran',
        'Kode wilayah tidak terdaftar dalam referensi'
    ];
    
    for ($i = 1; $i <= 30; $i++) {
        $id_dokumen = rand(1, 50);
        $id_petugas_pengolahan = rand(2, 6);
        $jenis_error = $jenis_errors[array_rand($jenis_errors)];
        $keterangan = $keterangan_list[array_rand($keterangan_list)];
        
        $anomaliStmt->execute([$id_dokumen, $id_petugas_pengolahan, $jenis_error, $keterangan]);
    }
    
    echo "<p><strong>Seeder berhasil dijalankan!</strong></p>\n";
    echo "<p>Data dummy telah dimasukkan ke dalam database.</p>\n";
    
} catch (PDOException $e) {
    echo "<p><strong>Error PDO:</strong> " . $e->getMessage() . "</p>\n";
} catch (Exception $e) {
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>\n";
}
?>