<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKartuKendaliTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nks' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'no_ruta' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'status_entry' => [
                'type'       => 'ENUM',
                'constraint' => ['Clean', 'Error'],
                'default'    => 'Clean',
            ],
            'is_patch_issue' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'tgl_entry' => [
                'type' => 'DATE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['nks', 'no_ruta']);
        
        // Foreign Keys
        $this->forge->addForeignKey('nks', 'nks_master', 'nks', 'CASCADE', 'CASCADE');
        
        // Check if users table uses 'id' or 'id_user'
        $userPk = $this->db->fieldExists('id_user', 'users') ? 'id_user' : 'id';
        $this->forge->addForeignKey('user_id', 'users', $userPk, 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('kartu_kendali', true);
    }

    public function down()
    {
        $this->forge->dropTable('kartu_kendali', true);
    }
}
