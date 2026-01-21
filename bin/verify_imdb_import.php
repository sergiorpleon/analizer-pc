<?php
/**
 * Script para verificar la importación con el nuevo formato IMDB
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
use App\Services\Data\FileLoader;

echo "🔍 Verificando importación con formato IMDB...\n\n";

try {
    $db = Database::getInstance();
    $db->initializeTable();
    echo "✅ Tabla inicializada\n";

    $importer = new DataImporter(
        new Component(),
        EmbeddingFactory::create(),
        new FileLoader()
    );

    // Formato IMDB: Poster_Link,Series_Title,Released_Year,Certificate,Runtime,Genre,...
    $testCsv = "Poster_Link,Series_Title,Released_Year,Certificate,Runtime,Genre,IMDB_Rating,Overview\n";
    $testCsv .= "http://link1,The Shawshank Redemption,1994,A,142 min,Drama,9.3,Overview 1\n";
    $testCsv .= "http://link2,The Godfather,1972,A,175 min,\"Crime, Drama\",9.2,Overview 2\n";

    echo "📥 Importando datos de prueba...\n";
    $importer->importFromContent($testCsv, 'imdb_top_1000.csv', 2);
    echo "✅ Importación finalizada\n\n";

    echo "🔍 Verificando resultados en BD...\n";
    $pdo = $db->getPdo();
    $stmt = $pdo->query("SELECT categoria, nombre, detalles FROM componentes_pc LIMIT 5");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        foreach ($results as $row) {
            echo "🎬 Película: {$row['nombre']}\n";
            echo "🏷️  Categoría (Género): {$row['categoria']}\n";
            echo "📝 Detalles: " . substr($row['detalles'], 0, 100) . "...\n\n";
        }
    } else {
        echo "❌ No se encontraron registros.\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
