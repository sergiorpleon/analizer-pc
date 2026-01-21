<?php
require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

$config = require __DIR__ . '/../config/config.php';

echo "EMBEDDING_PROVIDER: " . ($_ENV['EMBEDDING_PROVIDER'] ?? 'no set') . PHP_EOL;
echo "VECTOR_DIMENSION from config: " . $config['ai']['vector_dimension'] . PHP_EOL;
