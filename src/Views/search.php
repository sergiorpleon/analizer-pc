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

<?php if (isset($noData) && $noData): ?>
    <div
        style="margin: 30px 0; padding: 30px; background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%); border: 2px solid #ffc107; border-radius: 15px; box-shadow: 0 5px 15px rgba(255, 193, 7, 0.2);">
        <h3 style="color: #856404; margin-bottom: 15px;">📊 No hay datos disponibles</h3>
        <p style="color: #856404; font-size: 1.1em; line-height: 1.6; margin-bottom: 20px;">
            La base de datos está vacía. Necesitas <strong>importar datos</strong> primero para poder buscar componentes.
        </p>

        <div style="background: white; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h4 style="color: #667eea; margin-bottom: 15px;">🚀 Pasos para importar datos:</h4>
            <ol style="color: #333; line-height: 2; margin-left: 20px;">
                <?php if (!isset($_SESSION['user']) || !$_SESSION['user']['logged_in']): ?>
                    <li><strong>Inicia sesión:</strong> <a href="/login"
                            style="color: #667eea; text-decoration: none; font-weight: bold;">Ir a Login →</a></li>
                <?php endif; ?>
                <li><strong>Importa datos:</strong> <a href="/data"
                        style="color: #667eea; text-decoration: none; font-weight: bold;">Ir a Importar Datos →</a></li>
                <li><strong>Espera</strong> a que termine la importación (5-10 minutos)</li>
                <li><strong>Vuelve aquí</strong> y realiza tu búsqueda</li>
            </ol>
        </div>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #667eea;">
            <p style="margin: 0; color: #666;">
                <strong>💡 Tip:</strong> La importación descarga datos de componentes PC desde GitHub y genera embeddings
                con IA para búsquedas semánticas.
            </p>
        </div>
    </div>

<?php elseif (isset($error)): ?>
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

<?php elseif (!empty($query) && !isset($noData)): ?>
    <div class="message" style="background: #fff3cd; color: #856404; border: 1px solid #ffeeba;">
        ℹ️ No se encontraron resultados para tu búsqueda.
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';
?>