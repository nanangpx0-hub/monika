<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;

class PclManagementTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $namespace = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testCreatePcl()
    {
        // Mock Admin Session
        $session = [
            'id_user' => 1,
            'username' => 'admin',
            'id_role' => 1,
            'is_logged_in' => true
        ];
        
        $result = $this->withSession($session)
                       ->post('admin/pcl/store', [
                           'fullname' => 'Test PCL',
                           'username' => 'testpcl',
                           'email' => 'testpcl@example.com',
                           'password' => 'password123',
                           'nik_ktp' => '1234567890123456',
                           'phone_number' => '08123456789',
                           'wilayah_kerja' => 'Jakarta',
                           'masa_tugas_start' => '2023-01-01',
                           'masa_tugas_end' => '2023-12-31'
                       ]);

        $result->assertRedirectTo('admin/pcl');
        
        $model = new UserModel();
        $pcl = $model->where('username', 'testpcl')->first();
        $this->assertNotNull($pcl);
        $this->assertEquals(3, $pcl['id_role']); // Role PCL
        $this->assertEquals('Jakarta', $pcl['wilayah_kerja']);
    }

    public function testListPcl()
    {
         // Mock Admin Session
         $session = [
            'id_user' => 1,
            'username' => 'admin',
            'id_role' => 1,
            'is_logged_in' => true
        ];

        $result = $this->withSession($session)->get('admin/pcl');
        $result->assertStatus(200);
        $result->assertSee('Manajemen PCL');
    }
}
