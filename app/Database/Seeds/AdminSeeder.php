<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'fullname' => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@monika.test',
            'password' => password_hash('admin123', PASSWORD_BCRYPT),
            'id_role'  => 1,
            'is_active' => 1
        ];

        // Simple insert
        $this->db->table('users')->insert($data);
    }
}
