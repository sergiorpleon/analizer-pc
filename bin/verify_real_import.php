<?php
/**
 * Script para verificar la importación REAL desde la URL
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

use App\Models\Database;
use App\Models\Component;
use App\Services\Ai\EmbeddingFactory;
use App\Services\Data\DataImporter;
use App\Services\Data\DataSourceFactory;

echo "🚀 Iniciando importación REAL desde URL (límite 5 películas)...\n\n";

try {
    $db = Database::getInstance();
    $db->initializeTable();
    echo "✅ Tabla reiniciada.\n";

    $importer = new DataImporter(
        new Component(),
        EmbeddingFactory::create(),
        new \App\Services\Data\FileLoader()
    );

    $dataSource = DataSourceFactory::create();
    $documents = $dataSource->getDocuments();

    foreach ($documents as $filename => $content) {
        echo "📄 Procesando: $filename\n";
        $importer->importFromContent($content, $filename, 5);
    }

    echo "\n🔍 Verificando 5 registros en BD:\n";
    $pdo = $db->getPdo();
    $stmt = $pdo->query("SELECT categoria, nombre, detalles FROM componentes_pc ORDER BY id ASC LIMIT 5");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        echo "------------------------------------------\n";
        echo "🎬 TÍTULO:    {$row['nombre']}\n";
        echo "🏷️  GÉNERO:    {$row['categoria']}\n";
        echo "📝 DETALLES:  " . substr($row['detalles'], 0, 150) . "...\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
