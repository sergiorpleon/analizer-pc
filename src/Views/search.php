<?php
$title = 'Buscar Componentes';
ob_start();
?>

<h1>🔍 Buscador de Componentes con IA</h1>

<form method="GET" action="/search" style="margin: 30px 0;">
    <div style="display: flex; gap: 10px;">
        <input type="text" name="q" placeholder="Ej: Necesito un procesador para gaming barato..."
            value="<?php echo htmlspecialchars($query); ?>"
            style="flex: 1; padding: 15px; font-size: 1.1em; border: 2px solid #667eea; border-radius: 8px;">
        <button type="submit"
            style="padding: 15px 30px; font-size: 1.1em; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;"
            onmouseover="this.style.background='#764ba2'" onmouseout="this.style.background='#667eea'">
            Buscar
        </button>
    </div>
</form>

<?php if (isset($error)): ?>
    <div class="message error">
        ❌ Error:
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<?php if (!empty($results)): ?>
    <h3>Resultados encontrados:</h3>

    <?php foreach ($results as $result): ?>
        <div style="border: 2px solid #667eea; margin: 15px 0; padding: 20px; border-radius: 10px; background: #f8f9fa;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                <strong style="font-size: 1.3em; color: #667eea;">
                    <?php echo htmlspecialchars($result['nombre']); ?>
                </strong>
                <span style="background: #667eea; color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.9em;">
                    <?php echo htmlspecialchars($result['categoria']); ?>
                </span>
            </div>

            <div style="margin: 10px 0; padding: 10px; background: white; border-radius: 5px;">
                <em style="color: #764ba2;">
                    📊 Similitud:
                    <?php echo $result['similarity']; ?>
                </em>
            </div>

            <p style="line-height: 1.6; color: #333;">
                <?php echo htmlspecialchars($result['detalles']); ?>
            </p>
        </div>
    <?php endforeach; ?>

<?php elseif (!empty($query)): ?>
    <div class="message" style="background: #fff3cd; color: #856404; border: 1px solid #ffeeba;">
        ℹ️ No se encontraron resultados para tu búsqueda.
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';
?>