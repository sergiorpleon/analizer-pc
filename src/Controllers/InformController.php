<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\SessionKey;
use App\Services\Export\ExportFactory;

class InformController
{
    /**
     * Maneja la solicitud de exportación de informes.
     */
    public function index(): void
    {
        $format = $_GET['format'] ?? 'json';
        $results = $_SESSION[SessionKey::SEARCH_RESULTS] ?? [];
        $query = $_SESSION[SessionKey::SEARCH_QUERY] ?? 'Sin consulta';

        if (empty($results)) {
            echo "No hay resultados para exportar.";
            return;
        }

        try {
            // Aplicando SOLID: El controlador no sabe CÓMO se exporta, 
            // solo delega la responsabilidad al exportador correspondiente.
            $exporter = ExportFactory::create($format);
            $exporter->export($results, $query);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
