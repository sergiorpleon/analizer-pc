<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Database;
use PDO;

class User
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Inicializa la tabla de usuarios
     */
    public function initializeTable(): void
    {
        $pdo = $this->db->getPdo();

        // Crear tabla users si no existe
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id SERIAL PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Verificar si ya existe el usuario admin
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute(['admin']);
        $count = $stmt->fetchColumn();

        // Si no existe, crear usuario admin por defecto
        if ($count == 0) {
            $this->create('admin', 'admin123', 'admin@example.com');
        }
    }

    /**
     * Crea un nuevo usuario
     */
    public function create(string $username, string $password, ?string $email = null): bool
    {
        $pdo = $this->db->getPdo();

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            INSERT INTO users (username, password, email) 
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([$username, $hashedPassword, $email]);
    }

    /**
     * Busca un usuario por username
     */
    public function findByUsername(string $username): ?array
    {
        $pdo = $this->db->getPdo();

        $stmt = $pdo->prepare("
            SELECT id, username, password, email, created_at 
            FROM users 
            WHERE username = ?
        ");

        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    /**
     * Verifica las credenciales de un usuario
     * 
     * @return array|null Retorna los datos del usuario si es válido, null si no
     */
    public function verify(string $username, string $password): ?array
    {
        $user = $this->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // No retornar el password
            unset($user['password']);
            return $user;
        }

        return null;
    }

    /**
     * Actualiza la contraseña de un usuario
     */
    public function updatePassword(string $username, string $newPassword): bool
    {
        $pdo = $this->db->getPdo();

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            UPDATE users 
            SET password = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE username = ?
        ");

        return $stmt->execute([$hashedPassword, $username]);
    }

    /**
     * Obtiene todos los usuarios (sin passwords)
     */
    public function getAll(): array
    {
        $pdo = $this->db->getPdo();

        $stmt = $pdo->query("
            SELECT id, username, email, created_at 
            FROM users 
            ORDER BY created_at DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Elimina un usuario
     */
    public function delete(string $username): bool
    {
        $pdo = $this->db->getPdo();

        $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");

        return $stmt->execute([$username]);
    }
}
