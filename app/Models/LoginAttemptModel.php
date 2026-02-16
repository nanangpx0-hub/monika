<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;
use Throwable;

class LoginAttemptModel extends Model
{
    protected $table            = 'login_attempts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ip_address',
        'username',
        'attempt_time',
        'success',
        'user_agent',
        'error_message',
    ];

    protected $useTimestamps = false;
    private ?bool $tableAvailable = null;

    /**
     * Check whether login_attempts table exists.
     * Fail closed to "not available" to avoid breaking login flow.
     */
    private function isTableAvailable(): bool
    {
        if ($this->tableAvailable !== null) {
            return $this->tableAvailable;
        }

        try {
            $this->tableAvailable = Database::connect()->tableExists($this->table);
        } catch (Throwable) {
            $this->tableAvailable = false;
        }

        return $this->tableAvailable;
    }

    /**
     * Log login attempt
     */
    public function logAttempt(string $ipAddress, string $username, bool $success, ?string $errorMessage = null): bool
    {
        if (! $this->isTableAvailable()) {
            return false;
        }

        try {
            return (bool) $this->insert([
                'ip_address'    => $ipAddress,
                'username'      => $username,
                'attempt_time'  => date('Y-m-d H:i:s'),
                'success'       => $success ? 1 : 0,
                'user_agent'    => $_SERVER['HTTP_USER_AGENT'] ?? null,
                'error_message' => $errorMessage,
            ]);
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Get failed attempts count for IP in last N minutes
     */
    public function getFailedAttemptsCount(string $ipAddress, int $minutes = 15): int
    {
        if (! $this->isTableAvailable()) {
            return 0;
        }

        $since = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));

        try {
            return (int) $this->where('ip_address', $ipAddress)
                ->where('success', 0)
                ->where('attempt_time >=', $since)
                ->countAllResults();
        } catch (Throwable) {
            return 0;
        }
    }

    /**
     * Get failed attempts count for username in last N minutes
     */
    public function getFailedAttemptsCountByUsername(string $username, int $minutes = 15): int
    {
        if (! $this->isTableAvailable()) {
            return 0;
        }

        $since = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));

        try {
            return (int) $this->where('username', $username)
                ->where('success', 0)
                ->where('attempt_time >=', $since)
                ->countAllResults();
        } catch (Throwable) {
            return 0;
        }
    }

    /**
     * Clear old attempts (older than N days)
     */
    public function clearOldAttempts(int $days = 30): int
    {
        if (! $this->isTableAvailable()) {
            return 0;
        }

        $before = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        try {
            return (int) $this->where('attempt_time <', $before)->delete();
        } catch (Throwable) {
            return 0;
        }
    }
}
