<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Services\Data\DataImporter;
use App\Models\Component;
use App\Services\Ai\EmbeddingFactory;
use App\Services\Data\FileLoader;
use App\Models\Database;

$db = Database::getInstance();
$db->initializeTable();

$importer = new DataImporter(new Component(), EmbeddingFactory::create(), new FileLoader());

// CSV simulado con columnas suficientes (IMDB format)
// Col 1: Title, Col 5: Genre
$csv = "C0,Title,C2,C3,C4,Genre,C6\n";
$csv .= "0,Movie1,2,3,4,Action,6\n";
$csv .= "0,Movie2,2,3,4,Comedy,6\n";
$csv .= "0,Movie3,2,3,4,Drama,6\n"; // Última línea con \n
$csv .= ""; // Línea vacía final explicita (explode la creará si hay \n antes)

echo "Importando CSV de prueba...\n";
$importer->importFromContent($csv, 'test.csv', 10);

$pdo = $db->getPdo();
$stmt = $pdo->query("SELECT nombre FROM componentes_pc");
$rows = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "Registros en BD: " . count($rows) . "\n";
foreach ($rows as $r)
    echo "- $r\n";
