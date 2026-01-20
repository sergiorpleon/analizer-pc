<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Database;
use App\Models\Component;
use App\Services\Ai\OllamaService;
use App\Services\Data\DataImporter;
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

        // Inyectamos dependencias (o las instanciamos siguiendo la nueva estructura)
        $this->importer = new DataImporter(
            new Component(),
            new OllamaService(),
            new FileLoader()
        );

        // Requerir autenticación
        $auth = new Auth();
        $auth->requireAuth();
    }

    public function import(): void
    {
        set_time_limit(0);

        echo "<h2>Iniciando poblamiento de Base de Datos...</h2>";

        try {
            $this->db->initializeTable();
            echo "<p>✅ Tabla inicializada correctamente.</p>";
        } catch (\Exception $e) {
            die("<p>❌ Error al inicializar tabla: " . $e->getMessage() . "</p>");
        }

        $this->runImportProcess();

        echo "<h3>¡Base de datos cargada con éxito!</h3>";
    }

    private function runImportProcess(): void
    {
        $dataSource = $this->config['data']['source'];
        $files = $this->config['data']['files'];
        $importLimit = $this->config['data']['import_limit'];

        echo "<h2>Iniciando importación de componentes...</h2>";
        echo "<p>Fuente de datos: <strong>" . strtoupper($dataSource) . "</strong></p>";

        foreach ($files as $file) {
            echo "<p><strong>Procesando $file...</strong></p>";

            try {
                $source = ($dataSource === 'local')
                    ? $this->config['data']['local_path'] . $file
                    : $this->config['data']['base_url'] . $file;

                $this->importer->importFromCsv($source, $file, $importLimit);
            } catch (\Exception $e) {
                echo "<p>❌ Error procesando $file: " . $e->getMessage() . "</p>";
            }
        }
    }
}
