<?php
// index.php
require_once __DIR__ . '/vendor/autoload.php';
$pdo = new PDO("pgsql:host=db;dbname=ai_db", "user", "password");
$client = new \GuzzleHttp\Client();

$pregunta = $_GET['q'] ?? ''; // Captura la búsqueda desde la URL
?>

<h1>🔍 Buscador de Componentes con IA</h1>
<form method="GET">
    <input type="text" name="q" placeholder="Ej: Necesito un procesador para gaming barato..." style="width: 300px;"
        value="<?php echo htmlspecialchars($pregunta); ?>">
    <button type="submit">Buscar</button>
</form>

<?php
if ($pregunta) {
    // 1. Convertir la pregunta del usuario en un Vector (Embedding)
    $response = $client->post('http://ollama:11434/api/embeddings', [
        'json' => ['model' => 'llama3', 'prompt' => $pregunta]
    ]);
    $queryVector = json_decode($response->getBody(), true)['embedding'];
    $vectorString = '[' . implode(',', $queryVector) . ']';

    // 2. Buscar en Postgres por similitud matemática (<-> es distancia euclidiana)
    $sql = "SELECT nombre, detalles, categoria, (embedding <-> ?) as distancia 
            FROM componentes_pc 
            ORDER BY distancia ASC 
            LIMIT 5";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$vectorString]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Resultados encontrados:</h3>";
    foreach ($resultados as $res) {
        echo "<div style='border: 1px solid #ccc; margin-bottom: 10px; padding: 10px;'>";
        echo "<strong>{$res['nombre']}</strong> <small>({$res['categoria']})</small><br>";
        echo "<em>Similitud: " . round(1 - $res['distancia'], 4) . "</em><br>";
        echo "<p>{$res['detalles']}</p>";
        echo "</div>";
    }
}
?>