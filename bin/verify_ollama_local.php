<?php
/**
 * Script simplificado para verificar Ollama con datos locales
 * Sin ejecutar el script completo, solo verificar configuración
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Establecer variables de entorno temporalmente
$_ENV['EMBEDDING_PROVIDER'] = 'ollama';
$_ENV['DATA_SOURCE'] = 'local';
putenv('EMBEDDING_PROVIDER=ollama');
putenv('DATA_SOURCE=local');

use App\Services\Ai\EmbeddingFactory;
use App\Services\Data\DataSourceFactory;

$config = require __DIR__ . '/../config/config.php';

echo "=== VERIFICACIÓN DE CONFIGURACIÓN ===\n\n";

echo "1. PROVEEDOR DE EMBEDDINGS:\n";
echo "   Variable de entorno: " . $_ENV['EMBEDDING_PROVIDER'] . "\n";
$embeddingService = EmbeddingFactory::create();
echo "   Servicio creado: " . get_class($embeddingService) . "\n";
echo "   Dimensiones esperadas: " . $config['ai']['vector_dimension'] . "\n";

try {
    $testEmbed = $embeddingService->getEmbedding("test");
    echo "   Dimensiones reales: " . count($testEmbed) . "\n";
    echo "   ✅ Ollama funcionando correctamente\n\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
}

echo "2. ORIGEN DE DATOS:\n";
echo "   Variable de entorno: " . $_ENV['DATA_SOURCE'] . "\n";
$dataSource = DataSourceFactory::create();
echo "   Origen creado: " . get_class($dataSource) . "\n";
echo "   Ruta local: " . $config['data']['local_path'] . "\n";

try {
    $documents = $dataSource->getDocuments();
    echo "   Archivos encontrados: " . count($documents) . "\n";
    echo "   ✅ Datos locales accesibles\n\n";

    echo "   Primeros 5 archivos:\n";
    $count = 0;
    foreach (array_keys($documents) as $filename) {
        echo "      - $filename\n";
        if (++$count >= 5)
            break;
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== VERIFICACIÓN COMPLETA ===\n";
