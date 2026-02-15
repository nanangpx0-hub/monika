<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserDummySeeder extends Seeder
{
    public function run()
    {
        if (! $this->db->tableExists('users')) {
            echo "[GAGAL] Tabel users tidak ditemukan.\n";
            return;
        }

        $columns = $this->db->getFieldNames('users');
        $has = static fn (string $name): bool => in_array($name, $columns, true);

        $allowedRoles = [];
        if ($has('role')) {
            $roleColumn = $this->db->query("SHOW COLUMNS FROM users LIKE 'role'")->getRowArray();
            $roleType = strtolower((string) ($roleColumn['Type'] ?? ''));
            if (str_starts_with($roleType, 'enum(')) {
                preg_match_all("/'([^']+)'/", $roleType, $matches);
                $allowedRoles = $matches[1] ?? [];
            }
        }

        $data = [
            [
                'username' => 'admin_nanang',
                'email' => 'nanang.pamungkas@bps.go.id',
                'password' => 'Monika@2026!',
                'role' => 'admin',
                'nama' => 'Nanang Pamungkas',
                'status_aktif' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'last_login' => Time::parse('2026-02-14 08:10:00')->toDateTimeString(),
            ],
            [
                'username' => 'admin_suharsa',
                'email' => 'muhamad.suharsa@bps.go.id',
                'password' => 'DataBPS#2026',
                'role' => 'admin',
                'nama' => 'Muhamad Suharsa',
                'status_aktif' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'last_login' => Time::parse('2026-02-13 09:00:00')->toDateTimeString(),
            ],
            [
                'username' => 'mod_qudrat',
                'email' => 'qudrat.jufrian@bps.go.id',
                'password' => 'Qudrat!2026',
                'role' => 'moderator',
                'nama' => 'Qudrat Jufrian',
                'status_aktif' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'last_login' => Time::parse('2026-02-12 07:35:00')->toDateTimeString(),
            ],
            [
                'username' => 'mod_arumita',
                'email' => 'arumita.hertriesa@bps.go.id',
                'password' => 'Arumita$2026',
                'role' => 'moderator',
                'nama' => 'Arumita Hertriesa',
                'status_aktif' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'last_login' => null,
            ],
            [
                'username' => 'user_putri',
                'email' => 'putrisalsabhilafahira10@gmail.com',
                'password' => 'Putri@Salsa26',
                'role' => 'user',
                'nama' => 'Putri Salsabhila',
                'status_aktif' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'last_login' => Time::parse('2026-02-10 11:20:00')->toDateTimeString(),
            ],
            [
                'username' => 'user_astri',
                'email' => 'a.widarianti@gmail.com',
                'password' => 'Astri#Wid26',
                'role' => 'user',
                'nama' => 'Astri Widarianti',
                'status_aktif' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'last_login' => null,
            ],
            [
                'username' => 'user_nurida',
                'email' => 'nidasuryandari@gmail.com',
                'password' => 'Nurida!Data26',
                'role' => 'user',
                'nama' => 'Nur Ida Suryandari',
                'status_aktif' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'last_login' => Time::parse('2026-01-30 15:05:00')->toDateTimeString(),
            ],
            [
                'username' => 'user_gilang',
                'email' => 'gilangrizqi2001@gmail.com',
                'password' => 'Gilang*Risqi26',
                'role' => 'user',
                'nama' => 'Gilang Risqi',
                'status_aktif' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'last_login' => Time::parse('2026-02-01 19:45:00')->toDateTimeString(),
            ],
            [
                'username' => 'user_dimas',
                'email' => 'rafendra.dimas09@gmail.com',
                'password' => 'Dimas^Rafen26',
                'role' => 'user',
                'nama' => 'Dimas Rafendra',
                'status_aktif' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'last_login' => null,
            ],
            [
                'username' => 'user_zainal',
                'email' => 'muhammadzainalgufron11@gmail.com',
                'password' => 'Zainal&Guf26',
                'role' => 'user',
                'nama' => 'Zainal Gufron',
                'status_aktif' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'last_login' => Time::parse('2026-02-14 20:20:00')->toDateTimeString(),
            ],
        ];

        foreach ($data as $user) {
            $insertData = [
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => password_hash($user['password'], PASSWORD_BCRYPT),
            ];

            if ($has('nama')) {
                $insertData['nama'] = $user['nama'];
            }

            if ($has('role')) {
                if ($allowedRoles === [] || in_array($user['role'], $allowedRoles, true)) {
                    $insertData['role'] = $user['role'];
                } else {
                    echo "[INFO] {$user['username']}: role '{$user['role']}' tidak sesuai ENUM tabel, dilewati.\n";
                }
            }

            if ($has('status_aktif')) {
                $insertData['status_aktif'] = $user['status_aktif'];
            } elseif ($has('is_active')) {
                $insertData['is_active'] = $user['status_aktif'];
            }

            if ($has('created_at')) {
                $insertData['created_at'] = $user['created_at'];
            }

            if ($has('updated_at')) {
                $insertData['updated_at'] = $user['updated_at'];
            }

            if ($has('last_login')) {
                $insertData['last_login'] = $user['last_login'];
            }

            $result = $this->db->table('users')->ignore(true)->insert($insertData);

            if (! $result) {
                $error = $this->db->error();
                $message = $error['message'] ?? 'Unknown database error';
                echo "[GAGAL] {$user['username']} ({$user['email']}): {$message}\n";
                continue;
            }

            if ($this->db->affectedRows() > 0) {
                echo "[OK] {$user['username']} ({$user['email']}) berhasil diinsert.\n";
                continue;
            }

            echo "[SKIP] {$user['username']} ({$user['email']}) dilewati (duplikat username/email).\n";
        }
    }
}
