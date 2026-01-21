<?php
require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

use App\Services\Ai\EmbeddingFactory;

$service = EmbeddingFactory::create();
$embedding = $service->getEmbedding("test");

echo "Dimensiones del embedding: " . count($embedding) . "\n";
echo "Tipo de servicio: " . get_class($service) . "\n";
