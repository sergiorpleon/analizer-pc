<?php
$title = '500 - Error del Servidor';
ob_start();
?>

<div class="text-center py-20 space-y-6">
    <div class="inline-flex p-6 bg-amber-50 rounded-full text-google-yellow">
        <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
    </div>
    <h1 class="text-6xl font-extrabold text-gray-900">500</h1>
    <h2 class="text-2xl font-bold text-gray-700">Error interno del servidor</h2>
    <p class="text-gray-500 max-w-md mx-auto">
        Algo salió mal en nuestro lado. Por favor, inténtalo de nuevo más tarde.
    </p>
    <?php if (isset($e)): ?>
        <div class="max-w-2xl mx-auto mt-8 p-4 bg-gray-100 rounded-lg text-left overflow-x-auto">
            <p class="text-xs font-mono text-gray-600"><?php echo htmlspecialchars($e->getMessage()); ?></p>
        </div>
    <?php endif; ?>
    <div class="pt-6">
        <a href="/" class="btn-google px-8 py-3">
            Volver al Inicio
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>