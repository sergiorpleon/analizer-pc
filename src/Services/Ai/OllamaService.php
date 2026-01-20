<?php

declare(strict_types=1);

namespace App\Services\Ai;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OllamaService implements AiServiceInterface
{
    private Client $client;
    private string $baseUrl;
    private string $model;

    public function __construct()
    {
        $config = require __DIR__ . '/../../../config/config.php';
        $this->client = new Client();
        $this->baseUrl = $config['ollama']['url'];
        $this->model = $config['ollama']['model'];
    }

    public function generateEmbedding(string $text): array
    {
        try {
            $response = $this->client->post($this->baseUrl . '/api/embeddings', [
                'json' => [
                    'model' => $this->model,
                    'prompt' => $text
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['embedding'] ?? [];
        } catch (GuzzleException $e) {
            throw new \Exception("Error al generar embedding: " . $e->getMessage());
        }
    }

    public function generateText(string $prompt): string
    {
        try {
            $response = $this->client->post($this->baseUrl . '/api/generate', [
                'json' => [
                    'model' => $this->model,
                    'prompt' => $prompt,
                    'stream' => false
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['response'] ?? '';
        } catch (GuzzleException $e) {
            throw new \Exception("Error al generar texto: " . $e->getMessage());
        }
    }

    public function testConnection(): bool
    {
        try {
            $this->generateText('Hola');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
