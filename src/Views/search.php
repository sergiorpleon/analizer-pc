<?php
$title = 'Buscar Componentes - Analizador PC';
ob_start();
?>

<div class="max-w-4xl mx-auto space-y-8">
    <!-- Search Header -->
    <div class="text-center space-y-2">
        <h1 class="text-3xl font-bold text-gray-900">Buscador Inteligente</h1>
        <p class="text-gray-500">Describe lo que necesitas y nuestra IA encontrará las mejores opciones.</p>
    </div>

    <!-- Search Form -->
    <form method="GET" action="/search" class="relative">
        <div
            class="flex items-center bg-white border border-gray-300 rounded-full shadow-sm hover:shadow-md focus-within:shadow-md transition-shadow duration-200 px-6 py-3">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="q" placeholder="Ej: Procesador potente para edición de video..."
                value="<?php echo htmlspecialchars($query); ?>"
                class="flex-grow ml-4 bg-transparent border-none focus:ring-0 text-gray-900 text-lg outline-none"
                autofocus>
            <button type="submit" class="hidden sm:block btn-google rounded-full px-6">
                Buscar
            </button>
        </div>
        <div class="sm:hidden mt-4 flex justify-center">
            <button type="submit" class="btn-google w-full justify-center">
                Buscar
            </button>
        </div>
    </form>

    <!-- Error/Status Messages -->
    <?php if (isset($noData) && $noData): ?>
        <div class="card bg-amber-50 border-amber-200 p-8">
            <div class="flex items-start gap-4">
                <div class="p-2 bg-amber-100 rounded-lg">
                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-amber-900">Base de datos vacía</h3>
                    <p class="text-amber-800">
                        Para poder realizar búsquedas, primero debes importar el catálogo de componentes.
                    </p>
                    <div class="bg-white/50 rounded-lg p-6 space-y-4">
                        <h4 class="font-semibold text-gray-900">Pasos recomendados:</h4>
                        <ul class="space-y-3 text-gray-700">
                            <?php if (!isset($_SESSION['user']) || !$_SESSION['user']['logged_in']): ?>
                                <li class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 flex items-center justify-center bg-google-blue text-white rounded-full text-xs">1</span>
                                    <a href="/login" class="text-google-blue hover:underline font-medium">Inicia sesión como
                                        administrador</a>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 flex items-center justify-center bg-google-blue text-white rounded-full text-xs">2</span>
                                    <a href="/data" class="text-google-blue hover:underline font-medium">Ve a la sección de
                                        Importar Datos</a>
                                </li>
                            <?php else: ?>
                                <li class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 flex items-center justify-center bg-google-blue text-white rounded-full text-xs">1</span>
                                    <a href="/data" class="text-google-blue hover:underline font-medium">Importar catálogo
                                        ahora</a>
                                </li>
                            <?php endif; ?>
                            <li class="flex items-center gap-2">
                                <span
                                    class="w-5 h-5 flex items-center justify-center bg-google-blue text-white rounded-full text-xs"><?php echo (!isset($_SESSION['user']) || !$_SESSION['user']['logged_in']) ? '3' : '2'; ?></span>
                                <span>Espera unos minutos a que la IA procese los datos.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif (isset($error)): ?>
        <div class="card bg-red-50 border-red-200 p-4 text-red-700 flex items-center gap-3">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <!-- Results -->
    <?php if (!empty($results)): ?>
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200 pb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    Resultados para "<span class="text-google-blue"><?php echo htmlspecialchars($query); ?></span>"
                </h3>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider mr-2">Exportar:</span>
                    <div class="flex gap-1">
                        <a href="/inform?format=json" class="p-2 hover:bg-gray-100 rounded-md transition-colors"
                            title="JSON">
                            <span class="text-xs font-bold text-amber-600">JSON</span>
                        </a>
                        <a href="/inform?format=xml" class="p-2 hover:bg-gray-100 rounded-md transition-colors" title="XML">
                            <span class="text-xs font-bold text-orange-600">XML</span>
                        </a>
                        <a href="/inform?format=csv" class="p-2 hover:bg-gray-100 rounded-md transition-colors" title="CSV">
                            <span class="text-xs font-bold text-green-600">CSV</span>
                        </a>
                        <a href="/inform?format=pdf" class="p-2 hover:bg-gray-100 rounded-md transition-colors" title="PDF">
                            <span class="text-xs font-bold text-red-600">PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <?php foreach ($results as $result): ?>
                    <div class="card hover:border-google-blue transition-colors duration-200">
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-start gap-4">
                                <div class="space-y-1">
                                    <h4 class="text-xl font-bold text-gray-900 hover:text-google-blue cursor-pointer">
                                        <?php echo htmlspecialchars($result['nombre']); ?>
                                    </h4>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-google-blue">
                                            <?php echo htmlspecialchars($result['categoria']); ?>
                                        </span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs font-medium text-google-green">
                                            <?php echo round($result['similarity'] * 100, 1); ?>% de coincidencia
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <p class="text-gray-600 leading-relaxed">
                                <?php echo htmlspecialchars($result['detalles']); ?>
                            </p>

                            <div class="pt-4 border-t border-gray-100 flex justify-end">
                                <button class="text-sm font-medium text-google-blue hover:underline">Ver detalles completos
                                    →</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php elseif (!empty($query) && !$noData): ?>
        <div class="text-center py-12 space-y-4">
            <div class="inline-flex p-4 bg-gray-100 rounded-full text-gray-400">
                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-medium text-gray-900">No encontramos coincidencias</h3>
            <p class="text-gray-500 max-w-sm mx-auto">
                Prueba describiendo el componente de otra forma o verifica si el catálogo está actualizado.
            </p>
            <a href="/search" class="text-google-blue hover:underline font-medium">Limpiar búsqueda</a>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';
?>