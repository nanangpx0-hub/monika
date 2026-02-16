<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class DiagnoseLogin extends BaseCommand
{
    protected $group       = 'Diagnostics';
    protected $name        = 'diagnose:login';
    protected $description = 'Diagnose login issues and check system configuration';

    public function run(array $params)
    {
        CLI::write('=== MONIKA Login Diagnostics ===', 'yellow');
        CLI::newLine();

        $this->checkEnvironment();
        $this->checkDatabase();
        $this->checkSession();
        $this->checkPermissions();
        $this->checkEncryption();
        $this->checkUsers();

        CLI::newLine();
        CLI::write('=== Diagnostics Complete ===', 'yellow');
    }

    private function checkEnvironment()
    {
        CLI::write('1. Environment Configuration', 'cyan');

        $env = ENVIRONMENT;
        CLI::write("   Environment: {$env}", $env === 'production' ? 'red' : 'green');

        $baseURL = base_url();
        CLI::write("   Base URL: {$baseURL}");

        CLI::newLine();
    }

    private function checkDatabase()
    {
        CLI::write('2. Database Connection', 'cyan');

        try {
            $db = Database::connect();

            if ($db->connID) {
                CLI::write('   ✓ Database connection: OK', 'green');

                $hostname = env('database.default.hostname', 'not set');
                $database = env('database.default.database', 'not set');
                $username = env('database.default.username', 'not set');

                CLI::write("   Hostname: {$hostname}");
                CLI::write("   Database: {$database}");
                CLI::write("   Username: {$username}");

                // Check if users table exists
                if ($db->tableExists('users')) {
                    CLI::write('   ✓ Users table: EXISTS', 'green');

                    $userCount = $db->table('users')->countAll();
                    CLI::write("   Total users: {$userCount}");
                } else {
                    CLI::write('   ✗ Users table: NOT FOUND', 'red');
                }

                // Check if login_attempts table exists
                if ($db->tableExists('login_attempts')) {
                    CLI::write('   ✓ Login attempts table: EXISTS', 'green');
                } else {
                    CLI::write('   ⚠ Login attempts table: NOT FOUND (run migrations)', 'yellow');
                }
            } else {
                CLI::write('   ✗ Database connection: FAILED', 'red');
            }
        } catch (\Exception $e) {
            CLI::write('   ✗ Database error: ' . $e->getMessage(), 'red');
        }

        CLI::newLine();
    }

    private function checkSession()
    {
        CLI::write('3. Session Configuration', 'cyan');

        $sessionPath = WRITEPATH . 'session';
        CLI::write("   Session path: {$sessionPath}");

        if (is_dir($sessionPath)) {
            CLI::write('   ✓ Session directory: EXISTS', 'green');

            if (is_writable($sessionPath)) {
                CLI::write('   ✓ Session directory: WRITABLE', 'green');
            } else {
                CLI::write('   ✗ Session directory: NOT WRITABLE', 'red');
                CLI::write('   Fix: chmod 0700 ' . $sessionPath, 'yellow');
            }

            $files = glob($sessionPath . '/ci_session*');
            $sessionCount = count($files);
            CLI::write("   Active sessions: {$sessionCount}");
        } else {
            CLI::write('   ✗ Session directory: NOT FOUND', 'red');
            CLI::write('   Fix: mkdir -p ' . $sessionPath . ' && chmod 0700 ' . $sessionPath, 'yellow');
        }

        CLI::newLine();
    }

    private function checkPermissions()
    {
        CLI::write('4. File Permissions', 'cyan');

        $writablePath = WRITEPATH;
        CLI::write("   Writable path: {$writablePath}");

        if (is_writable($writablePath)) {
            CLI::write('   ✓ Writable directory: WRITABLE', 'green');
        } else {
            CLI::write('   ✗ Writable directory: NOT WRITABLE', 'red');
        }

        $logsPath = WRITEPATH . 'logs';
        if (is_dir($logsPath)) {
            if (is_writable($logsPath)) {
                CLI::write('   ✓ Logs directory: WRITABLE', 'green');
            } else {
                CLI::write('   ✗ Logs directory: NOT WRITABLE', 'red');
            }
        } else {
            CLI::write('   ⚠ Logs directory: NOT FOUND', 'yellow');
        }

        CLI::newLine();
    }

    private function checkEncryption()
    {
        CLI::write('5. Encryption Configuration', 'cyan');

        $encryptionKey = env('encryption.key', '');
        if ($encryptionKey !== '') {
            CLI::write('   ✓ Encryption key: SET', 'green');
        } else {
            CLI::write('   ✗ Encryption key: NOT SET', 'red');
        }

        $rememberKey = env('auth.remember.key', '');
        if ($rememberKey !== '') {
            CLI::write('   ✓ Remember me key: SET', 'green');
        } else {
            CLI::write('   ⚠ Remember me key: NOT SET (remember me will fail)', 'yellow');
        }

        CLI::newLine();
    }

    private function checkUsers()
    {
        CLI::write('6. User Accounts', 'cyan');

        try {
            $db = Database::connect();

            if ($db->tableExists('users')) {
                $users = $db->table('users')
                    ->select('username, email, is_active, id_role')
                    ->get()
                    ->getResultArray();

                if (count($users) > 0) {
                    CLI::write('   Active users:');
                    foreach ($users as $user) {
                        $status = isset($user['is_active']) && (int) $user['is_active'] === 1 ? '✓' : '✗';
                        $statusColor = isset($user['is_active']) && (int) $user['is_active'] === 1 ? 'green' : 'red';

                        CLI::write(
                            "   {$status} {$user['username']} ({$user['email']}) - Role: {$user['id_role']}",
                            $statusColor
                        );
                    }
                } else {
                    CLI::write('   ⚠ No users found in database', 'yellow');
                    CLI::write('   Run: php spark db:seed UserSeeder', 'yellow');
                }
            }
        } catch (\Exception $e) {
            CLI::write('   ✗ Error checking users: ' . $e->getMessage(), 'red');
        }

        CLI::newLine();
    }
}
