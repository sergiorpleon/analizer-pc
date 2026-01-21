<?php

declare(strict_types=1);

namespace App\Services\Data;

use App\Models\Component;
use App\Services\Ai\EmbeddingServiceInterface;

/**
 * Servicio encargado de procesar e importar datos de películas.
 */
final class DataImporter
{
    public function __construct(
        private Component $componentModel,
        private EmbeddingServiceInterface $embeddingService,
        private FileLoader $fileLoader
    ) {
    }

    /**
     * Importa películas desde un archivo CSV (Legacy path support).
     */
    public function importFromCsv(string $source, string $filename, int $limit): void
    {
        $csvData = $this->fileLoader->load($source);
        $this->importFromContent($csvData, $filename, $limit);
    }

    /**
     * Importa películas directamente desde el contenido de un CSV.
     */
    public function importFromContent(string $content, string $filename, int $limit): void
    {
        $lines = explode("\n", $content);
        $headers = str_getcsv(array_shift($lines));

        $count = 0;
        foreach ($lines as $line) {
            if (empty(trim($line)) || $count >= $limit) {
                continue;
            }

            $row = str_getcsv($line);
            if (count($row) < 2) {
                continue;
            }

            $this->processRowDirectly($row, $headers, $filename);
            $count++;
        }
    }

    /**
     * Procesa una fila individual de datos.
     */
    public function processRowDirectly(array $row, array $headers, string $filename): void
    {
        $nombre = $row[1];
        $detalles = "Película: $filename. ";

        foreach ($headers as $index => $header) {
            if (isset($row[$index])) {
                $detalles .= "$header: {$row[$index]}. ";
            }
        }

        try {
            // Generar embedding usando la abstracción del servicio AI
            $embedding = $this->embeddingService->getEmbedding($detalles);

            // Extraer la categoría del nombre del archivo (sin la extensión .csv)
            $categoria = $row[5];//str_replace('.csv', '', $filename);

            // Guardar en base de datos (categoria, nombre, detalles, embedding)
            $this->componentModel->insert($categoria, $nombre, $detalles, $embedding);

            echo "<div class='text-gray-400'>   [OK] Importado: $nombre</div>";
        } catch (\Exception $e) {
            echo "<div class='text-red-400'>   [ERROR] Error en $nombre: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
