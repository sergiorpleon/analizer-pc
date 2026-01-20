<?php
// src/Controllers/DataController.php

namespace App\Controllers;

use App\Models\Database;
use App\Models\Component;
use App\Models\OllamaService;

class DataController
{
    private $config;
    private $componentModel;
    private $ollamaService;
    private $db;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/config.php';
        $this->componentModel = new Component();
        $this->ollamaService = new OllamaService();
        $this->db = Database::getInstance();
    }

    /**
     * Verifica la autenticación mediante clave
     */
    private function checkAuth()
    {
        $key = $_GET['key'] ?? '';
        if ($key !== $this->config['data']['access_key']) {
            die("Acceso no autorizado. Debes proporcionar la clave correcta.");
        }
    }

    /**
     * Importa datos desde archivos CSV
     */
    public function import()
    {
        $this->checkAuth();
        set_time_limit(0);

        echo "<h2>Iniciando poblamiento de Base de Datos...</h2>";

        // Inicializar tabla
        try {
            $this->db->initializeTable();
            echo "<p>✅ Tabla inicializada correctamente.</p>";
        } catch (\Exception $e) {
            die("<p>❌ Error al inicializar tabla: " . $e->getMessage() . "</p>");
        }

        // Procesar archivos CSV
        $this->processCSVFiles();

        echo "<h3>¡Base de datos cargada con éxito!</h3>";
    }

    /**
     * Procesa todos los archivos CSV configurados
     */
    private function processCSVFiles()
    {
        $baseUrl = $this->config['data']['base_url'];
        $files = $this->config['data']['files'];
        $importLimit = $this->config['data']['import_limit'];

        echo "<h2>Iniciando importación de componentes...</h2>";

        foreach ($files as $file) {
            echo "<p><strong>Procesando $file...</strong></p>";

            try {
                $this->processCSVFile($baseUrl . $file, $file, $importLimit);
            } catch (\Exception $e) {
                echo "<p>❌ Error procesando $file: " . $e->getMessage() . "</p>";
            }
        }
    }

    /**
     * Procesa un archivo CSV individual
     */
    private function processCSVFile($url, $filename, $limit)
    {
        // Leer CSV
        $csvData = file_get_contents($url);
        if ($csvData === false) {
            throw new \Exception("No se pudo leer el archivo CSV");
        }

        $lines = explode("\n", $csvData);
        $headers = str_getcsv(array_shift($lines));

        $count = 0;
        foreach ($lines as $line) {
            if (empty($line) || $count >= $limit) {
                continue;
            }

            $row = str_getcsv($line);
            if (count($row) < 2) {
                continue;
            }

            try {
                $this->processCSVRow($row, $headers, $filename);
                $count++;
            } catch (\Exception $e) {
                echo "<p>   ❌ Error en fila: " . $e->getMessage() . "</p>";
            }
        }
    }

    /**
     * Procesa una fila individual del CSV
     */
    private function processCSVRow($row, $headers, $filename)
    {
        // Construir descripción
        $nombre = $row[0];
        $detalles = "Componente: $filename. ";

        foreach ($headers as $index => $header) {
            if (isset($row[$index])) {
                $detalles .= "$header: {$row[$index]}. ";
            }
        }

        // Generar embedding
        $embedding = $this->ollamaService->generateEmbedding($detalles);

        // Guardar en base de datos
        $this->componentModel->insert($filename, $nombre, $detalles, $embedding);

        echo "<p>   ✅ Importado: $nombre</p>";
    }
}
