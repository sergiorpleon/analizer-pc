<?php
/**
 * Script de importación real con Ollama y datos locales
 * Importa 3 componentes de cada uno de los 5 archivos configurados
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Configurar para usar Ollama y datos locales
$_ENV['EMBEDDING_PROVIDER'] = 'ollama';
$_ENV['DATA_SOURCE'] = 'local';
putenv('EMBEDDING_PROVIDER=ollama');
putenv('DATA_SOURCE=local');

use App\Models\Database;
use App\Models\Component;
use App\Services\Ai\EmbeddingFactory;
use App\Services\Data\DataImporter;
use App\Services\Data\DataSourceFactory;
use App\Services\Data\FileLoader;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  IMPORTACIÓN CON OLLAMA Y DATOS LOCALES                   ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

$config = require __DIR__ . '/../config/config.php';

echo "📋 Configuración:\n";
echo "   Proveedor: Ollama\n";
echo "   Origen: Carpeta local\n";
echo "   Dimensiones: " . $config['ai']['vector_dimension'] . "\n";
echo "   Límite por archivo: 3 componentes\n\n";

try {
    // Inicializar base de datos
    echo "🗄️  Inicializando base de datos...\n";
    $db = Database::getInstance();
    $db->initializeTable();
    echo "✅ Tabla creada con " . $config['ai']['vector_dimension'] . " dimensiones\n\n";

    // Crear servicios
    $embeddingService = EmbeddingFactory::create();
    $dataSource = DataSourceFactory::create();
    $importer = new DataImporter(
        new Component(),
        $embeddingService,
        new FileLoader()
    );

    // Obtener documentos
    $documents = $dataSource->getDocuments();
    echo "📂 Archivos encontrados: " . count($documents) . "\n\n";

    // Importar 3 componentes de cada archivo
    $totalImported = 0;
    foreach ($documents as $filename => $content) {
        echo "📄 Procesando: $filename\n";

        ob_start();
        $importer->importFromContent($content, $filename, 3);
        $output = ob_get_clean();

        // Contar cuántos se importaron exitosamente
        $imported = substr_count($output, '[OK]');
        $totalImported += $imported;

        echo "   ✓ Importados: $imported componentes\n";
    }

    echo "\n" . str_repeat("─", 60) . "\n";
    echo "✅ IMPORTACIÓN COMPLETADA\n";
    echo "   Total importado: $totalImported componentes\n\n";

    // Verificar en la base de datos
    echo "🔍 Verificando base de datos...\n";
    $pdo = $db->getPdo();

    // Contar por categoría
    $stmt = $pdo->query("
        SELECT categoria, COUNT(*) as total 
        FROM componentes_pc 
        GROUP BY categoria 
        ORDER BY categoria
    ");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "   Componentes por categoría:\n";
    foreach ($results as $row) {
        echo "      • {$row['categoria']}: {$row['total']}\n";
    }

    // Total
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM componentes_pc");
    $total = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\n   📊 Total en base de datos: {$total['total']} componentes\n\n";

    // Probar búsqueda
    echo "🔎 Probando búsqueda vectorial...\n";
    $queries = [
        "procesador Intel para gaming",
        "tarjeta gráfica NVIDIA",
        "memoria RAM DDR5"
    ];

    $componentModel = new Component();

    foreach ($queries as $query) {
        echo "\n   Consulta: \"$query\"\n";
        $queryEmbedding = $embeddingService->getEmbedding($query);
        $results = $componentModel->searchSimilar($queryEmbedding, 2);

        foreach ($results as $result) {
            $distance = round($result['distancia'], 4);
            echo "      → {$result['nombre']} (distancia: $distance)\n";
        }
    }

    echo "\n" . str_repeat("═", 60) . "\n";
    echo "✅ PRUEBA COMPLETADA EXITOSAMENTE\n";
    echo str_repeat("═", 60) . "\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
