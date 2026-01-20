<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Database;
use App\Services\Ai\OllamaService;

class HomeController
{
    /**
     * Muestra la página principal con tests de conexión
     */
    public function index(): void
    {
        $messages = [];

        // Test de conexión a PostgreSQL
        try {
            $db = Database::getInstance();
            if ($db->testConnection()) {
                $messages[] = [
                    'type' => 'success',
                    'text' => '✅ Conexión a Postgres y PGVector exitosa.'
                ];
            }
        } catch (\Exception $e) {
            $messages[] = [
                'type' => 'error',
                'text' => '❌ Error en BD: ' . $e->getMessage()
            ];
        }

        // Test de conexión a Ollama
        try {
            $ollama = new OllamaService();
            if ($ollama->testConnection()) {
                $messages[] = [
                    'type' => 'success',
                    'text' => '✅ Conexión con Ollama exitosa.'
                ];
            }
        } catch (\Exception $e) {
            $messages[] = [
                'type' => 'error',
                'text' => '❌ Error en Ollama: ' . $e->getMessage()
            ];
        }

        require __DIR__ . '/../Views/home.php';
    }
}
