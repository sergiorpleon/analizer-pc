<?php
/**
 * Script para cambiar temporalmente a Ollama con datos locales
 * Ejecutar: docker exec php-app php bin/switch_to_ollama_local.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

echo "🔄 Cambiando configuración a Ollama con datos locales...\n\n";

// Establecer variables de entorno temporalmente
$_ENV['EMBEDDING_PROVIDER'] = 'ollama';
$_ENV['DATA_SOURCE'] = 'local';
putenv('EMBEDDING_PROVIDER=ollama');
putenv('DATA_SOURCE=local');

echo "✅ Variables de entorno configuradas:\n";
echo "   EMBEDDING_PROVIDER: " . $_ENV['EMBEDDING_PROVIDER'] . "\n";
echo "   DATA_SOURCE: " . $_ENV['DATA_SOURCE'] . "\n\n";

// Cargar configuración
$config = require __DIR__ . '/../config/config.php';

echo "📋 Configuración resultante:\n";
echo "   Proveedor de embeddings: " . $config['ai']['provider'] . "\n";
echo "   Dimensiones del vector: " . $config['ai']['vector_dimension'] . "\n";
echo "   Origen de datos: " . ($_ENV['DATA_SOURCE'] ?? 'no configurado') . "\n";
echo "   Ruta local: " . $config['data']['local_path'] . "\n\n";

// Verificar que Ollama esté disponible
use App\Services\Ai\EmbeddingFactory;

try {
    echo "🔌 Verificando conexión con Ollama...\n";
    $service = EmbeddingFactory::create();

    if ($service->testConnection()) {
        echo "✅ Ollama está disponible y funcionando\n\n";

        // Ejecutar el script de prueba
        echo "🚀 Ejecutando prueba completa...\n";
        echo str_repeat("=", 60) . "\n\n";

        // Incluir el script de prueba
        require __DIR__ . '/test_ollama_local.php';

    } else {
        echo "❌ Ollama no está disponible\n";
        echo "\n⚠️  Para iniciar Ollama, ejecuta:\n";
        echo "   docker-compose --profile local-ai up -d\n\n";
        echo "   Luego espera unos segundos y ejecuta este script nuevamente.\n";
    }

} catch (Exception $e) {
    echo "❌ Error al conectar con Ollama: " . $e->getMessage() . "\n";
    echo "\n⚠️  Asegúrate de que Ollama esté corriendo:\n";
    echo "   docker-compose --profile local-ai up -d\n";
}
