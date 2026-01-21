<?php
$title = 'Iniciar Sesión - Analizador de Películas';
ob_start();
?>

<div class="max-w-md mx-auto mt-12">
    <div class="card p-8 space-y-8">
        <!-- Google-style Logo -->
        <div class="text-center">
            <h2 class="text-3xl font-bold tracking-tight">
                <span class="text-google-blue">A</span><span class="text-google-red">n</span><span
                    class="text-google-yellow">a</span><span class="text-google-blue">l</span><span
                    class="text-google-green">i</span><span class="text-google-red">z</span><span
                    class="text-google-blue">e</span><span class="text-google-green">r</span>
            </h2>
            <h3 class="mt-2 text-xl font-medium text-gray-900">Iniciar Sesión</h3>
            <p class="mt-1 text-sm text-gray-500">Usa tu cuenta de administrador</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" class="space-y-6">
            <div class="space-y-1">
                <label for="username" class="block text-sm font-medium text-gray-700">Usuario</label>
                <input type="text" id="username" name="username" required autocomplete="username"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-google-blue focus:border-google-blue text-gray-900 outline-none transition-all">
            </div>

            <div class="space-y-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-google-blue focus:border-google-blue text-gray-900 outline-none transition-all">
            </div>

            <div class="flex items-center justify-between">
                <a href="#" class="text-sm font-medium text-google-blue hover:underline">¿Olvidaste tu contraseña?</a>
            </div>

            <div class="pt-4 flex justify-between items-center">
                <a href="/" class="text-sm font-medium text-google-blue hover:underline">Crear cuenta</a>
                <button type="submit" class="btn-google px-8 py-2.5">
                    Siguiente
                </button>
            </div>
        </form>

        <!-- Credentials Hint -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <p class="text-xs text-gray-500 text-center leading-relaxed">
                <span class="font-semibold uppercase tracking-wider block mb-1">Acceso de Prueba</span>
                Usuario: <code
                    class="bg-white px-1.5 py-0.5 rounded border border-gray-200 font-mono text-gray-800">admin</code><br>
                Password: <code
                    class="bg-white px-1.5 py-0.5 rounded border border-gray-200 font-mono text-gray-800">admin123</code>
            </p>
        </div>
    </div>

    <div class="mt-8 flex justify-center space-x-6 text-xs text-gray-500">
        <span>Español (España)</span>
        <div class="flex space-x-4">
            <span>Ayuda</span>
            <span>Privacidad</span>
            <span>Términos</span>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>