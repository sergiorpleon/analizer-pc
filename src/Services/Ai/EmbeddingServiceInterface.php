<?php

declare(strict_types=1);

namespace App\Services\Ai;

/**
 * Interfaz para servicios de generación de embeddings.
 * Sigue el principio de Segregación de Interfaces.
 */
interface EmbeddingServiceInterface
{
    /**
     * Genera un vector numérico (embedding) para el texto dado.
     * 
     * @param string $text El texto a procesar.
     * @return array<float> El vector resultante.
     * @throws \Exception Si ocurre un error en la generación.
     */
    public function getEmbedding(string $text): array;

    /**
     * Verifica la conexión con el servicio.
     */
    public function testConnection(): bool;
}
