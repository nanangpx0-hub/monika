<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        // Disable FK checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('roles')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        // Data role dasar
        $data = [
            [
                'id_role' => 1,
                'role_name' => 'Administrator',
                'description' => 'Super user with full access'
            ],
            [
                'id_role' => 3,
                'role_name' => 'Petugas Pendataan (PCL)',
                'description' => 'Mitra Lapangan - Field Enumerator'
            ],
            [
                'id_role' => 4,
                'role_name' => 'Petugas Pengolahan',
                'description' => 'Mitra Entry/Editing - Data Processor'
            ],
            [
                'id_role' => 5,
                'role_name' => 'Pengawas Lapangan (PML)',
                'description' => 'Field Supervisor'
            ],
            [
                'id_role' => 6,
                'role_name' => 'Pengawas Pengolahan',
                'description' => 'Processing Supervisor'
            ]
        ];

        // Insert data
        $this->db->table('roles')->insertBatch($data);
    }
}