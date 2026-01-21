<?php

declare(strict_types=1);

namespace App\Services\Ai;

use Exception;

/**
 * Factoría para instanciar el servicio de embeddings correcto.
 */
final class EmbeddingFactory
{
    public static function create(): EmbeddingServiceInterface
    {
        // Usar $_ENV en lugar de getenv() porque Dotenv carga en $_ENV
        $provider = $_ENV['EMBEDDING_PROVIDER'] ?? getenv('EMBEDDING_PROVIDER') ?: 'ollama';
        $config = require __DIR__ . '/../../../config/config.php';

        return match ($provider) {
            'gemini' => new GeminiService(
                apiKey: $_ENV['GEMINI_API_KEY'] ?? getenv('GEMINI_API_KEY') ?: ($config['ai']['gemini_key'] ?? '')
            ),
            'ollama' => new OllamaService(
                baseUrl: $config['ollama']['url'] ?? 'http://ollama:11434',
                model: $config['ollama']['model'] ?? 'llama3'
            ),
            default => throw new Exception("Proveedor de embeddings no soportado: $provider"),
        };
    }
}
