<?php

declare(strict_types=1);

namespace App\Services\Export;

class CsvExporter implements ExportInterface
{
    public function export(array $results, string $query): void
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="informe_componentes.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Nombre', 'Categoría', 'Detalles', 'Similitud']);

        foreach ($results as $result) {
            fputcsv($output, [
                $result['nombre'],
                $result['categoria'],
                $result['detalles'],
                $result['similarity']
            ]);
        }

        fclose($output);
        exit;
    }
}
