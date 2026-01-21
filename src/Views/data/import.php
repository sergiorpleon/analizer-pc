<?php
$title = 'Importar Datos - Analizador PC';
ob_start();
?>

<div class="max-w-4xl mx-auto space-y-8">
    <div class="card p-8">
        <div class="flex items-center gap-4 mb-8">
            <div class="p-3 bg-google-blue rounded-xl text-white">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Importación de Catálogo</h1>
                <p class="text-gray-500">Poblando la base de datos con componentes y generando embeddings.</p>
            </div>
        </div>

        <?php if (isset($showConfirmation) && $showConfirmation): ?>
            <!-- Confirmation View -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-8 space-y-6 text-center">
                <div class="max-w-md mx-auto space-y-4">
                    <h3 class="text-xl font-bold text-gray-900">¿Listo para comenzar?</h3>
                    <p class="text-gray-600">
                        Este proceso descargará los datos de componentes desde GitHub y generará embeddings vectoriales para
                        cada uno.
                        <span class="block mt-2 font-medium text-google-blue">Dependiendo de la cantidad de datos, esto
                            puede tardar unos minutos.</span>
                    </p>

                    <form method="POST" action="/data" class="pt-4">
                        <input type="hidden" name="confirm_import" value="1">
                        <button type="submit"
                            class="btn-google text-lg px-12 py-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                            Comenzar Importación
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- Progress/Log View -->
            <div id="import-log"
                class="bg-gray-900 rounded-lg p-6 font-mono text-sm text-green-400 overflow-y-auto max-h-[500px] space-y-2">
                <div class="text-gray-400 italic">>> Proceso finalizado</div>
                <?php echo $importOutput ?? ''; ?>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="/data" class="btn-outline">
                    Volver a Importar
                </a>
                <a href="/search" class="btn-google">
                    Ir al Buscador
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>