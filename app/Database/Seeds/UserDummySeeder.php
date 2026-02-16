<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

/**
 * User Dummy Seeder for MONIKA Application
 * 
 * Inserts 10 dummy users with default password Monika@2026!
 * Password hashed with bcrypt cost factor 12
 * 
 * @author System Operator
 * @date 2026-02-16
 */
class UserDummySeeder extends Seeder
{
    /**
     * Default password for all users
     */
    private const DEFAULT_PASSWORD = 'Monika@2026!';
    
    /**
     * Bcrypt cost factor
     */
    private const BCRYPT_COST = 12;

    /**
     * Run the seeder
     */
    public function run()
    {
        $startTime = microtime(true);
        $logFile = WRITEPATH . 'logs/user_seeder_' . date('Y-m-d_His') . '.log';
        $logContent = "=== USER DUMMY SEEDER LOG ===\n";
        $logContent .= "Date: " . date('Y-m-d H:i:s') . "\n";
        $logContent .= "Operator: System\n";
        $logContent .= "================================\n\n";

        // Check if table exists
        if (! $this->db->tableExists('users')) {
            $logContent .= "[ERROR] Tabel users tidak ditemukan.\n";
            $this->writeLog($logFile, $logContent);
            echo "[GAGAL] Tabel users tidak ditemukan.\n";
            return;
        }

        // Get table columns
        $columns = $this->db->getFieldNames('users');
        $has = static fn (string $name): bool => in_array($name, $columns, true);

        // Log available columns
        $logContent .= "Kolom tersedia: " . implode(', ', $columns) . "\n\n";

        // Check required columns
        $requiredColumns = ['username', 'email', 'password'];
        foreach ($requiredColumns as $col) {
            if (! $has($col)) {
                $logContent .= "[ERROR] Kolom wajib '{$col}' tidak ditemukan.\n";
                $this->writeLog($logFile, $logContent);
                echo "[GAGAL] Kolom wajib '{$col}' tidak ditemukan.\n";
                return;
            }
        }

        // Backup table before insertion
        $backupTable = 'users_backup_' . date('Ymd_His');
        try {
            $this->db->query("CREATE TABLE {$backupTable} AS SELECT * FROM users");
            $logContent .= "[BACKUP] Tabel backup dibuat: {$backupTable}\n";
            echo "[BACKUP] Tabel backup dibuat: {$backupTable}\n";
        } catch (\Exception $e) {
            $logContent .= "[WARNING] Gagal membuat backup: " . $e->getMessage() . "\n";
            echo "[WARNING] Gagal membuat backup: " . $e->getMessage() . "\n";
        }

        // Count existing users before insertion
        $countBefore = $this->db->table('users')->countAllResults();
        $logContent .= "Jumlah user sebelum insert: {$countBefore}\n\n";

        // Generate password hash with bcrypt cost 12
        $passwordHash = password_hash(self::DEFAULT_PASSWORD, PASSWORD_BCRYPT, ['cost' => self::BCRYPT_COST]);
        $logContent .= "Password hash generated (bcrypt cost " . self::BCRYPT_COST . ")\n";
        $logContent .= "Hash: {$passwordHash}\n\n";

        // User data sesuai dokumentasi AKUN_PENGGUNA.md
        // Role mapping berdasarkan tabel roles yang ada:
        // 1 = Administrator
        // 3 = Petugas Pendataan (PCL)
        // 4 = Petugas Pengolahan
        // 5 = Pengawas Lapangan (PML)
        // 6 = Pengawas Pengolahan
        $users = [
            [
                'nama_lengkap' => 'Nanang Pamungkas',
                'username' => 'admin_nanang',
                'email' => 'nanang.pamungkas@bps.go.id',
                'id_role' => 1, // Administrator
                'status' => 'Aktif',
            ],
            [
                'nama_lengkap' => 'Muhamad Suharsa',
                'username' => 'admin_suharsa',
                'email' => 'muhamad.suharsa@bps.go.id',
                'id_role' => 1, // Administrator
                'status' => 'Aktif',
            ],
            [
                'nama_lengkap' => 'Qudrat Jufrian',
                'username' => 'mod_qudrat',
                'email' => 'qudrat.jufrian@bps.go.id',
                'id_role' => 6, // Pengawas Pengolahan (Moderator)
                'status' => 'Aktif',
            ],
            [
                'nama_lengkap' => 'Arumita Hertriesa',
                'username' => 'mod_arumita',
                'email' => 'arumita.hertriesa@bps.go.id',
                'id_role' => 6, // Pengawas Pengolahan (Moderator)
                'status' => 'Aktif',
            ],
            [
                'nama_lengkap' => 'Putri Salsabhila',
                'username' => 'user_putri',
                'email' => 'putrisalsabhilafahira10@gmail.com',
                'id_role' => 3, // Petugas Pendataan (PCL)
                'status' => 'Aktif',
            ],
            [
                'nama_lengkap' => 'Astri Widarianti',
                'username' => 'user_astri',
                'email' => 'a.widarianti@gmail.com',
                'id_role' => 4, // Petugas Pengolahan
                'status' => 'Aktif',
            ],
            [
                'nama_lengkap' => 'Nur Ida Suryandari',
                'username' => 'user_nurida',
                'email' => 'nidasuryandari@gmail.com',
                'id_role' => 4, // Petugas Pengolahan
                'status' => 'Aktif',
            ],
            [
                'nama_lengkap' => 'Gilang Risqi',
                'username' => 'user_gilang',
                'email' => 'gilangrizqi2001@gmail.com',
                'id_role' => 3, // Petugas Pendataan (PCL)
                'status' => 'Aktif',
            ],
            [
                'nama_lengkap' => 'Dimas Rafendra',
                'username' => 'user_dimas',
                'email' => 'rafendra.dimas09@gmail.com',
                'id_role' => 3, // Petugas Pendataan (PCL)
                'status' => 'Non-Aktif',
            ],
            [
                'nama_lengkap' => 'Zainal Gufron',
                'username' => 'user_zainal',
                'email' => 'muhammadzainalgufron11@gmail.com',
                'id_role' => 4, // Petugas Pengolahan
                'status' => 'Aktif',
            ],
        ];

        $logContent .= "=== PROSES INSERT ===\n";
        $inserted = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($users as $index => $user) {
            $no = $index + 1;
            $logContent .= "\n[{$no}] Processing: {$user['username']}\n";
            
            // Check for duplicate username
            $existingUsername = $this->db->table('users')
                ->where('username', $user['username'])
                ->countAllResults();
            
            if ($existingUsername > 0) {
                $logContent .= "    [SKIP] Username sudah ada: {$user['username']}\n";
                echo "[SKIP] {$user['username']} - username sudah ada\n";
                $skipped++;
                continue;
            }

            // Check for duplicate email
            $existingEmail = $this->db->table('users')
                ->where('email', $user['email'])
                ->countAllResults();
            
            if ($existingEmail > 0) {
                $logContent .= "    [SKIP] Email sudah ada: {$user['email']}\n";
                echo "[SKIP] {$user['username']} - email sudah ada\n";
                $skipped++;
                continue;
            }

            // Build insert data
            $insertData = [
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => $passwordHash,
            ];

            // Add nama_lengkap if column exists
            if ($has('nama_lengkap')) {
                $insertData['nama_lengkap'] = $user['nama_lengkap'];
            } elseif ($has('nama')) {
                $insertData['nama'] = $user['nama_lengkap'];
            } elseif ($has('fullname')) {
                $insertData['fullname'] = $user['nama_lengkap'];
            }

            // Add id_role (required by foreign key)
            if ($has('id_role')) {
                $insertData['id_role'] = $user['id_role'];
            }

            // Add status if column exists
            if ($has('status')) {
                $insertData['status'] = $user['status'];
            } elseif ($has('status_aktif')) {
                $insertData['status_aktif'] = ($user['status'] === 'Aktif') ? 1 : 0;
            } elseif ($has('is_active')) {
                $insertData['is_active'] = ($user['status'] === 'Aktif') ? 1 : 0;
            }

            // Add timestamps
            $now = date('Y-m-d H:i:s');
            if ($has('created_at')) {
                $insertData['created_at'] = $now;
            }
            if ($has('updated_at')) {
                $insertData['updated_at'] = $now;
            }

            // Insert
            try {
                $result = $this->db->table('users')->insert($insertData);
                
                if ($result) {
                    $logContent .= "    [OK] Berhasil insert: {$user['username']} ({$user['email']})\n";
                    $logContent .= "    Data: " . json_encode($insertData, JSON_UNESCAPED_UNICODE) . "\n";
                    echo "[OK] {$user['username']} ({$user['email']}) berhasil diinsert.\n";
                    $inserted++;
                } else {
                    $error = $this->db->error();
                    $logContent .= "    [GAGAL] Database error: " . ($error['message'] ?? 'Unknown') . "\n";
                    echo "[GAGAL] {$user['username']}: " . ($error['message'] ?? 'Unknown error') . "\n";
                    $failed++;
                }
            } catch (\Exception $e) {
                $logContent .= "    [GAGAL] Exception: " . $e->getMessage() . "\n";
                echo "[GAGAL] {$user['username']}: " . $e->getMessage() . "\n";
                $failed++;
            }
        }

        // Count after insertion
        $countAfter = $this->db->table('users')->countAllResults();
        
        // Validation
        $logContent .= "\n=== VALIDASI ===\n";
        $logContent .= "Jumlah user sebelum: {$countBefore}\n";
        $logContent .= "Jumlah user sesudah: {$countAfter}\n";
        $logContent .= "Selisih: " . ($countAfter - $countBefore) . " record\n";
        $logContent .= "Inserted: {$inserted}\n";
        $logContent .= "Skipped: {$skipped}\n";
        $logContent .= "Failed: {$failed}\n";

        // Check for duplicates
        $duplicateCheck = $this->db->query("
            SELECT username, COUNT(*) as cnt 
            FROM users 
            GROUP BY username 
            HAVING COUNT(*) > 1
        ")->getResultArray();
        
        if (empty($duplicateCheck)) {
            $logContent .= "Duplikasi username: TIDAK ADA\n";
        } else {
            $logContent .= "Duplikasi username: DITEMUKAN\n";
            foreach ($duplicateCheck as $dup) {
                $logContent .= "  - {$dup['username']}: {$dup['cnt']} kali\n";
            }
        }

        $duplicateEmail = $this->db->query("
            SELECT email, COUNT(*) as cnt 
            FROM users 
            GROUP BY email 
            HAVING COUNT(*) > 1
        ")->getResultArray();
        
        if (empty($duplicateEmail)) {
            $logContent .= "Duplikasi email: TIDAK ADA\n";
        } else {
            $logContent .= "Duplikasi email: DITEMUKAN\n";
            foreach ($duplicateEmail as $dup) {
                $logContent .= "  - {$dup['email']}: {$dup['cnt']} kali\n";
            }
        }

        // Execution time
        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        $logContent .= "\n=== SUMMARY ===\n";
        $logContent .= "Waktu eksekusi: {$executionTime} detik\n";
        $logContent .= "Status: " . ($inserted === 10 ? 'SUKSES' : 'SELESAI DENGAN CATATAN') . "\n";
        $logContent .= "Tanggal eksekusi: " . date('Y-m-d H:i:s') . "\n";
        $logContent .= "Operator: System\n";

        // Write log
        $this->writeLog($logFile, $logContent);

        echo "\n=== SUMMARY ===\n";
        echo "Inserted: {$inserted}\n";
        echo "Skipped: {$skipped}\n";
        echo "Failed: {$failed}\n";
        echo "Total users sekarang: {$countAfter}\n";
        echo "Log file: {$logFile}\n";
    }

    /**
     * Write log to file
     */
    private function writeLog(string $file, string $content): void
    {
        $dir = dirname($file);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($file, $content);
    }
}
