<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Database;

class UserTest extends TestCase
{
    private $userModel;

    protected function setUp(): void
    {
        $this->userModel = new User();

        // Limpiar tabla de usuarios de test
        try {
            $db = Database::getInstance();
            $pdo = $db->getPdo();
            $pdo->exec("DELETE FROM users WHERE username LIKE 'test_%'");
        } catch (\Exception $e) {
            // Si falla, continuar
        }
    }

    public function testCreateUser()
    {
        $result = $this->userModel->create('test_user', 'password123', 'test@example.com');

        $this->assertTrue($result, 'User should be created successfully');

        // Limpiar
        $this->userModel->delete('test_user');
    }

    public function testFindByUsername()
    {
        // Crear usuario de prueba
        $this->userModel->create('test_find', 'password123', 'find@example.com');

        $user = $this->userModel->findByUsername('test_find');

        $this->assertNotNull($user, 'User should be found');
        $this->assertEquals('test_find', $user['username']);
        $this->assertEquals('find@example.com', $user['email']);

        // Limpiar
        $this->userModel->delete('test_find');
    }

    public function testFindByUsernameNotFound()
    {
        $user = $this->userModel->findByUsername('nonexistent_user');

        $this->assertNull($user, 'Non-existent user should return null');
    }

    public function testVerifyCorrectPassword()
    {
        // Crear usuario de prueba
        $this->userModel->create('test_verify', 'correct_password', 'verify@example.com');

        $user = $this->userModel->verify('test_verify', 'correct_password');

        $this->assertNotNull($user, 'Verification should succeed with correct password');
        $this->assertEquals('test_verify', $user['username']);
        $this->assertArrayNotHasKey('password', $user, 'Password should not be returned');

        // Limpiar
        $this->userModel->delete('test_verify');
    }

    public function testVerifyIncorrectPassword()
    {
        // Crear usuario de prueba
        $this->userModel->create('test_wrong', 'correct_password', 'wrong@example.com');

        $user = $this->userModel->verify('test_wrong', 'wrong_password');

        $this->assertNull($user, 'Verification should fail with incorrect password');

        // Limpiar
        $this->userModel->delete('test_wrong');
    }

    public function testPasswordIsHashed()
    {
        // Crear usuario
        $this->userModel->create('test_hash', 'plain_password', 'hash@example.com');

        $user = $this->userModel->findByUsername('test_hash');

        // El password almacenado no debe ser igual al original
        $this->assertNotEquals('plain_password', $user['password']);

        // Debe empezar con $2y$ (BCrypt)
        $this->assertStringStartsWith('$2y$', $user['password']);

        // Limpiar
        $this->userModel->delete('test_hash');
    }

    public function testUpdatePassword()
    {
        // Crear usuario
        $this->userModel->create('test_update', 'old_password', 'update@example.com');

        // Actualizar contraseña
        $result = $this->userModel->updatePassword('test_update', 'new_password');
        $this->assertTrue($result);

        // Verificar que la nueva contraseña funciona
        $user = $this->userModel->verify('test_update', 'new_password');
        $this->assertNotNull($user);

        // Verificar que la vieja contraseña no funciona
        $userOld = $this->userModel->verify('test_update', 'old_password');
        $this->assertNull($userOld);

        // Limpiar
        $this->userModel->delete('test_update');
    }

    protected function tearDown(): void
    {
        // Limpiar todos los usuarios de test
        try {
            $db = Database::getInstance();
            $pdo = $db->getPdo();
            $pdo->exec("DELETE FROM users WHERE username LIKE 'test_%'");
        } catch (\Exception $e) {
            // Si falla, continuar
        }
    }
}
