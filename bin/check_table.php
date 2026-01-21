<?php
require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

use App\Models\Database;

$config = require __DIR__ . '/../config/config.php';

echo "Config VECTOR_DIMENSION: " . $config['ai']['vector_dimension'] . "\n";
echo "ENV EMBEDDING_PROVIDER: " . ($_ENV['EMBEDDING_PROVIDER'] ?? 'not set') . "\n";
echo "ENV VECTOR_DIMENSION: " . ($_ENV['VECTOR_DIMENSION'] ?? 'not set') . "\n\n";

echo "Inicializando tabla...\n";
$db = Database::getInstance();
$db->initializeTable();

echo "Verificando estructura de la tabla...\n";
$pdo = $db->getPdo();
$stmt = $pdo->query("SELECT column_name, data_type, udt_name FROM information_schema.columns WHERE table_name = 'componentes_pc' AND column_name = 'embedding'");
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo "Columna embedding encontrada:\n";
    print_r($result);
} else {
    echo "No se encontró la columna embedding\n";
}

// Verificar el tipo vector directamente
$stmt = $pdo->query("SELECT pg_typeof(embedding) as tipo FROM componentes_pc LIMIT 1");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result) {
    echo "\nTipo de embedding: " . $result['tipo'] . "\n";
}
