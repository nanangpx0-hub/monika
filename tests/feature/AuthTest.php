<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

/**
 * @internal
 */
final class AuthTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $db = db_connect();
        $forge = Database::forge();

        if ($db->tableExists('users')) {
            $forge->dropTable('users', true);
        }

        $forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $forge->addKey('id', true);
        $forge->createTable('users', true);

        $db->table('users')->insert([
            'username' => 'admin',
            'email' => 'admin@monika.local',
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'role' => 'super_admin',
            'nama' => 'Super Admin',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function testLoginSuccess(): void
    {
        $security = service('security');

        $result = $this->post('/auth/login', [
            'username' => 'admin',
            'password' => '123456',
            $security->getTokenName() => $security->getHash(),
        ]);

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => 'success']);
        $result->assertSessionHas('is_logged_in');
    }

    public function testLoginWrongPassword(): void
    {
        $security = service('security');

        $result = $this->post('/auth/login', [
            'username' => 'admin',
            'password' => 'wrong-password',
            $security->getTokenName() => $security->getHash(),
        ]);

        $result->assertStatus(401);
        $result->assertJSONFragment(['status' => 'error']);
    }

    public function testDashboardWithoutLoginRedirectToLogin(): void
    {
        $result = $this->get('/dashboard');

        $result->assertStatus(302);
        $result->assertRedirectTo('/login');
    }
}
