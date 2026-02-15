<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        if (! $this->db->tableExists('users')) {
            return;
        }

        $columns = $this->db->getFieldNames('users');
        $has = static fn (string $name): bool => in_array($name, $columns, true);

        $passwordHash = password_hash('123456', PASSWORD_BCRYPT);
        $now = date('Y-m-d H:i:s');

        $data = [];

        if ($has('username')) {
            $data['username'] = 'admin';
        }

        if ($has('email')) {
            $data['email'] = 'admin@monika.local';
        }

        if ($has('password')) {
            $data['password'] = $passwordHash;
        }

        if ($has('role')) {
            $data['role'] = 'super_admin';
        }

        if ($has('created_at')) {
            $data['created_at'] = $now;
        }

        if ($has('nama')) {
            $data['nama'] = 'Super Admin';
        }

        if ($has('fullname')) {
            $data['fullname'] = 'Super Admin';
        }

        if ($has('id_role')) {
            $data['id_role'] = 1;
        }

        if ($has('is_active')) {
            $data['is_active'] = 1;
        }

        if ($has('nik_ktp')) {
            $data['nik_ktp'] = '0000000000000000';
        }

        if ($has('sobat_id')) {
            $data['sobat_id'] = 'ADMIN-0001';
        }

        if ($has('no_hp')) {
            $data['no_hp'] = '081000000000';
        }

        if ($data === [] || ! isset($data['username'])) {
            return;
        }

        $builder = $this->db->table('users');
        $builder->where('username', 'admin');
        if ($has('email')) {
            $builder->orWhere('email', 'admin@monika.local');
        }

        $existing = $builder->get()->getRowArray();
        $pk = $has('id') ? 'id' : ($has('id_user') ? 'id_user' : null);

        if ($existing && $pk !== null && isset($existing[$pk])) {
            $this->db->table('users')->where($pk, $existing[$pk])->update($data);
            return;
        }

        if ($existing) {
            $this->db->table('users')->where('username', 'admin')->update($data);
            return;
        }

        $this->db->table('users')->insert($data);
    }
}
