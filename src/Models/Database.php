<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;
    private array $config;

    private function __construct()
    {
        $this->config = require __DIR__ . '/../../config/config.php';
        $this->connect();
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect(): void
    {
        try {
            $dbConfig = $this->config['database'];
            $this->pdo = new PDO(
                $dbConfig['dsn'],
                $dbConfig['user'],
                $dbConfig['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $this->pdo->exec("CREATE EXTENSION IF NOT EXISTS vector;");
        } catch (PDOException $e) {
            throw new \Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function initializeTable(): void
    {
        $embeddingSize = $this->config['ollama']['embedding_size'];

        $this->pdo->exec("DROP TABLE IF EXISTS componentes_pc;");
        $this->pdo->exec("CREATE TABLE componentes_pc (
            id SERIAL PRIMARY KEY,
            categoria TEXT,
            nombre TEXT,
            detalles TEXT,
            embedding VECTOR($embeddingSize)
        );");
    }

    public function testConnection(): bool
    {
        try {
            $this->pdo->query("SELECT 1");
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
