<?php

namespace App\Tests;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;

/**
 * AppTest: Kerangka Unit Test untuk aplikasi Lokasi Kuliner & Review Jajanan.
 *
 * Mencakup 3 skenario uji utama:
 * 1. Login Authentication    - Verifikasi proses autentikasi pengguna
 * 2. Form Validation         - Verifikasi validasi input form
 * 3. Route Protection Filter - Verifikasi proteksi route berdasarkan role
 */
class AppTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    /**
     * Seed yang digunakan pada setiap test.
     *
     * @var array|string
     */
    protected $seed = 'App\Database\Seeds\KulinerSeeder';

    /**
     * Namespace migration yang digunakan.
     *
     * @var string
     */
    protected $namespace = 'App';

    /**
     * Helper: Simulasi login sebagai contributor.
     */
    private function loginAsContributor(): void
    {
        session()->set([
            'isLoggedIn' => true,
            'user_id'    => 2,
            'username'   => 'budi_s',
            'role'       => 'contributor',
        ]);
    }

    /**
     * Helper: Simulasi login sebagai admin.
     */
    private function loginAsAdmin(): void
    {
        session()->set([
            'isLoggedIn' => true,
            'user_id'    => 1,
            'username'   => 'admin',
            'role'       => 'admin',
        ]);
    }

    // ======================================================================
    // 1. LOGIN AUTHENTICATION TESTS
    // ======================================================================

    /**
     * Test: Login berhasil dengan kredensial yang valid.
     */
    public function testLoginSuccess(): void
    {
        $result = $this->call('post', '/login', [
            'email'    => 'admin@udinus.ac.id',
            'password' => 'admin123',
        ]);

        $result->assertStatus(302);
        $result->assertRedirect('');

        $session = session();
        $this->assertTrue($session->get('isLoggedIn') === true);
        $this->assertEquals('admin', $session->get('role'));
    }

    /**
     * Test: Login gagal dengan password yang salah.
     */
    public function testLoginFailedWrongPassword(): void
    {
        $result = $this->call('post', '/login', [
            'email'    => 'admin@udinus.ac.id',
            'password' => 'salah123',
        ]);

        $result->assertStatus(302);
        $result->assertRedirect('/login');

        $session = session();
        $this->assertNull($session->get('isLoggedIn'));
    }

    /**
     * Test: Login gagal dengan email yang tidak terdaftar.
     */
    public function testLoginFailedUnregisteredEmail(): void
    {
        $result = $this->call('post', '/login', [
            'email'    => 'notexist@example.com',
            'password' => 'any_password',
        ]);

        $result->assertStatus(302);
        $result->assertRedirect('/login');

        $session = session();
        $this->assertNull($session->get('isLoggedIn'));
    }

    // ======================================================================
    // 2. FORM VALIDATION TESTS
    // ======================================================================

    /**
     * Test: Registrasi gagal jika field wajib kosong.
     */
    public function testRegisterValidationRequiredFields(): void
    {
        $result = $this->call('post', '/register', [
            'username'  => '',
            'email'     => '',
            'password'  => '',
            'full_name' => '',
        ]);

        $result->assertStatus(302);
        $result->assertRedirect('/register');

        $users = model(UserModel::class);
        $count = $users->where('email', '')->countAllResults();
        $this->assertEquals(0, $count);
    }

    /**
     * Test: Registrasi gagal jika email tidak valid.
     */
    public function testRegisterValidationInvalidEmail(): void
    {
        $result = $this->call('post', '/register', [
            'username'  => 'newuser',
            'email'     => 'bukan-email',
            'password'  => 'password123',
            'full_name' => 'New User',
        ]);

        $result->assertStatus(302);
        $result->assertRedirect('/register');
    }

    /**
     * Test: Registrasi gagal jika password kurang dari 6 karakter.
     */
    public function testRegisterValidationShortPassword(): void
    {
        $result = $this->call('post', '/register', [
            'username'  => 'newuser',
            'email'     => 'newuser@test.com',
            'password'  => '12345',
            'full_name' => 'New User',
        ]);

        $result->assertStatus(302);
        $result->assertRedirect('/register');
    }

    /**
     * Test: Submit tempat kuliner gagal jika field wajib kosong.
     * (Memerlukan login sebagai contributor agar melewati AuthFilter)
     */
    public function testPlaceValidationRequiredFields(): void
    {
        $this->loginAsContributor();

        $result = $this->call('post', '/places', [
            'name'         => '',
            'address'      => '',
            'category_id'  => '',
        ]);

        $result->assertStatus(302);
    }

    /**
     * Test: Submit review gagal jika rating di luar jangkauan.
     * (Memerlukan login sebagai contributor agar melewati AuthFilter)
     */
    public function testReviewValidationRatingOutOfRange(): void
    {
        $this->loginAsContributor();

        $result = $this->call('post', '/places/1/reviews', [
            'rating'  => 6,
            'comment' => 'Bagus!',
        ]);

        $result->assertStatus(302);
    }

    // ======================================================================
    // 3. ROUTE PROTECTION FILTER TESTS
    // ======================================================================

    /**
     * Test: Route terproteksi redirect ke login jika belum login.
     */
    public function testAuthFilterRedirectsUnauthenticated(): void
    {
        $result = $this->call('get', '/dashboard');

        $result->assertStatus(302);
        $result->assertRedirect('/login');
    }

    /**
     * Test: Route admin terproteksi, contributor tidak bisa mengakses.
     * RoleFilter mengembalikan response 403 untuk user tanpa hak akses.
     */
    public function testRoleFilterBlocksContributorFromAdmin(): void
    {
        $this->loginAsContributor();

        $result = $this->call('get', '/admin/dashboard');

        $this->assertContains(
            $result->response()->getStatusCode(),
            [403, 302],
            'Contributor seharusnya tidak bisa mengakses halaman admin.'
        );
    }

    /**
     * Test: Admin bisa mengakses route admin (tidak diblokir RoleFilter).
     */
    public function testRoleFilterAllowsAdminAccess(): void
    {
        $this->loginAsAdmin();

        $result = $this->call('get', '/admin/dashboard');

        $statusCode = $result->response()->getStatusCode();

        $this->assertNotEquals(403, $statusCode, 'Admin seharusnya bisa mengakses halaman admin.');

        $this->assertNotEquals(302, $statusCode, 'Admin seharusnya tidak di-redirect dari halaman admin.');
    }

    /**
     * Test: Route favorit terproteksi dari pengguna yang belum login.
     */
    public function testFavoriteRouteProtectedFromGuest(): void
    {
        $result = $this->call('post', '/places/1/favorite');

        $result->assertStatus(302);
        $result->assertRedirect('/login');
    }

    /**
     * Test: Route notifikasi terproteksi dari pengguna yang belum login.
     */
    public function testNotificationRouteProtectedFromGuest(): void
    {
        $result = $this->call('get', '/notifications');

        $result->assertStatus(302);
        $result->assertRedirect('/login');
    }
}
