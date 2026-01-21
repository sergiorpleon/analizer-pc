<?php

declare(strict_types=1);

namespace App\Services\Export;

class JsonExporter implements ExportInterface
{
    public function export(array $results, string $query): void
    {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="informe_peliculas.json"');
        echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
