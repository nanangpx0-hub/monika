<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePresensiTable extends Migration
{
    public function up()
    {
        $userPk = null;
        if ($this->db->fieldExists('id_user', 'users')) {
            $userPk = 'id_user';
        } elseif ($this->db->fieldExists('id', 'users')) {
            $userPk = 'id';
        }

        $isUserPkUnsigned = false;
        if ($userPk !== null) {
            $col = $this->db->query("SHOW COLUMNS FROM users LIKE '{$userPk}'")->getRowArray();
            $isUserPkUnsigned = isset($col['Type']) && stripos((string) $col['Type'], 'unsigned') !== false;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => $isUserPkUnsigned,
            ],
            'tgl' => [
                'type' => 'DATE',
            ],
            'jam_masuk' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'jam_pulang' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'foto_masuk' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'foto_pulang' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'lokasi_masuk' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'lokasi_pulang' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'tgl']);

        if ($userPk !== null) {
            $this->forge->addForeignKey('user_id', 'users', $userPk, 'CASCADE', 'CASCADE');
        }
        $this->forge->createTable('presensi', true);
    }

    public function down()
    {
        $this->forge->dropTable('presensi', true);
    }
}
