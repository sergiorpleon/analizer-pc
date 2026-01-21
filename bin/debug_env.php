<?php
require __DIR__ . '/../vendor/autoload.php';

echo "=== ANTES de cargar .env ===\n";
echo "getenv('EMBEDDING_PROVIDER'): " . (getenv('EMBEDDING_PROVIDER') ?: 'not set') . "\n";
echo "getenv('VECTOR_DIMENSION'): " . (getenv('VECTOR_DIMENSION') ?: 'not set') . "\n\n";

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

echo "=== DESPUÉS de cargar .env ===\n";
echo "\$_ENV['EMBEDDING_PROVIDER']: " . ($_ENV['EMBEDDING_PROVIDER'] ?? 'not set') . "\n";
echo "\$_ENV['VECTOR_DIMENSION']: " . ($_ENV['VECTOR_DIMENSION'] ?? 'not set') . "\n";
echo "getenv('EMBEDDING_PROVIDER'): " . (getenv('EMBEDDING_PROVIDER') ?: 'not set') . "\n";
echo "getenv('VECTOR_DIMENSION'): " . (getenv('VECTOR_DIMENSION') ?: 'not set') . "\n\n";

$config = require __DIR__ . '/../config/config.php';

echo "=== CONFIGURACIÓN FINAL ===\n";
echo "config['ai']['provider']: " . $config['ai']['provider'] . "\n";
echo "config['ai']['vector_dimension']: " . $config['ai']['vector_dimension'] . "\n";

echo "\n=== LÓGICA DE DETECCIÓN ===\n";
$manualDimension = $_ENV['VECTOR_DIMENSION'] ?? null;
$provider = $_ENV['EMBEDDING_PROVIDER'] ?? 'ollama';
$autoDimension = $provider === 'gemini' ? 768 : 4096;

echo "Dimensión manual (\$_ENV['VECTOR_DIMENSION']): " . ($manualDimension ?? 'not set') . "\n";
echo "Proveedor: $provider\n";
echo "Dimensión auto-detectada: $autoDimension\n";
echo "Dimensión final (manual ?? auto): " . ($manualDimension ?? $autoDimension) . "\n";
