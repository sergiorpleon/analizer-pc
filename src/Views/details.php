<?php
$title = htmlspecialchars($movie['nombre']) . ' - Detalles';
ob_start();
$info = $movie['info_formateada'];
?>

<div class="max-w-5xl mx-auto space-y-8">
    <!-- Breadcrumbs -->
    <nav class="flex text-sm text-gray-500" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li><a href="/" class="hover:text-google-blue">Inicio</a></li>
            <li>
                <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li><a href="/search" class="hover:text-google-blue">Buscador</a></li>
            <li>
                <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li class="text-gray-900 font-medium" aria-current="page">
                <?php echo htmlspecialchars($movie['nombre']); ?>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Poster & Quick Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card overflow-hidden">
                <?php if (isset($info['Poster_Link'])): ?>
                    <img src="<?php echo htmlspecialchars($info['Poster_Link']); ?>"
                        alt="<?php echo htmlspecialchars($movie['nombre']); ?>"
                        class="w-full h-auto object-cover shadow-inner">
                <?php else: ?>
                    <div class="aspect-[2/3] bg-gray-100 flex items-center justify-center">
                        <svg class="h-20 w-20 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                        </svg>
                    </div>
                <?php endif; ?>

                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-bold text-google-blue">
                            <?php echo htmlspecialchars($info['IMDB_Rating'] ?? 'N/A'); ?>
                        </span>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">IMDB Rating</p>
                            <p class="text-xs text-gray-500">
                                <?php echo htmlspecialchars($info['No_of_Votes'] ?? '0'); ?> votos
                            </p>
                        </div>
                    </div>

                    <?php if (isset($info['Meta_score']) && $info['Meta_score'] !== 'null'): ?>
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-1 bg-green-600 text-white text-sm font-bold rounded">
                                <?php echo htmlspecialchars($info['Meta_score']); ?>
                            </span>
                            <span class="text-sm text-gray-600 font-medium">Metascore</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card p-6 space-y-4">
                <h3 class="font-bold text-gray-900 border-b pb-2">Ficha Técnica</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-400">Año</dt>
                        <dd class="text-gray-900 font-medium">
                            <?php echo htmlspecialchars($info['Released_Year'] ?? 'N/A'); ?>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Duración</dt>
                        <dd class="text-gray-900 font-medium">
                            <?php echo htmlspecialchars($info['Runtime'] ?? 'N/A'); ?>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Certificado</dt>
                        <dd class="text-gray-900 font-medium">
                            <?php echo htmlspecialchars($info['Certificate'] ?? 'N/A'); ?>
                        </dd>
                    </div>
                    <?php if (isset($info['Gross']) && $info['Gross'] !== 'null'): ?>
                        <div>
                            <dt class="text-gray-400">Recaudación</dt>
                            <dd class="text-gray-900 font-medium">$
                                <?php echo htmlspecialchars($info['Gross']); ?>
                            </dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <!-- Main Content: Overview & Cast -->
        <div class="lg:col-span-2 space-y-8">
            <div class="space-y-4">
                <h1 class="text-4xl font-extrabold text-gray-900">
                    <?php echo htmlspecialchars($movie['nombre']); ?>
                </h1>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $genres = explode(',', $movie['categoria']);
                    foreach ($genres as $genre):
                        ?>
                        <span
                            class="px-3 py-1 bg-blue-50 text-google-blue rounded-full text-sm font-medium border border-blue-100">
                            <?php echo htmlspecialchars(trim($genre)); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card p-8 space-y-6">
                <section class="space-y-3">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-google-yellow" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.993 7.993 0 002 12a7.998 7.998 0 007 7.938V4.804z" />
                            <path d="M11 4.804v15.134A7.997 7.997 0 0018 12a7.993 7.993 0 00-7-7.196z" />
                        </svg>
                        Sinopsis
                    </h3>
                    <p class="text-gray-600 text-lg leading-relaxed italic">
                        "
                        <?php echo htmlspecialchars($info['Overview'] ?? 'No hay descripción disponible.'); ?>"
                    </p>
                </section>

                <hr class="border-gray-100">

                <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <h4 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-google-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Director
                        </h4>
                        <p class="text-gray-700 font-medium text-lg">
                            <?php echo htmlspecialchars($info['Director'] ?? 'N/A'); ?>
                        </p>
                    </div>

                    <div class="space-y-4">
                        <h4 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-google-green" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Reparto Principal
                        </h4>
                        <ul class="space-y-2 text-gray-700">
                            <?php for ($i = 1; $i <= 4; $i++):
                                if (isset($info["Star$i"])): ?>
                                    <li class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 bg-google-green rounded-full"></span>
                                        <?php echo htmlspecialchars($info["Star$i"]); ?>
                                    </li>
                                <?php endif; endfor; ?>
                        </ul>
                    </div>
                </section>
            </div>

            <div class="flex justify-between items-center">
                <a href="/search" class="btn-outline flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al buscador
                </a>
                <button onclick="window.print()" class="text-gray-400 hover:text-google-blue transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';
?>