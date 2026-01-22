<?php
$title = 'Importar Datos - Analizador de Películas';
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
                <p class="text-gray-500">Poblando la base de datos con películas y generando embeddings.</p>
            </div>
        </div>

        <?php if (isset($showConfirmation) && $showConfirmation): ?>
            <!-- Confirmation View -->
            <div id="confirmation-view" class="bg-blue-50 border border-blue-200 rounded-xl p-8 space-y-6 text-center">
                <div class="max-w-md mx-auto space-y-4">
                    <h3 class="text-xl font-bold text-gray-900">¿Listo para comenzar?</h3>
                    <p class="text-gray-600">
                        Este proceso descargará los datos de las películas y generará embeddings vectoriales para
                        cada una.
                        <span class="block mt-2 font-medium text-google-blue">Dependiendo de la cantidad de datos, esto
                            puede tardar unos minutos.</span>
                    </p>

                    <form id="import-form" method="POST" action="/data" class="pt-4">
                        <input type="hidden" name="confirm_import" value="1">
                        <button type="submit" id="submit-btn"
                            class="btn-google text-lg px-12 py-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 mx-auto">
                            <span>Comenzar Importación</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Loading View (Hidden by default) -->
            <div id="loading-view" class="hidden bg-white border border-gray-200 rounded-xl p-12 space-y-8 text-center">
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <div
                            class="w-16 h-16 border-4 border-google-blue/20 border-t-google-blue rounded-full animate-spin">
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="h-6 w-6 text-google-blue animate-pulse" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Importando películas...</h3>
                    <p class="text-gray-500 max-w-sm">
                        Estamos procesando el catálogo y generando los vectores de búsqueda. Por favor, no cierres esta
                        ventana.
                    </p>
                </div>

                <!-- Progress bar simulation -->
                <div class="max-w-md mx-auto">
                    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-google-blue transition-all duration-300" style="width: 0%;">
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-400 italic">Conectando con el servicio de IA...</p>
                </div>
            </div>

            <style>
                .hidden {
                    display: none;
                }
            </style>

            <script>
                document.getElementById('import-form').addEventListener('submit', function (e) {
                    e.preventDefault();

                    const confirmationView = document.getElementById('confirmation-view');
                    const loadingView = document.getElementById('loading-view');
                    const progressBar = document.querySelector('#loading-view .bg-google-blue');
                    const progressText = document.querySelector('#loading-view p.italic');

                    // Create log container if it doesn't exist
                    let logContainer = document.getElementById('import-log-container');
                    if (!logContainer) {
                        logContainer = document.createElement('div');
                        logContainer.id = 'import-log-container';
                        logContainer.className = 'mt-4 text-left text-xs text-gray-400 font-mono h-32 overflow-y-auto bg-gray-50 p-2 rounded border border-gray-100';
                        loadingView.appendChild(logContainer);
                    }

                    confirmationView.classList.add('hidden');
                    loadingView.classList.remove('hidden');

                    const evtSource = new EventSource("/data/stream");

                    evtSource.onmessage = function (event) {
                        const data = JSON.parse(event.data);

                        // Update progress
                        progressBar.style.width = data.progress + '%';

                        // Update log/status
                        if (data.log) {
                            progressText.textContent = `Progreso: ${data.progress}%`;
                            const logEntry = document.createElement('div');
                            logEntry.innerHTML = data.log;
                            logContainer.appendChild(logEntry);
                            logContainer.scrollTop = logContainer.scrollHeight;
                        }

                        if (data.done) {
                            evtSource.close();
                            progressText.textContent = '¡Importación completada!';
                            progressText.className = 'mt-2 text-sm font-bold text-green-600';

                            // Show completion button
                            if (!document.getElementById('completion-btn')) {
                                const btn = document.createElement('a');
                                btn.id = 'completion-btn';
                                btn.href = '/search';
                                btn.className = 'btn-google mt-4 inline-block';
                                btn.textContent = 'Ir al Buscador';
                                loadingView.appendChild(btn);
                            }
                        }
                    };

                    evtSource.onerror = function () {
                        console.error("EventSource failed.");
                        evtSource.close();
                        progressText.textContent = 'Error en la conexión. Por favor recarga la página.';
                        progressText.className = 'mt-2 text-sm font-bold text-red-600';
                    };
                });
            </script>

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