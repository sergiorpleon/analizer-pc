<?php
$title = 'Inicio - Analizador de Películas';
ob_start();
?>

<div class="space-y-12">
    <!-- Hero Section -->
    <div class="text-center space-y-4">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
            Búsqueda inteligente de <span class="text-google-blue">Películas</span>
        </h1>
        <p class="max-w-2xl mx-auto text-xl text-gray-500">
            Utiliza inteligencia artificial para encontrar la película perfecta para tu próxima sesión de cine.
        </p>
        <div class="flex justify-center gap-4 pt-4">
            <a href="/search" class="btn-google text-lg px-8 py-3">
                Empezar a buscar
            </a>
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="/login" class="btn-outline text-lg px-8 py-3">
                    Acceso Admin
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Status Section -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($messages as $message): ?>
            <div class="card p-6 flex items-start gap-4">
                <div class="flex-shrink-0">
                    <?php if ($message['type'] === 'success'): ?>
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="h-6 w-6 text-google-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    <?php else: ?>
                        <div class="p-2 bg-red-100 rounded-lg">
                            <svg class="h-6 w-6 text-google-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">
                        <?php
                        if (strpos($message['text'], 'Base de datos') !== false)
                            echo 'Base de Datos';
                        elseif (strpos($message['text'], 'Ollama') !== false)
                            echo 'Servicio IA (Ollama)';
                        elseif (strpos($message['text'], 'Gemini') !== false)
                            echo 'Servicio IA (Gemini)';
                        else
                            echo 'Estado del Sistema';
                        ?>
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        <?php echo htmlspecialchars($message['text']); ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Features Section -->
    <div class="bg-white rounded-2xl border border-gray-200 p-8 md:p-12 shadow-sm">
        <div class="max-w-3xl">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">¿Cómo funciona?</h2>
            <div class="space-y-8">
                <div class="flex gap-4">
                    <div
                        class="flex-shrink-0 w-8 h-8 rounded-full bg-google-blue text-white flex items-center justify-center font-bold">
                        1</div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">Búsqueda Semántica</h4>
                        <p class="text-gray-600">No busques solo por palabras clave. Describe lo que te apetece ver,
                            como
                            "una película de ciencia ficción con viajes en el tiempo" y nuestra IA entenderá el
                            contexto.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div
                        class="flex-shrink-0 w-8 h-8 rounded-full bg-google-red text-white flex items-center justify-center font-bold">
                        2</div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">Embeddings Vectoriales</h4>
                        <p class="text-gray-600">Convertimos tus descripciones y los datos de las películas en
                            vectores matemáticos para encontrar las coincidencias más precisas.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div
                        class="flex-shrink-0 w-8 h-8 rounded-full bg-google-yellow text-white flex items-center justify-center font-bold">
                        3</div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">Resultados Instantáneos</h4>
                        <p class="text-gray-600">Obtén una lista comparativa con porcentajes de similitud y detalles
                            completos de cada película.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';
?>