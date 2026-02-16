<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;

class CreatePenyetoranDokumen extends Migration
{
    public function up()
    {
        // Header table: batch info + handover metadata
        $this->forge->addField([
            'id_penyetoran' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_kegiatan'   => ['type' => 'INT', 'constraint' => 11],
            'nama_pengirim' => ['type' => 'VARCHAR', 'constraint' => 100],
            'tanggal_penyerahan' => ['type' => 'DATE'],
            'jenis_dokumen' => ['type' => 'VARCHAR', 'constraint' => 100],
            'keterangan'    => ['type' => 'TEXT', 'null' => true],
            'file_pendukung'=> ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'id_penyerah'   => ['type' => 'INT', 'constraint' => 11, 'comment' => 'User yang menyetor'],
            'id_penerima'   => ['type' => 'INT', 'constraint' => 11, 'null' => true, 'comment' => 'PLS user yang konfirmasi'],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Diserahkan', 'Diterima', 'Ditolak'], 'default' => 'Diserahkan'],
            'tanggal_konfirmasi' => ['type' => 'DATETIME', 'null' => true],
            'catatan_penerima'   => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'    => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id_penyetoran', true);
        $this->forge->addKey('id_kegiatan');
        $this->forge->addKey('id_penyerah');
        $this->forge->addKey('id_penerima');
        $this->forge->createTable('penyetoran_dokumen', true);

        // Detail table: per-ruta rows matching Excel layout
        $this->forge->addField([
            'id_detail'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_penyetoran'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'kode_prop'      => ['type' => 'VARCHAR', 'constraint' => 2],
            'kode_kab'       => ['type' => 'VARCHAR', 'constraint' => 2],
            'kode_nks'       => ['type' => 'VARCHAR', 'constraint' => 5],
            'no_urut_ruta'   => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true],
            'status_selesai' => ['type' => 'ENUM', 'constraint' => ['sudah', 'belum'], 'default' => 'belum'],
            'tgl_penerimaan' => ['type' => 'DATE', 'null' => true],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id_detail', true);
        $this->forge->addKey('id_penyetoran');
        $this->forge->addForeignKey('id_penyetoran', 'penyetoran_dokumen', 'id_penyetoran', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penyetoran_dokumen_detail', true);
    }

    public function down()
    {
        $this->forge->dropTable('penyetoran_dokumen_detail', true);
        $this->forge->dropTable('penyetoran_dokumen', true);
    }
}
