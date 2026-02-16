<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class AuthTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    protected function setUp(): void
    {
        parent::setUp();

        $this->db->table('users')
            ->groupStart()
            ->where('username', 'testuser')
            ->orWhere('email', 'test@example.com')
            ->groupEnd()
            ->delete();

        // Create test user
        $this->db->table('users')->insert([
            'username'   => 'testuser',
            'email'      => 'test@example.com',
            'password'   => password_hash('TestPassword123', PASSWORD_BCRYPT),
            'fullname'   => 'Test User',
            'id_role'    => 3,
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function withCsrf(array $data): array
    {
        $security = service('security');
        $data[$security->getTokenName()] = $security->getHash();

        return $data;
    }

    public function testLoginPageLoads()
    {
        $result = $this->get('login');

        $result->assertStatus(200);
        $result->assertSee('MONIKA LOGIN');
        $result->assertSee('Username atau Email');
        $result->assertSee('Password');
    }

    public function testLoginWithValidCredentials()
    {
        $result = $this->post('auth/login', [
            'username' => 'testuser',
            'password' => 'TestPassword123',
        ] + $this->withCsrf([]));

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => 'success']);
        $result->assertJSONFragment(['redirect' => base_url('dashboard')]);
    }

    public function testLoginWithInvalidPassword()
    {
        $result = $this->post('auth/login', [
            'username' => 'testuser',
            'password' => 'WrongPassword',
        ] + $this->withCsrf([]));

        $result->assertStatus(401);
        $result->assertJSONFragment(['status' => 'error']);
        $result->assertJSONFragment(['message' => 'Username atau password salah.']);
    }

    public function testLoginWithNonExistentUser()
    {
        $result = $this->post('auth/login', [
            'username' => 'nonexistent',
            'password' => 'SomePassword',
        ] + $this->withCsrf([]));

        $result->assertStatus(401);
        $result->assertJSONFragment(['status' => 'error']);
    }

    public function testLoginWithEmptyCredentials()
    {
        $result = $this->post('auth/login', [
            'username' => '',
            'password' => '',
        ] + $this->withCsrf([]));

        $result->assertStatus(422);
        $result->assertJSONFragment(['status' => 'error']);
        $result->assertJSONFragment(['message' => 'Username/Email dan password wajib diisi.']);
    }

    public function testLoginWithInactiveAccount()
    {
        // Create inactive user
        $this->db->table('users')->insert([
            'username'   => 'inactiveuser',
            'email'      => 'inactive@example.com',
            'password'   => password_hash('TestPassword123', PASSWORD_BCRYPT),
            'fullname'   => 'Inactive User',
            'id_role'    => 3,
            'is_active'  => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $result = $this->post('auth/login', [
            'username' => 'inactiveuser',
            'password' => 'TestPassword123',
        ] + $this->withCsrf([]));

        $result->assertStatus(403);
        $result->assertJSONFragment(['status' => 'error']);
        $result->assertJSONFragment(['message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
    }

    public function testLoginWithEmail()
    {
        $result = $this->post('auth/login', [
            'username' => 'test@example.com',
            'password' => 'TestPassword123',
        ] + $this->withCsrf([]));

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => 'success']);
    }

    public function testRateLimitingAfterMultipleFailedAttempts()
    {
        // Attempt login 5 times with wrong password
        for ($i = 0; $i < 5; $i++) {
            $this->post('auth/login', [
                'username' => 'testuser',
                'password' => 'WrongPassword',
            ] + $this->withCsrf([]));
        }

        // 6th attempt should be rate limited
        $result = $this->post('auth/login', [
            'username' => 'testuser',
            'password' => 'WrongPassword',
        ] + $this->withCsrf([]));

        $result->assertStatus(429);
        $result->assertJSONFragment(['status' => 'error']);
        $result->assertSee('Terlalu banyak percobaan login');
    }

    public function testLogoutClearsSession()
    {
        // Simulate authenticated session then call logout endpoint.
        $result = $this->withSession([
            'id' => 1,
            'id_user' => 1,
            'username' => 'testuser',
            'is_logged_in' => true,
        ])->get('logout');

        // Verify redirect to login
        $result->assertRedirectTo(base_url('login?logout=1'));
    }

    public function testSessionPersistsAfterLogin()
    {
        // Login
        $this->post('auth/login', [
            'username' => 'testuser',
            'password' => 'TestPassword123',
        ] + $this->withCsrf([]));

        // Check session data
        $this->assertTrue(session()->get('is_logged_in') === true);
        $this->assertEquals('testuser', session()->get('username'));
        $this->assertNotNull(session()->get('id'));
    }
}
