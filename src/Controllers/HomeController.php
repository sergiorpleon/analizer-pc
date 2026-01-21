<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Database;

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

        // Test de conexión al Proveedor de IA (Ollama o Gemini)
        try {
            $aiService = \App\Services\Ai\EmbeddingFactory::create();
            if ($aiService->testConnection()) {
                $providerName = getenv('EMBEDDING_PROVIDER') === 'gemini' ? 'Gemini' : 'Ollama';
                $messages[] = [
                    'type' => 'success',
                    'text' => "✅ Conexión con $providerName exitosa."
                ];
            }
        } catch (\Exception $e) {
            $messages[] = [
                'type' => 'error',
                'text' => '❌ Error en IA: ' . $e->getMessage()
            ];
        }

        require __DIR__ . '/../Views/home.php';
    }
}
