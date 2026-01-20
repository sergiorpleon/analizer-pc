<?php

namespace App\Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Controllers\AuthController;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    private $authController;
    private $userModel;

    protected function setUp(): void
    {
        // Limpiar sesión
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
        $_POST = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->authController = new AuthController();
        $this->userModel = new User();

        // Crear usuario de prueba
        try {
            $this->userModel->create('test_login_user', 'test_password', 'login@test.com');
        } catch (\Exception $e) {
            // Usuario puede ya existir
        }
    }

    public function testShowLoginDisplaysForm()
    {
        ob_start();
        $this->authController->showLogin();
        $output = ob_get_clean();

        $this->assertStringContainsString('Iniciar Sesión', $output);
        $this->assertStringContainsString('form', $output);
        $this->assertStringContainsString('username', $output);
        $this->assertStringContainsString('password', $output);
    }

    public function testShowLoginHasCorrectFormAction()
    {
        ob_start();
        $this->authController->showLogin();
        $output = ob_get_clean();

        $this->assertStringContainsString('action="/login"', $output);
        $this->assertStringContainsString('method="POST"', $output);
    }

    public function testShowLoginHasUsernameField()
    {
        ob_start();
        $this->authController->showLogin();
        $output = ob_get_clean();

        $this->assertStringContainsString('name="username"', $output);
        $this->assertStringContainsString('type="text"', $output);
    }

    public function testShowLoginHasPasswordField()
    {
        ob_start();
        $this->authController->showLogin();
        $output = ob_get_clean();

        $this->assertStringContainsString('name="password"', $output);
        $this->assertStringContainsString('type="password"', $output);
    }

    public function testShowLoginHasSubmitButton()
    {
        ob_start();
        $this->authController->showLogin();
        $output = ob_get_clean();

        $this->assertStringContainsString('type="submit"', $output);
    }

    public function testShowLoginDisplaysErrorMessage()
    {
        $_SESSION['login_error'] = 'Test error message';

        ob_start();
        $this->authController->showLogin();
        $output = ob_get_clean();

        $this->assertStringContainsString('Test error message', $output);
    }

    public function testLoginFormHasCredentialsHint()
    {
        ob_start();
        $this->authController->showLogin();
        $output = ob_get_clean();

        // Debe mostrar las credenciales por defecto
        $this->assertStringContainsString('admin', $output);
        $this->assertStringContainsString('admin123', $output);
    }

    protected function tearDown(): void
    {
        // Limpiar usuario de prueba
        try {
            $this->userModel->delete('test_login_user');
        } catch (\Exception $e) {
            // Si falla, continuar
        }

        // Limpiar variables
        $_SESSION = [];
        $_POST = [];
        $_SERVER = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
