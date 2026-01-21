<?php

declare(strict_types=1);

namespace App\Services\Data;

/**
 * Interfaz para fuentes de datos de componentes.
 */
interface DataSourceInterface
{
    /**
     * Obtiene la lista de documentos (archivos) disponibles.
     * 
     * @return array<string, string> Mapa de [nombre_archivo => contenido_csv]
     */
    public function getDocuments(): array;
}
