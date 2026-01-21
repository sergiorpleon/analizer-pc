<?php
/**
 * Script de prueba para el servicio de Gemini
 * Ejecutar desde la raíz del proyecto: php bin/test_gemini.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

use App\Services\Ai\GeminiService;
use App\Services\Ai\EmbeddingFactory;

echo "🔍 Probando servicio de Gemini...\n\n";

// Verificar variables de entorno
echo "📋 Variables de entorno:\n";
echo "   EMBEDDING_PROVIDER: " . ($_ENV['EMBEDDING_PROVIDER'] ?? 'no configurado') . "\n";
echo "   GEMINI_API_KEY: " . (isset($_ENV['GEMINI_API_KEY']) && !empty($_ENV['GEMINI_API_KEY']) ? 'configurado (***' . substr($_ENV['GEMINI_API_KEY'], -4) . ')' : 'NO configurado') . "\n\n";

// Probar con la factoría
try {
    echo "🏭 Creando servicio con EmbeddingFactory...\n";
    $service = EmbeddingFactory::create();
    echo "✅ Servicio creado: " . get_class($service) . "\n\n";

    // Probar conexión
    echo "🔌 Probando conexión...\n";
    if ($service->testConnection()) {
        echo "✅ Conexión exitosa\n\n";
    } else {
        echo "❌ Fallo en la conexión\n\n";
    }

    // Probar generación de embedding
    echo "🧪 Probando generación de embedding...\n";
    $testText = "Procesador Intel Core i7-12700K, 12 núcleos, 3.6 GHz";
    echo "   Texto de prueba: $testText\n";

    $embedding = $service->getEmbedding($testText);

    echo "✅ Embedding generado exitosamente\n";
    echo "   Dimensiones: " . count($embedding) . "\n";
    echo "   Primeros 5 valores: [" . implode(', ', array_slice($embedding, 0, 5)) . "...]\n";
    echo "   Últimos 5 valores: [..." . implode(', ', array_slice($embedding, -5)) . "]\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n";

    if ($e->getPrevious()) {
        echo "\n   Error anterior: " . $e->getPrevious()->getMessage() . "\n";
    }
}

echo "\n=== Prueba Completa ===\n";
