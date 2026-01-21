<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Models\Component;
use App\Services\Ai\EmbeddingFactory;

$query = "The 39 Steps";
$embeddingService = EmbeddingFactory::create();
$componentModel = new Component();

echo "🔍 Buscando: '$query'\n";
$vector = $embeddingService->getEmbedding($query);
$results = $componentModel->searchSimilar($vector, 5);

echo "Resultados encontrados: " . count($results) . "\n";
foreach ($results as $i => $r) {
    echo "[$i] ID: {$r['id']} - {$r['nombre']} (Distancia: {$r['distancia']})\n";
}
