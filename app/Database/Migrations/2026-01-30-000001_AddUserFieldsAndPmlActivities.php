<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserFieldsAndPmlActivities extends Migration
{
    public function up()
    {
        // 1. Add new fields to users table
        $fields = [
            'phone_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'after'      => 'email',
            ],
            'wilayah_kerja' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'sobat_id',
            ],
            'wilayah_supervisi' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'wilayah_kerja',
            ],
            'qualification' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'wilayah_supervisi',
            ],
            'masa_tugas_start' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'qualification'
            ],
            'masa_tugas_end' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'masa_tugas_start'
            ]
        ];

        // Check if columns exist before adding (safeguard)
        foreach ($fields as $column => $def) {
            if (!$this->db->fieldExists($column, 'users')) {
                $this->forge->addColumn('users', [$column => $def]);
            }
        }

        // 2. Create pml_activities table
        if (!$this->db->tableExists('pml_activities')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'id_pml' => [
                    'type'       => 'INT', // Matched to users.id_user (Signed INT)
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'activity_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                ],
                'description' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'location_lat' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                ],
                'location_long' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addForeignKey('id_pml', 'users', 'id_user', 'CASCADE', 'CASCADE');
            $this->forge->createTable('pml_activities');
        }
    }

    public function down()
    {
        // Drop table first due to FK
        $this->forge->dropTable('pml_activities', true);

        // Remove columns
        $columns = ['phone_number', 'wilayah_kerja', 'wilayah_supervisi', 'qualification', 'masa_tugas_start', 'masa_tugas_end'];
        foreach ($columns as $col) {
            if ($this->db->fieldExists($col, 'users')) {
                $this->forge->dropColumn('users', $col);
            }
        }
    }
}
