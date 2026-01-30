<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('users')) {
            $this->forge->addField([
                'id_user' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'fullname' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'username' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                ],
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'password' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'nik_ktp' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '16',
                    'null'       => true,
                ],
                'sobat_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                ],
                'id_role' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'id_supervisor' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id_user', true);
            $this->forge->createTable('users');
        }
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
