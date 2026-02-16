<?php

namespace App\Models;

use CodeIgniter\Model;
use Throwable;

class UserAuditModel extends Model
{
    protected $table            = 'user_audit_log';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user', 'action', 'changed_by', 'details', 'created_at',
    ];

    protected $useTimestamps = false;
    private ?bool $tableReady = null;

    /**
     * Ensure the audit table exists — auto-create if missing.
     */
    private function ensureTable(): bool
    {
        if ($this->tableReady !== null) {
            return $this->tableReady;
        }

        try {
            if ($this->db->tableExists($this->table)) {
                $this->tableReady = true;
                return true;
            }

            $forge = \Config\Database::forge();
            $forge->addField([
                'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'id_user'    => ['type' => 'INT', 'unsigned' => true],
                'action'     => ['type' => 'VARCHAR', 'constraint' => 50],
                'changed_by' => ['type' => 'INT', 'unsigned' => true],
                'details'    => ['type' => 'TEXT', 'null' => true],
                'created_at' => ['type' => 'DATETIME'],
            ]);
            $forge->addKey('id', true);
            $forge->addKey('id_user');
            $forge->addKey('action');
            $forge->createTable($this->table, true);

            $this->tableReady = true;
        } catch (Throwable) {
            $this->tableReady = false;
        }

        return $this->tableReady;
    }

    /**
     * Log an audit action.
     */
    public function logAction(int $userId, string $action, int $changedBy, ?array $details = null): bool
    {
        if (! $this->ensureTable()) {
            return false;
        }

        try {
            return (bool) $this->insert([
                'id_user'    => $userId,
                'action'     => $action,
                'changed_by' => $changedBy,
                'details'    => $details ? json_encode($details, JSON_UNESCAPED_UNICODE) : null,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (Throwable) {
            return false;
        }
    }
}
