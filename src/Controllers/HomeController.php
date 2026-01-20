<?php
// src/Controllers/HomeController.php

namespace App\Controllers;

use App\Models\Database;
use App\Models\OllamaService;

class HomeController
{
    /**
     * Muestra la página principal con tests de conexión
     */
    public function index()
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
            $response = $ollama->generateText('Dime hola en una frase corta');
            $messages[] = [
                'type' => 'success',
                'text' => '✅ Ollama responde: ' . $response
            ];
        } catch (\Exception $e) {
            $messages[] = [
                'type' => 'error',
                'text' => '❌ Error en Ollama: ' . $e->getMessage()
            ];
        }

        // Cargar vista
        require __DIR__ . '/../Views/home.php';
    }
}
