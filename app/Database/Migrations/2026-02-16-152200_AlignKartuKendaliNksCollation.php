<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlignKartuKendaliNksCollation extends Migration
{
    public function up()
    {
        if ($this->db->DBDriver !== 'MySQLi') {
            return;
        }

        if (! $this->db->tableExists('kartu_kendali')) {
            return;
        }

        if (! $this->db->fieldExists('nks', 'kartu_kendali')) {
            return;
        }

        $this->db->query(
            'ALTER TABLE `kartu_kendali` MODIFY `nks` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL'
        );
    }

    public function down()
    {
        if ($this->db->DBDriver !== 'MySQLi') {
            return;
        }

        if (! $this->db->tableExists('kartu_kendali')) {
            return;
        }

        if (! $this->db->fieldExists('nks', 'kartu_kendali')) {
            return;
        }

        // Keep rollback compatible for MySQL 8 installations currently used by MONIKA.
        $this->db->query(
            'ALTER TABLE `kartu_kendali` MODIFY `nks` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL'
        );
    }
}
