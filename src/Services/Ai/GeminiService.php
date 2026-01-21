<?php

declare(strict_types=1);

namespace App\Services\Ai;

use Exception;

/**
 * Implementación de Gemini para generación de embeddings.
 * Utiliza cURL nativo para máxima compatibilidad y rendimiento.
 */
final class GeminiService implements EmbeddingServiceInterface
{
    private string $model = 'text-embedding-004';
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct(
        private string $apiKey
    ) {
        if (empty($this->apiKey)) {
            throw new Exception("Gemini API Key no configurada.");
        }
    }

    public function getEmbedding(string $text): array
    {
        $url = "{$this->baseUrl}{$this->model}:embedContent?key={$this->apiKey}";

        $payload = [
            'model' => "models/{$this->model}",
            'content' => [
                'parts' => [
                    ['text' => $text]
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new Exception("Error de cURL en Gemini: $error");
        }

        if ($httpCode !== 200) {
            throw new Exception("Gemini API devolvió código $httpCode: $response");
        }

        $data = json_decode($response, true);
        return $data['embedding']['values'] ?? throw new Exception("Formato de respuesta de Gemini inválido.");
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
