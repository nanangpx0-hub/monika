<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserEnhancements extends Migration
{
    public function up()
    {
        // Add new fields to users table
        $fields = [
            'password_changed_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'last_login_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ];

        // Check if columns exist before adding
        if (!$this->db->fieldExists('password_changed_at', 'users')) {
            $this->forge->addColumn('users', ['password_changed_at' => $fields['password_changed_at']]);
        }
        if (!$this->db->fieldExists('last_login_at', 'users')) {
            $this->forge->addColumn('users', ['last_login_at' => $fields['last_login_at']]);
        }
        if (!$this->db->fieldExists('deleted_at', 'users')) {
            $this->forge->addColumn('users', ['deleted_at' => $fields['deleted_at']]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'password_changed_at');
        $this->forge->dropColumn('users', 'last_login_at');
        $this->forge->dropColumn('users', 'deleted_at');
    }
}
