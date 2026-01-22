<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Database;
use App\Models\Component;
use App\Services\Ai\EmbeddingFactory;
use App\Services\Data\DataImporter;
use App\Services\Data\DataSourceFactory;
use App\Services\Data\FileLoader;
use App\Models\Auth;

class DataController
{
    private array $config;
    private DataImporter $importer;
    private Database $db;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/config.php';
        $this->db = Database::getInstance();

        // Inyectamos dependencias usando las nuevas factorías SOLID
        $this->importer = new DataImporter(
            new Component(),
            EmbeddingFactory::create(),
            new FileLoader()
        );

        // Requerir autenticación
        $auth = new Auth();
        $auth->requireAuth();
    }

    public function import(): void
    {
        // Si es una petición POST, ejecutamos la importación
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_import'])) {
            $this->executeImport();
            return;
        }

        // Si es GET, mostramos la página de confirmación
        $showConfirmation = true;
        require __DIR__ . '/../Views/data/import.php';
    }

    private function executeImport(): void
    {
        set_time_limit(0);

        // Iniciamos el buffer para capturar el progreso
        ob_start();

        echo "<div class='text-blue-400'>[INFO] Iniciando poblamiento de Base de Datos...</div>";

        try {
            $this->db->initializeTable();
            echo "<div class='text-green-400'>[OK] Tabla inicializada correctamente.</div>";
        } catch (\Exception $e) {
            echo "<div class='text-red-400'>[ERROR] " . htmlspecialchars($e->getMessage()) . "</div>";
            $importOutput = ob_get_clean();
            $showConfirmation = false;
            require __DIR__ . '/../Views/data/import.php';
            return;
        }

        $this->runImportProcess();

        echo "<div class='text-google-yellow font-bold mt-4'>[FIN] ¡Base de datos cargada con éxito!</div>";

        $importOutput = ob_get_clean();
        $showConfirmation = false;
        require __DIR__ . '/../Views/data/import.php';
    }

    private function runImportProcess(): void
    {
        try {
            // Usamos la factoría de origen de datos
            $dataSource = DataSourceFactory::create();
            $documents = $dataSource->getDocuments();
            $importLimit = $this->config['data']['import_limit'];

            if (empty($documents)) {
                echo "<div class='text-amber-400'>[WARN] No se encontraron documentos para importar.</div>";
                return;
            }

            foreach ($documents as $filename => $content) {
                echo "<div class='text-gray-300'>[FILE] Procesando $filename...</div>";

                // Nota: DataImporter.importFromCsv espera un path, pero ahora tenemos el contenido.
                // Refactorizamos DataImporter para procesar contenido directamente o adaptamos aquí.
                // Para mantener compatibilidad, creamos un archivo temporal o mejor refactorizamos DataImporter.

                $this->processDocumentContent($filename, $content, $importLimit);
            }
        } catch (\Exception $e) {
            echo "<div class='text-red-400'>[ERROR] Error en el proceso de importación: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }

    /**
     * Endpoint para streaming de progreso vía Server-Sent Events (SSE)
     */
    public function streamImport(): void
    {
        // Desactivar buffer de salida de PHP
        if (ob_get_level())
            ob_end_clean();

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Para Nginx

        $this->sendSseMessage(0, 'Iniciando proceso de importación...');

        try {
            $this->db->initializeTable();
            $this->sendSseMessage(0, 'Tabla inicializada correctamente.');
        } catch (\Exception $e) {
            $this->sendSseMessage(0, 'Error al inicializar tabla: ' . $e->getMessage(), true);
            return;
        }

        try {
            $dataSource = DataSourceFactory::create();
            $documents = $dataSource->getDocuments();
            $importLimit = $this->config['data']['import_limit'];

            if (empty($documents)) {
                $this->sendSseMessage(100, 'No se encontraron documentos.', true);
                return;
            }

            // 1. Calcular total de líneas para el porcentaje
            $totalLines = 0;
            foreach ($documents as $content) {
                // Restamos 1 por el header y contamos líneas no vacías (aproximación)
                $lines = count(explode("\n", trim($content))) - 1;
                $totalLines += min($lines, $importLimit);
            }

            if ($totalLines <= 0)
                $totalLines = 1;

            // 2. Procesar
            $processedLines = 0;
            foreach ($documents as $filename => $content) {
                $this->sendSseMessage(
                    (int) (($processedLines / $totalLines) * 100),
                    "Procesando archivo: $filename..."
                );

                $lines = explode("\n", $content);
                $headers = str_getcsv(array_shift($lines));

                $fileCount = 0;
                foreach ($lines as $line) {
                    if (empty(trim($line)) || $fileCount >= $importLimit)
                        continue;

                    $row = str_getcsv($line);
                    if (count($row) < 2)
                        continue;

                    // Capturar output del importer (que hace echo)
                    ob_start();
                    $this->importer->processRowDirectly($row, $headers, $filename);
                    $logOutput = ob_get_clean();

                    // Limpiar el HTML del log para enviarlo limpio o mantenerlo si el frontend lo soporta
                    // Aquí enviamos el HTML tal cual porque el frontend lo insertará

                    $processedLines++;
                    $fileCount++;

                    $percent = (int) (($processedLines / $totalLines) * 100);

                    // Enviar actualización cada X líneas para no saturar o cada línea si son pocas
                    $this->sendSseMessage($percent, trim($logOutput));
                }
            }

            $this->sendSseMessage(100, 'Importación completada con éxito.', true);

        } catch (\Exception $e) {
            $this->sendSseMessage(0, 'Error fatal: ' . $e->getMessage(), true);
        }
    }

    private function sendSseMessage(int $progress, string $log, bool $done = false): void
    {
        $data = [
            'progress' => $progress,
            'log' => $log,
            'done' => $done
        ];
        echo "data: " . json_encode($data) . "\n\n";
        flush();
    }

    private function processDocumentContent(string $filename, string $content, int $limit): void
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

            // Delegamos el procesamiento de la fila al importador
            // (Necesitaremos un método público en DataImporter o mover la lógica aquí)
            // Por simplicidad y siguiendo SOLID, el importador debería manejar el contenido.

            $this->importer->processRowDirectly($row, $headers, $filename);
            $count++;
        }
    }
}
