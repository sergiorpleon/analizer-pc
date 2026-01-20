<?php

declare(strict_types=1);

namespace App\Services\Export;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfExporter implements ExportInterface
{
    public function export(array $results, string $query): void
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $html = '
        <html>
        <head>
            <style>
                body { font-family: sans-serif; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
                th { background-color: #f2f2f2; }
                h1 { color: #667eea; }
            </style>
        </head>
        <body>
            <h1>Informe de Componentes PC</h1>
            <p><strong>Consulta:</strong> ' . htmlspecialchars($query) . '</p>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Similitud</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($results as $result) {
            $html .= '
                    <tr>
                        <td>' . htmlspecialchars($result['nombre']) . '</td>
                        <td>' . htmlspecialchars($result['categoria']) . '</td>
                        <td>' . $result['similarity'] . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('informe_componentes.pdf', ['Attachment' => true]);
        exit;
    }
}
