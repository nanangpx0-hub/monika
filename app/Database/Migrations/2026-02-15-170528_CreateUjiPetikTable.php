<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUjiPetikTable extends Migration
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
                'constraint' => 2,
            ],
            'variabel' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'isian_k' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'Isian Dokumen Fisik',
            ],
            'isian_c' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'Isian Komputer',
            ],
            'alasan_kesalahan' => [
                'type'       => 'ENUM',
                'constraint' => ['Salah Ketik', 'Salah Baca', 'Terlewat', 'Salah Kode', 'Lainnya'],
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('nks');
        $this->forge->createTable('uji_petik');
    }

    public function down()
    {
        $this->forge->dropTable('uji_petik');
    }
}
