<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Component
{
    private PDO $db;
    private array $config;

    public function __construct()
    {
        $this->db = Database::getInstance()->getPdo();
        $this->config = require __DIR__ . '/../../config/config.php';
    }

    /**
     * Inserta una película en la base de datos
     */
    public function insert(string $categoria, string $nombre, string $detalles, array $embedding): bool
    {
        try {
            $vectorString = '[' . implode(',', $embedding) . ']';

            $stmt = $this->db->prepare(
                "INSERT INTO componentes_pc (categoria, nombre, detalles, embedding) 
                 VALUES (?, ?, ?, ?)"
            );

            return $stmt->execute([$categoria, $nombre, $detalles, $vectorString]);
        } catch (\PDOException $e) {
            throw new \Exception("Error al insertar película: " . $e->getMessage());
        }
    }

    /**
     * Busca películas similares usando búsqueda vectorial
     */
    public function searchSimilar(array $queryVector, ?int $limit = null): array
    {
        if ($limit === null) {
            $limit = $this->config['app']['search_limit'];
        }

        try {
            $vectorString = '[' . implode(',', $queryVector) . ']';

            $sql = "SELECT id, nombre, detalles, categoria, (embedding <-> ?) as distancia 
                    FROM componentes_pc 
                    ORDER BY distancia ASC 
                    LIMIT ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$vectorString, $limit]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error al buscar películas: " . $e->getMessage());
        }
    }

    /**
     * Obtiene todas las películas
     */
    public function getAll(): array
    {
        try {
            $stmt = $this->db->query("SELECT id, categoria, nombre, detalles FROM componentes_pc");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener películas: " . $e->getMessage());
        }
    }

    /**
     * Cuenta el total de películas
     */
    public function count(): int
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM componentes_pc");
            return (int) $stmt->fetchColumn();
        } catch (\PDOException $e) {
            throw new \Exception("Error al contar películas: " . $e->getMessage());
        }
    }

    /**
     * Busca una película por su ID
     */
    public function findById(int $id): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM componentes_pc WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (\PDOException $e) {
            throw new \Exception("Error al buscar película por ID: " . $e->getMessage());
        }
    }
}
