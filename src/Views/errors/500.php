<?php
$title = '500 - Error del servidor';
ob_start();
?>

<div style="text-align: center; padding: 60px 20px;">
    <h1 style="font-size: 6em; margin: 0; color: #f5576c;">500</h1>
    <h2 style="color: #f093fb; margin: 20px 0;">Error del servidor</h2>

    <p style="font-size: 1.2em; color: #666; margin: 30px 0;">
        Ha ocurrido un error inesperado. Por favor, inténtalo de nuevo más tarde.
    </p>

    <?php if (!$isProduction && isset($exception)): ?>
        <div
            style="margin: 40px 0; padding: 30px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 10px; text-align: left;">
            <h3 style="color: #856404; margin-bottom: 15px;">⚠️ Detalles del error (solo en desarrollo):</h3>

            <div
                style="background: white; padding: 20px; border-radius: 8px; margin: 15px 0; font-family: monospace; font-size: 0.9em;">
                <p><strong>Mensaje:</strong>
                    <?php echo htmlspecialchars($exception->getMessage()); ?>
                </p>
                <p><strong>Archivo:</strong>
                    <?php echo htmlspecialchars($exception->getFile()); ?>
                </p>
                <p><strong>Línea:</strong>
                    <?php echo $exception->getLine(); ?>
                </p>
            </div>

            <details style="margin-top: 20px;">
                <summary
                    style="cursor: pointer; color: #856404; font-weight: bold; padding: 10px; background: white; border-radius: 5px;">
                    Ver Stack Trace completo
                </summary>
                <pre
                    style="background: white; padding: 20px; border-radius: 8px; margin-top: 10px; overflow-x: auto; font-size: 0.85em; line-height: 1.5;"><?php echo htmlspecialchars($exception->getTraceAsString()); ?></pre>
            </details>
        </div>
    <?php endif; ?>

    <div style="margin: 40px 0;">
        <a href="/"
            style="display: inline-block; padding: 15px 30px; background: #f5576c; color: white; text-decoration: none; border-radius: 8px; margin: 10px; transition: all 0.3s ease;">
            🏠 Volver al inicio
        </a>
        <a href="javascript:history.back()"
            style="display: inline-block; padding: 15px 30px; background: #f093fb; color: white; text-decoration: none; border-radius: 8px; margin: 10px; transition: all 0.3s ease;">
            ← Página anterior
        </a>
    </div>

    <?php if ($isProduction): ?>
        <div style="margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <p style="color: #666;">
                Si el problema persiste, por favor contacta al administrador del sistema.
            </p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>