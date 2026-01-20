<?php

declare(strict_types=1);

namespace App\Services\Ai;

interface AiServiceInterface
{
    /**
     * Genera un embedding para el texto dado.
     */
    public function generateEmbedding(string $text): array;

    /**
     * Genera texto basado en un prompt.
     */
    public function generateText(string $prompt): string;

    /**
     * Verifica la conexión con el servicio.
     */
    public function testConnection(): bool;
}
