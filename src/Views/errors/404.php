<?php
$title = '404 - Página no encontrada';
ob_start();
?>

<div class="text-center py-20 space-y-6">
    <div class="inline-flex p-6 bg-red-50 rounded-full text-google-red">
        <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <h1 class="text-6xl font-extrabold text-gray-900">404</h1>
    <h2 class="text-2xl font-bold text-gray-700">Página no encontrada</h2>
    <p class="text-gray-500 max-w-md mx-auto">
        Lo sentimos, la página que estás buscando no existe o ha sido movida.
    </p>
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