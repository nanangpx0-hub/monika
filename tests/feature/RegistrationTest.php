<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\UserModel;

class RegistrationTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';

    public function testRegistrationSuccess()
    {
        $data = [
            'fullname' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'confpassword' => 'password123',
            'nik_ktp' => '1234567890123456',
            'sobat_id' => '12345',
            'id_role' => 3
        ];

        $result = $this->withBody($data)
            ->post('register');

        $result->assertStatus(302);
        $result->assertRedirectTo('login');

        $userModel = new UserModel();
        $user = $userModel->where('username', 'testuser')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Test User', $user['fullname']);
    }

    public function testRegistrationFailureValidation()
    {
        $data = [
            'fullname' => '', // Empty fullname
            'username' => 'testuser',
            'email' => 'invalid-email', // Invalid email
            'password' => '123', // Too short
            'confpassword' => '123',
            'nik_ktp' => '123', // Too short
            'sobat_id' => '12345',
            'id_role' => 3
        ];

        $result = $this->withBody($data)
            ->post('register');

        $result->assertStatus(302);
        // Should redirect back
        $this->assertTrue(session()->has('errors'));
    }

    public function testRegistrationPasswordMismatch()
    {
        $data = [
            'fullname' => 'Test User 2',
            'username' => 'testuser2',
            'email' => 'test2@example.com',
            'password' => 'password123',
            'confpassword' => 'mismatch123',
            'nik_ktp' => '1234567890123457',
            'sobat_id' => '12345',
            'id_role' => 3
        ];

        $result = $this->withBody($data)
            ->post('register');

        $result->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
