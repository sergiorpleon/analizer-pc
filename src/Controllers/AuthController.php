<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Auth;
use App\Enums\SessionKey;

class AuthController
{
    private Auth $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }

    public function showLogin(): void
    {
        if ($this->auth->isAuthenticated()) {
            header('Location: /');
            exit;
        }

        $error = $_SESSION[SessionKey::LOGIN_ERROR] ?? null;
        unset($_SESSION[SessionKey::LOGIN_ERROR]);

        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->auth->login($username, $password)) {
            header('Location: /');
            exit;
        } else {
            $_SESSION[SessionKey::LOGIN_ERROR] = 'Usuario o contraseña incorrectos';
            header('Location: /login');
            exit;
        }
    }

    public function logout(): void
    {
        $this->auth->logout();
        header('Location: /login');
        exit;
    }
}
