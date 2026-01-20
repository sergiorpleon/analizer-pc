<?php

declare(strict_types=1);

namespace App\Services\Export;

class ExportFactory
{
    /**
     * Crea una instancia del exportador basado en el formato.
     * 
     * @param string $format
     * @return ExportInterface
     * @throws \Exception
     */
    public static function create(string $format): ExportInterface
    {
        return match ($format) {
            'json' => new JsonExporter(),
            'xml' => new XmlExporter(),
            'csv' => new CsvExporter(),
            'pdf' => new PdfExporter(),
            default => throw new \Exception("Formato de exportación '$format' no soportado."),
        };
    }
}
