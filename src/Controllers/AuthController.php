<?php
// src/Controllers/AuthController.php

namespace App\Controllers;

use App\Models\Auth;

class AuthController
{
    private $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }

    /**
     * Muestra el formulario de login
     */
    public function showLogin()
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
    public function login()
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
    public function logout()
    {
        $this->auth->logout();
        header('Location: /login');
        exit;
    }
}
