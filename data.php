<?php
// data.php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

// Seguridad básica: Solo permite ejecutar si pasas un token por URL
// Ejemplo: localhost:8000/data.php?key=12345
if (!isset($_GET['key']) || $_GET['key'] !== '12345') {
    die("Acceso no autorizado. Debes proporcionar la clave correcta.");
}

set_time_limit(0); // Evita que el script se corte por tiempo si hay muchos datos
$client = new Client();
$pdo = new PDO("pgsql:host=db;dbname=ai_db", "user", "password");

echo "<h2>Iniciando poblamiento de Base de Datos...</h2>";

// 1. Preparar la tabla (Asegúrate de que el tamaño sea 4096 para Llama3)
$pdo->exec("CREATE EXTENSION IF NOT EXISTS vector;");
$pdo->exec("DROP TABLE IF EXISTS componentes_pc;"); // Reiniciamos para el ejemplo
$pdo->exec("CREATE TABLE componentes_pc (
    id SERIAL PRIMARY KEY,
    categoria TEXT,
    nombre TEXT,
    detalles TEXT,
    embedding VECTOR(4096)
);");

// 2. Lista de archivos a procesar
$baseUrl = "https://raw.githubusercontent.com/docyx/pc-part-dataset/main/data/csv/";
$archivos = [
    'cpu.csv',
    'video-card.csv',
    'motherboard.csv',
    'memory.csv',
    'monitor.csv'
    // Puedes agregar todos los que quieras de tu lista aquí
];

echo "<h2>Iniciando importación de componentes...</h2>";

foreach ($archivos as $archivo) {
    echo "Procesando $archivo...<br>";

    // Leer el CSV desde GitHub
    $csvData = file_get_contents($baseUrl . $archivo);
    $lines = explode("\n", $csvData);
    $headers = str_getcsv(array_shift($lines)); // Obtener cabeceras

    // Procesar las primeras 10 filas de cada archivo para no saturar la API de golpe
    $count = 0;
    foreach ($lines as $line) {
        if (empty($line) || $count >= 10)
            continue;

        $row = str_getcsv($line);
        if (count($row) < 2)
            continue;

        // Combinamos las columnas para crear una "descripción rica" para la IA
        $nombre = $row[0]; // Usualmente la primera columna es el nombre
        $detalles = "Componente: $archivo. ";
        foreach ($headers as $index => $header) {
            if (isset($row[$index])) {
                $detalles .= "$header: {$row[$index]}. ";
            }
        }

        try {
            // 3. Generar el Embedding con Ollama
            $response = $client->post('http://ollama:11434/api/embeddings', [
                'json' => [
                    'model' => 'llama3',
                    'prompt' => $detalles
                ]
            ]);
            $resData = json_decode($response->getBody(), true);
            $vector = '[' . implode(',', $resData['embedding']) . ']';

            // 4. Guardar en Postgres
            $stmt = $pdo->prepare("INSERT INTO componentes_pc (categoria, nombre, detalles, embedding) VALUES (?, ?, ?, ?)");
            $stmt->execute([$archivo, $nombre, $detalles, $vector]);

            echo "   ✅ Importado: $nombre <br>";
            $count++;
        } catch (Exception $e) {
            echo "   ❌ Error en $nombre: " . $e->getMessage() . "<br>";
        }
    }
}

echo "<h3>¡Base de datos cargada con éxito!</h3>";