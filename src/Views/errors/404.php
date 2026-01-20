<?php
$title = '404 - Página no encontrada';
ob_start();
?>

<div style="text-align: center; padding: 60px 20px;">
    <h1 style="font-size: 6em; margin: 0; color: #667eea;">404</h1>
    <h2 style="color: #764ba2; margin: 20px 0;">Página no encontrada</h2>

    <p style="font-size: 1.2em; color: #666; margin: 30px 0;">
        Lo sentimos, la ruta <code
            style="background: #f8f9fa; padding: 5px 10px; border-radius: 5px; color: #667eea;"><?php echo htmlspecialchars($uri); ?></code>
        no existe.
    </p>

    <div style="margin: 40px 0;">
        <a href="/"
            style="display: inline-block; padding: 15px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 8px; margin: 10px; transition: all 0.3s ease;">
            🏠 Volver al inicio
        </a>
        <a href="/search"
            style="display: inline-block; padding: 15px 30px; background: #764ba2; color: white; text-decoration: none; border-radius: 8px; margin: 10px; transition: all 0.3s ease;">
            🔍 Ir al buscador
        </a>
    </div>

    <div style="margin-top: 60px; padding: 30px; background: #f8f9fa; border-radius: 10px; text-align: left;">
        <h3 style="color: #667eea; margin-bottom: 15px;">💡 Rutas disponibles:</h3>
        <ul style="list-style: none; padding: 0; line-height: 2.5;">
            <li>
                <strong style="color: #667eea;">GET /</strong> - Página principal con tests de conexión
            </li>
            <li>
                <strong style="color: #667eea;">GET /search</strong> - Buscador de componentes PC
            </li>
            <li>
                <strong style="color: #667eea;">GET /data?key=12345</strong> - Importar datos desde CSV
            </li>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>