<?php
$title = 'Inicio - Test de Conexiones';
ob_start();
?>

<h1>🔧 Analizador de Componentes PC</h1>
<h2>Estado de las Conexiones</h2>

<?php foreach ($messages as $message): ?>
    <div class="message <?php echo $message['type']; ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endforeach; ?>

<div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
    <h3>📋 Instrucciones</h3>
    <ul style="line-height: 2; margin-left: 20px;">
        <li><strong>Buscar Componentes:</strong> Usa el buscador para encontrar componentes usando IA</li>
        <li><strong>Importar Datos:</strong> Carga la base de datos con componentes desde GitHub</li>
        <li><strong>Arquitectura:</strong> Este proyecto sigue el patrón MVC (Model-View-Controller)</li>
    </ul>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';
?>