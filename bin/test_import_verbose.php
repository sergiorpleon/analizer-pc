<?php
/**
 * Script de prueba para la importación de datos con output detallado
 * Ejecutar desde el contenedor: docker exec php-app php bin/test_import_verbose.php
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

echo "🔍 Probando importación de datos con Gemini...\n\n";

// Verificar configuración
echo "📋 Configuración:\n";
echo "   EMBEDDING_PROVIDER: " . ($_ENV['EMBEDDING_PROVIDER'] ?? 'no configurado') . "\n";
echo "   DATA_SOURCE: " . ($_ENV['DATA_SOURCE'] ?? 'no configurado') . "\n";

$config = require __DIR__ . '/../config/config.php';
echo "   VECTOR_DIMENSION: " . $config['ai']['vector_dimension'] . "\n\n";

try {
    // Inicializar base de datos
    echo "🗄️  Inicializando base de datos...\n";
    $db = Database::getInstance();

    echo "   Eliminando tabla anterior...\n";
    $db->initializeTable();
    echo "✅ Tabla inicializada con " . $config['ai']['vector_dimension'] . " dimensiones\n\n";

    // Crear el importador
    echo "🏭 Creando importador...\n";
    $importer = new DataImporter(
        new Component(),
        EmbeddingFactory::create(),
        new FileLoader()
    );
    echo "✅ Importador creado\n\n";

    // Crear datos de prueba en formato CSV
    echo "📝 Creando datos de prueba...\n";
    $testCsv = "Nombre,Marca,Modelo,Precio\n";
    $testCsv .= "Procesador Intel Core i7-12700K,Intel,Core i7-12700K,400\n";
    $testCsv .= "Tarjeta Gráfica NVIDIA RTX 4080,NVIDIA,GeForce RTX 4080,1200\n";

    echo "✅ Datos de prueba creados\n\n";

    // Importar datos
    echo "📥 Importando datos...\n";
    ob_start();
    $importer->importFromContent($testCsv, 'test_components.csv', 10);
    $importOutput = ob_get_clean();

    // Mostrar output de importación sin HTML
    echo strip_tags($importOutput) . "\n";

    echo "\n✅ Importación completada exitosamente\n\n";

    // Verificar que se guardaron en la base de datos
    echo "🔍 Verificando datos en la base de datos...\n";
    $componentModel = new Component();
    $pdo = $db->getPdo();
    $stmt = $pdo->query("SELECT categoria, nombre FROM componentes_pc WHERE categoria = 'test_components' LIMIT 5");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        echo "✅ Encontrados " . count($results) . " componentes:\n";
        foreach ($results as $row) {
            echo "   - {$row['nombre']} (categoría: {$row['categoria']})\n";
        }
    } else {
        echo "❌ No se encontraron componentes en la base de datos\n";

        // Verificar cuántos hay en total
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM componentes_pc");
        $total = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "   Total de componentes en la tabla: " . $total['total'] . "\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n";
    echo "\n   Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n=== Prueba Completa ===\n";
