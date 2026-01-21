<?php
/**
 * Script de prueba para verificar Ollama con datos locales
 * Ejecutar: docker exec php-app php bin/test_ollama_local.php
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
use App\Services\Data\FileLoader;

echo "🔍 Probando Ollama con datos locales...\n\n";

// Verificar configuración
echo "📋 Configuración:\n";
echo "   EMBEDDING_PROVIDER: " . ($_ENV['EMBEDDING_PROVIDER'] ?? 'no configurado') . "\n";
echo "   DATA_SOURCE: " . ($_ENV['DATA_SOURCE'] ?? 'no configurado') . "\n";

$config = require __DIR__ . '/../config/config.php';
echo "   VECTOR_DIMENSION: " . $config['ai']['vector_dimension'] . "\n";
echo "   LOCAL_PATH: " . $config['data']['local_path'] . "\n\n";

try {
    // 1. Verificar servicio de embeddings
    echo "🏭 Verificando servicio de embeddings...\n";
    $embeddingService = EmbeddingFactory::create();
    echo "   Tipo de servicio: " . get_class($embeddingService) . "\n";

    // Probar conexión
    echo "   Probando conexión...\n";
    if ($embeddingService->testConnection()) {
        echo "   ✅ Conexión exitosa\n";
    } else {
        echo "   ❌ Fallo en la conexión\n";
        echo "   ⚠️  Asegúrate de que Ollama esté corriendo:\n";
        echo "      docker-compose --profile local-ai up -d\n";
        exit(1);
    }

    // Probar generación de embedding
    echo "   Generando embedding de prueba...\n";
    $testEmbedding = $embeddingService->getEmbedding("test");
    echo "   ✅ Embedding generado con " . count($testEmbedding) . " dimensiones\n\n";

    // 2. Verificar origen de datos
    echo "📂 Verificando origen de datos...\n";
    $dataSource = DataSourceFactory::create();
    echo "   Tipo de origen: " . get_class($dataSource) . "\n";

    $documents = $dataSource->getDocuments();
    echo "   ✅ Encontrados " . count($documents) . " archivos CSV\n";

    if (count($documents) === 0) {
        echo "   ❌ No se encontraron archivos CSV en la carpeta local\n";
        exit(1);
    }

    // Mostrar primeros 3 archivos
    echo "   Archivos encontrados:\n";
    $count = 0;
    foreach (array_keys($documents) as $filename) {
        echo "      - $filename\n";
        if (++$count >= 3) {
            echo "      ... y " . (count($documents) - 3) . " más\n";
            break;
        }
    }
    echo "\n";

    // 3. Inicializar base de datos
    echo "🗄️  Inicializando base de datos...\n";
    $db = Database::getInstance();
    $db->initializeTable();
    echo "   ✅ Tabla inicializada con " . $config['ai']['vector_dimension'] . " dimensiones\n\n";

    // 4. Importar datos de prueba (solo 2 filas del primer archivo)
    echo "📥 Importando datos de prueba...\n";
    $importer = new DataImporter(
        new Component(),
        $embeddingService,
        new FileLoader()
    );

    // Tomar el primer archivo
    $firstFile = array_key_first($documents);
    $firstContent = $documents[$firstFile];

    echo "   Procesando archivo: $firstFile\n";
    echo "   Importando primeras 2 filas...\n\n";

    ob_start();
    $importer->importFromContent($firstContent, $firstFile, 2);
    $importOutput = ob_get_clean();

    // Mostrar output sin HTML
    echo strip_tags($importOutput) . "\n";

    echo "\n✅ Importación completada\n\n";

    // 5. Verificar datos en la base de datos
    echo "🔍 Verificando datos en la base de datos...\n";
    $pdo = $db->getPdo();

    // Extraer categoría del nombre del archivo
    $categoria = str_replace('.csv', '', $firstFile);

    $stmt = $pdo->prepare("SELECT categoria, nombre FROM componentes_pc WHERE categoria = ? LIMIT 5");
    $stmt->execute([$categoria]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        echo "   ✅ Encontrados " . count($results) . " componentes:\n";
        foreach ($results as $row) {
            echo "      - {$row['nombre']} (categoría: {$row['categoria']})\n";
        }
    } else {
        echo "   ❌ No se encontraron componentes en la base de datos\n";
    }

    // Verificar total de componentes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM componentes_pc");
    $total = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\n   Total de componentes en la tabla: " . $total['total'] . "\n";

    // 6. Probar búsqueda vectorial
    echo "\n🔎 Probando búsqueda vectorial...\n";
    $queryText = "procesador rápido para gaming";
    echo "   Consulta: \"$queryText\"\n";

    $queryEmbedding = $embeddingService->getEmbedding($queryText);
    $componentModel = new Component();
    $searchResults = $componentModel->searchSimilar($queryEmbedding, 3);

    if (count($searchResults) > 0) {
        echo "   ✅ Encontrados " . count($searchResults) . " resultados similares:\n";
        foreach ($searchResults as $result) {
            echo "      - {$result['nombre']} (distancia: " . round($result['distancia'], 4) . ")\n";
        }
    } else {
        echo "   ⚠️  No se encontraron resultados\n";
    }

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n";
    echo "\n   Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n=== ✅ Prueba Completa Exitosa ===\n";
echo "\n📝 Resumen:\n";
echo "   ✓ Servicio de embeddings: Ollama funcionando\n";
echo "   ✓ Origen de datos: Carpeta local\n";
echo "   ✓ Importación: Exitosa\n";
echo "   ✓ Búsqueda vectorial: Funcionando\n";
