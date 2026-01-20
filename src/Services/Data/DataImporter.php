<?php

declare(strict_types=1);

namespace App\Services\Data;

use App\Models\Component;
use App\Services\Ai\AiServiceInterface;

class DataImporter
{
    private Component $componentModel;
    private AiServiceInterface $aiService;
    private FileLoader $fileLoader;

    public function __construct(
        Component $componentModel,
        AiServiceInterface $aiService,
        FileLoader $fileLoader
    ) {
        $this->componentModel = $componentModel;
        $this->aiService = $aiService;
        $this->fileLoader = $fileLoader;
    }

    /**
     * Importa componentes desde un archivo CSV.
     */
    public function importFromCsv(string $source, string $filename, int $limit): void
    {
        $csvData = $this->fileLoader->load($source);
        $lines = explode("\n", $csvData);
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

            $this->processRow($row, $headers, $filename);
            $count++;
        }
    }

    private function processRow(array $row, array $headers, string $filename): void
    {
        $nombre = $row[0];
        $detalles = "Componente: $filename. ";

        foreach ($headers as $index => $header) {
            if (isset($row[$index])) {
                $detalles .= "$header: {$row[$index]}. ";
            }
        }

        // Generar embedding usando la abstracción del servicio AI
        $embedding = $this->aiService->generateEmbedding($detalles);

        // Guardar en base de datos
        $this->componentModel->insert($filename, $nombre, $detalles, $embedding);

        echo "<p>   ✅ Importado: $nombre</p>";
    }
}
