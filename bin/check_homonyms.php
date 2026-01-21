<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Models\Database;

$db = Database::getInstance();
$pdo = $db->getPdo();

echo "🔍 Buscando homónimos...\n";
$stmt = $pdo->query("SELECT nombre, detalles FROM componentes_pc WHERE nombre IN (SELECT nombre FROM componentes_pc GROUP BY nombre HAVING COUNT(*) > 1) ORDER BY nombre");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    echo "🎬 {$row['nombre']}\n";
    // Extraer año de los detalles
    if (preg_match('/Released_Year: (\d+)/', $row['detalles'], $m)) {
        echo "   📅 Año: {$m[1]}\n";
    }
    echo "   📝 " . substr($row['detalles'], 0, 50) . "...\n\n";
}
