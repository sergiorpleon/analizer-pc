<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Auth;
use App\Models\User;

class AuthTest extends TestCase
{
    private $auth;
    private $userModel;

    protected function setUp(): void
    {
        // Limpiar sesión
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];

        $this->auth = new Auth();
        $this->userModel = new User();

        // Crear usuario de prueba
        try {
            $this->userModel->create('test_auth_user', 'test_password', 'auth@test.com');
        } catch (\Exception $e) {
            // Usuario puede ya existir
        }
    }

    public function testLoginSuccess()
    {
        $result = $this->auth->login('test_auth_user', 'test_password');

        $this->assertTrue($result, 'Login should succeed with correct credentials');
        $this->assertTrue($this->auth->isAuthenticated(), 'User should be authenticated after login');
    }

    public function testLoginFailureWrongPassword()
    {
        $result = $this->auth->login('test_auth_user', 'wrong_password');

        $this->assertFalse($result, 'Login should fail with wrong password');
        $this->assertFalse($this->auth->isAuthenticated(), 'User should not be authenticated');
    }

    public function testLoginFailureNonexistentUser()
    {
        $result = $this->auth->login('nonexistent_user', 'any_password');

        $this->assertFalse($result, 'Login should fail with nonexistent user');
        $this->assertFalse($this->auth->isAuthenticated(), 'User should not be authenticated');
    }

    public function testIsAuthenticatedAfterLogin()
    {
        $this->auth->login('test_auth_user', 'test_password');

        $this->assertTrue($this->auth->isAuthenticated());
    }

    public function testIsNotAuthenticatedBeforeLogin()
    {
        $this->assertFalse($this->auth->isAuthenticated());
    }

    public function testGetUserAfterLogin()
    {
        $this->auth->login('test_auth_user', 'test_password');

        $user = $this->auth->getUser();

        $this->assertNotNull($user);
        $this->assertEquals('test_auth_user', $user['username']);
        $this->assertEquals('auth@test.com', $user['email']);
        $this->assertTrue($user['logged_in']);
        $this->assertArrayHasKey('login_time', $user);
    }

    public function testGetUserBeforeLogin()
    {
        $user = $this->auth->getUser();

        $this->assertNull($user);
    }

    public function testLogout()
    {
        // Login primero
        $this->auth->login('test_auth_user', 'test_password');
        $this->assertTrue($this->auth->isAuthenticated());

        // Logout
        $this->auth->logout();

        // Verificar que ya no está autenticado
        $this->assertFalse($this->auth->isAuthenticated());
        $this->assertNull($this->auth->getUser());
    }

    public function testSessionContainsUserData()
    {
        $this->auth->login('test_auth_user', 'test_password');

        $this->assertArrayHasKey('user', $_SESSION);
        $this->assertArrayHasKey('username', $_SESSION['user']);
        $this->assertArrayHasKey('email', $_SESSION['user']);
        $this->assertArrayHasKey('logged_in', $_SESSION['user']);
        $this->assertArrayHasKey('login_time', $_SESSION['user']);
    }

    protected function tearDown(): void
    {
        // Limpiar usuario de prueba
        try {
            $this->userModel->delete('test_auth_user');
        } catch (\Exception $e) {
            // Si falla, continuar
        }

        // Limpiar sesión
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
