<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\User;
use App\Enums\SessionKey;

class Auth
{
    private array $config;
    private User $userModel;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/config.php';
        $this->userModel = new User();

        if (session_status() === PHP_SESSION_NONE) {
            session_name($this->config['auth']['session_name']);
            session_start();
        }

        try {
            $this->userModel->initializeTable();
        } catch (\Exception $e) {
            // Silently fail if table already exists or other DB issues
        }
    }

    public function login(string $username, string $password): bool
    {
        $user = $this->userModel->verify($username, $password);

        if ($user) {
            $_SESSION[SessionKey::USER] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'] ?? null,
                'logged_in' => true,
                'login_time' => time()
            ];

            return true;
        }

        return false;
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION[SessionKey::USER]) && $_SESSION[SessionKey::USER]['logged_in'] === true;
    }

    public function getUser(): ?array
    {
        return $_SESSION[SessionKey::USER] ?? null;
    }

    public function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }

    public function getUserModel(): User
    {
        return $this->userModel;
    }
}
