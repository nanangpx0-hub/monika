<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogistikTable extends Migration
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
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'kondisi' => [
                'type'       => 'ENUM',
                'constraint' => ['Baik', 'Rusak Ringan', 'Rusak Berat'],
                'default'    => 'Baik',
            ],
            'lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
            ],
            'keterangan' => [
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
        $this->forge->addUniqueKey('kode_barang');
        $this->forge->createTable('logistik', true);
    }

    public function down()
    {
        $this->forge->dropTable('logistik', true);
    }
}
