<?php

declare(strict_types=1);

namespace App\Services\Ai;

use Exception;

/**
 * Implementación de Ollama para generación de embeddings local.
 */
final class OllamaService implements EmbeddingServiceInterface
{
    public function __construct(
        private string $baseUrl,
        private string $model
    ) {
    }

    public function getEmbedding(string $text): array
    {
        $payload = [
            'model' => $this->model,
            'prompt' => $text
        ];

        $ch = curl_init("{$this->baseUrl}/api/embeddings");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new Exception("Error de conexión con Ollama: $error. ¿Está el contenedor encendido?");
        }

        if ($httpCode !== 200) {
            throw new Exception("Ollama devolvió código $httpCode: $response");
        }

        $data = json_decode($response, true);
        return $data['embedding'] ?? throw new Exception("Ollama no devolvió un embedding válido.");
    }

    public function testConnection(): bool
    {
        try {
            $this->getEmbedding('ping');
            return true;
        } catch (Exception) {
            return false;
        }
    }
}
