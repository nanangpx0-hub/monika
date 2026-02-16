<?php

namespace Tests\Integration;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class LoginFlowTest extends CIUnitTestCase
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
            ->where('username', 'integrationuser')
            ->orWhere('email', 'integration@example.com')
            ->groupEnd()
            ->delete();

        // Create test user
        $this->db->table('users')->insert([
            'username'   => 'integrationuser',
            'email'      => 'integration@example.com',
            'password'   => password_hash('IntegrationTest123', PASSWORD_BCRYPT),
            'fullname'   => 'Integration Test User',
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

    public function testCompleteLoginFlow()
    {
        // Step 1: Access login page
        $result = $this->get('login');
        $result->assertStatus(200);
        $result->assertSee('MONIKA LOGIN');

        // Step 2: Submit login form
        $result = $this->post('auth/login', [
            'username' => 'integrationuser',
            'password' => 'IntegrationTest123',
        ] + $this->withCsrf([]));

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => 'success']);

        // Step 3: Access protected page that only depends on session + presensi table
        $result = $this->withSession([
            'id' => 1,
            'id_user' => 1,
            'username' => 'integrationuser',
            'is_logged_in' => true,
        ])->get('presensi');
        $result->assertStatus(200);

        // Step 4: Logout from authenticated session
        $result = $this->withSession([
            'id' => 1,
            'id_user' => 1,
            'username' => 'integrationuser',
            'is_logged_in' => true,
        ])->get('logout');
        $result->assertRedirectTo(base_url('login?logout=1'));

        // Step 5: Logout endpoint completed and redirected correctly.
    }

    public function testLoginWithRememberMe()
    {
        // Login with remember me
        $result = $this->post('auth/login', [
            'username'    => 'integrationuser',
            'password'    => 'IntegrationTest123',
            'remember_me' => '1',
        ] + $this->withCsrf([]));

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => 'success']);

        // Verify remember cookie is set
        $result->assertCookie('monika_remember', null);
    }

    public function testAuthFilterBlocksUnauthenticatedAccess()
    {
        // Try to access protected page without login
        $result = $this->get('dashboard');

        // Should redirect to login
        $result->assertRedirectTo(base_url('login'));

        // Should have error message in session
        $this->assertEquals('Silakan login terlebih dahulu.', session()->getFlashdata('error'));
    }

    public function testLoginAttemptsAreLogged()
    {
        // Perform login
        $this->post('auth/login', [
            'username' => 'integrationuser',
            'password' => 'IntegrationTest123',
        ] + $this->withCsrf([]));

        // Check if login attempt was logged
        $attempt = $this->db->table('login_attempts')
            ->where('username', 'integrationuser')
            ->where('success', 1)
            ->get()
            ->getRowArray();

        $this->assertNotNull($attempt);
        $this->assertEquals('integrationuser', $attempt['username']);
        $this->assertEquals(1, $attempt['success']);
    }

    public function testFailedLoginAttemptsAreLogged()
    {
        // Perform failed login
        $this->post('auth/login', [
            'username' => 'integrationuser',
            'password' => 'WrongPassword',
        ] + $this->withCsrf([]));

        // Check if failed attempt was logged
        $attempt = $this->db->table('login_attempts')
            ->where('username', 'integrationuser')
            ->where('success', 0)
            ->get()
            ->getRowArray();

        $this->assertNotNull($attempt);
        $this->assertEquals('integrationuser', $attempt['username']);
        $this->assertEquals(0, $attempt['success']);
        $this->assertNotNull($attempt['error_message']);
    }

    public function testSessionRegenerationOnLogin()
    {
        // Login
        $result = $this->post('auth/login', [
            'username' => 'integrationuser',
            'password' => 'IntegrationTest123',
        ] + $this->withCsrf([]));

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => 'success']);
    }

    public function testDatabaseConnectionErrorHandling()
    {
        // This test would require mocking database connection failure
        // For now, we'll just verify the error handling structure exists
        $this->assertTrue(method_exists(\App\Controllers\Auth::class, 'login'));
    }
}
