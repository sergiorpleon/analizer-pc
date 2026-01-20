<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Auth;

class AuthController
{
    private Auth $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }

    /**
     * Muestra el formulario de login
     */
    public function showLogin(): void
    {
        // Si ya está autenticado, redirigir al inicio
        if ($this->auth->isAuthenticated()) {
            header('Location: /');
            exit;
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require __DIR__ . '/../Views/auth/login.php';
    }

    /**
     * Procesa el login
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->auth->login($username, $password)) {
            // Login exitoso
            header('Location: /');
            exit;
        } else {
            // Login fallido
            $_SESSION['login_error'] = 'Usuario o contraseña incorrectos';
            header('Location: /login');
            exit;
        }
    }

    /**
     * Cierra la sesión
     */
    public function logout(): void
    {
        $this->auth->logout();
        header('Location: /login');
        exit;
    }
}
