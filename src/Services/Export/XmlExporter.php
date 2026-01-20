<?php

declare(strict_types=1);

namespace App\Services\Export;

class XmlExporter implements ExportInterface
{
    public function export(array $results, string $query): void
    {
        header('Content-Type: text/xml');
        header('Content-Disposition: attachment; filename="informe_componentes.xml"');

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><informe/>');
        $xml->addChild('consulta', htmlspecialchars($query));
        $items = $xml->addChild('componentes');

        foreach ($results as $result) {
            $item = $items->addChild('componente');
            $item->addChild('nombre', htmlspecialchars($result['nombre']));
            $item->addChild('categoria', htmlspecialchars($result['categoria']));
            $item->addChild('detalles', htmlspecialchars($result['detalles']));
            $item->addChild('similitud', (string) $result['similarity']);
        }

        echo $xml->asXML();
        exit;
    }
}
