<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

use App\Models\Database;

try {
    $db = Database::getInstance();
    $pdo = $db->getPdo();

    echo "🔍 Verificando duplicados en la base de datos...\n";

    // Contar total
    $stmt = $pdo->query("SELECT COUNT(*) FROM componentes_pc");
    $total = $stmt->fetchColumn();
    echo "Total de registros: $total\n";

    // Buscar nombres duplicados
    $stmt = $pdo->query("SELECT nombre, COUNT(*) as c FROM componentes_pc GROUP BY nombre HAVING COUNT(*) > 1");
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($duplicates) > 0) {
        echo "⚠️  SE ENCONTRARON DUPLICADOS:\n";
        foreach ($duplicates as $dup) {
            echo "   - {$dup['nombre']}: {$dup['c']} veces\n";
        }
    } else {
        echo "✅ No se encontraron duplicados por nombre.\n";
    }

    // Ver los últimos 5 registros
    echo "\n🔍 Últimos 5 registros insertados:\n";
    $stmt = $pdo->query("SELECT id, nombre FROM componentes_pc ORDER BY id DESC LIMIT 5");
    $last = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($last as $row) {
        echo "   [{$row['id']}] {$row['nombre']}\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
