<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to allow truncation
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // Truncate users table to reset IDs
        $this->db->table('users')->truncate();

        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        // Data dummy pengguna
        $data = [
            // Administrator (Re-create ID 1)
            [
                'id_user' => 1,
                'fullname' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@monika.test',
                'password' => password_hash('admin', PASSWORD_BCRYPT), // Password: admin
                'nik_ktp' => null,
                'sobat_id' => null,
                'id_role' => 1,
                'id_supervisor' => null,
                'is_active' => 1
            ],
            [
                'id_user' => 2,
                'fullname' => 'John Doe',
                'username' => 'johndoe',
                'email' => 'john.doe@monika.test',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'nik_ktp' => '1234567890123456',
                'sobat_id' => 'SOB001',
                'id_role' => 3, // Petugas Pendataan
                'id_supervisor' => null,
                'is_active' => 1
            ],
            [
                'id_user' => 3,
                'fullname' => 'Jane Smith',
                'username' => 'janesmith',
                'email' => 'jane.smith@monika.test',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'nik_ktp' => '1234567890123457',
                'sobat_id' => 'SOB002',
                'id_role' => 4, // Petugas Pengolahan
                'id_supervisor' => null,
                'is_active' => 1
            ],
            [
                'id_user' => 4,
                'fullname' => 'Robert Johnson',
                'username' => 'robertj',
                'email' => 'robert.johnson@monika.test',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'nik_ktp' => '1234567890123458',
                'sobat_id' => 'SOB003',
                'id_role' => 5, // Pengawas Lapangan
                'id_supervisor' => null,
                'is_active' => 1
            ],
            [
                'id_user' => 5,
                'fullname' => 'Emily Davis',
                'username' => 'emilyd',
                'email' => 'emily.davis@monika.test',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'nik_ktp' => '1234567890123459',
                'sobat_id' => 'SOB004',
                'id_role' => 6, // Pengawas Pengolahan
                'id_supervisor' => null,
                'is_active' => 1
            ],
            [
                'id_user' => 6,
                'fullname' => 'Michael Wilson',
                'username' => 'michaelw',
                'email' => 'michael.wilson@monika.test',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'nik_ktp' => '1234567890123460',
                'sobat_id' => 'SOB005',
                'id_role' => 3, // Petugas Pendataan
                'id_supervisor' => 4, // Diawasi oleh Robert Johnson
                'is_active' => 1
            ],
            [
                'id_user' => 7,
                'fullname' => 'Sarah Brown',
                'username' => 'sarahb',
                'email' => 'sarah.brown@monika.test',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'nik_ktp' => '1234567890123461',
                'sobat_id' => 'SOB006',
                'id_role' => 4, // Petugas Pengolahan
                'id_supervisor' => 5, // Diawasi oleh Emily Davis
                'is_active' => 1
            ]
        ];

        // Insert data
        $this->db->table('users')->insertBatch($data);
    }
}