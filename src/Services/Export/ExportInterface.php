<?php

declare(strict_types=1);

namespace App\Services\Export;

interface ExportInterface
{
    /**
     * Exporta los datos en el formato específico.
     * 
     * @param array $results Resultados de la búsqueda.
     * @param string $query Consulta realizada.
     */
    public function export(array $results, string $query): void;
}
