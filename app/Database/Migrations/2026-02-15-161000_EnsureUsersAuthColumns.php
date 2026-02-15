<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnsureUsersAuthColumns extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('users')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'username' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                ],
                'password' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'role' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('username');
            $this->forge->addUniqueKey('email');
            $this->forge->createTable('users', true);

            return;
        }

        $existingColumns = $this->db->getFieldNames('users');
        $addColumns = [];

        if (! in_array('username', $existingColumns, true)) {
            $addColumns['username'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! in_array('email', $existingColumns, true)) {
            $addColumns['email'] = [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ];
        }

        if (! in_array('password', $existingColumns, true)) {
            $addColumns['password'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ];
        }

        if (! in_array('role', $existingColumns, true)) {
            $addColumns['role'] = [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ];
        }

        if (! in_array('created_at', $existingColumns, true)) {
            $addColumns['created_at'] = [
                'type' => 'DATETIME',
                'null' => true,
            ];
        }

        if ($addColumns !== []) {
            $this->forge->addColumn('users', $addColumns);
        }
    }

    public function down()
    {
        // Intentionally no-op for existing production data safety.
    }
}
