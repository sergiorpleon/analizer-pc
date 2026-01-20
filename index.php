<?php
require_once __DIR__ . '/vendor/autoload.php';

// 1. Probar conexión a Postgres + PGVector
try {
    $pdo = new PDO("pgsql:host=db;dbname=ai_db", "user", "password");
    // Activar extensión vector
    $pdo->exec("CREATE EXTENSION IF NOT EXISTS vector;");
    echo "✅ Conexión a Postgres y PGVector exitosa.<br>";
} catch (Exception $e) {
    echo "❌ Error en BD: " . $e->getMessage() . "<br>";
}

// 2. Probar conexión a Ollama (usando Guzzle)
$client = new \GuzzleHttp\Client();
try {
    $response = $client->post('http://ollama:11434/api/generate', [
        'json' => [
            'model' => 'llama3',
            'prompt' => 'Dime hola en una frase corta',
            'stream' => false
        ]
    ]);
    $data = json_decode($response->getBody(), true);
    echo "✅ Ollama responde: " . $data['response'];
} catch (Exception $e) {
    echo "❌ Error en Ollama: " . $e->getMessage();
}