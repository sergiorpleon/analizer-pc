<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Analizador de Componentes PC'; ?></title>
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        google: {
                            blue: '#1a73e8',
                            red: '#ea4335',
                            yellow: '#fbbc05',
                            green: '#34a853',
                            gray: '#5f6368'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer base {
            body { @apply text-gray-900 antialiased; }
        }
        @layer components {
            .btn-google {
                @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-google-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-google-blue transition-colors duration-200;
            }
            .btn-outline {
                @apply inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-google-blue transition-colors duration-200;
            }
            .card {
                @apply bg-white overflow-hidden shadow-sm border border-gray-200 rounded-lg;
            }
        }
    </style>
</head>

<body class="h-full flex flex-col">
    <!-- Header / Nav -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="flex items-center gap-2">
                            <span class="text-2xl font-bold tracking-tight">
                                <span class="text-google-blue">A</span><span class="text-google-red">n</span><span
                                    class="text-google-yellow">a</span><span class="text-google-blue">l</span><span
                                    class="text-google-green">i</span><span class="text-google-red">z</span><span
                                    class="text-google-blue">e</span><span class="text-google-green">r</span>
                            </span>
                            <span class="hidden sm:block text-gray-500 font-medium ml-1">PC</span>
                        </a>
                    </div>
                    <nav class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        <a href="/"
                            class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo $_SERVER['REQUEST_URI'] === '/' ? 'border-google-blue text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'; ?> text-sm font-medium">
                            Inicio
                        </a>
                        <a href="/search"
                            class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo strpos($_SERVER['REQUEST_URI'], '/search') === 0 ? 'border-google-blue text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'; ?> text-sm font-medium">
                            Buscador
                        </a>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['logged_in'] === true): ?>
                            <a href="/data"
                                class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo strpos($_SERVER['REQUEST_URI'], '/data') === 0 ? 'border-google-blue text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'; ?> text-sm font-medium">
                                Importar
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
                <div class="flex items-center">
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['logged_in'] === true): ?>
                        <div class="flex items-center gap-4">
                            <span class="hidden md:block text-sm text-gray-600">
                                Hola, <span
                                    class="font-semibold text-gray-900"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
                            </span>
                            <a href="/logout" class="btn-outline border-google-red text-google-red hover:bg-red-50">
                                Salir
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="btn-google">
                            Iniciar Sesión
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <?php echo $content; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-xl font-bold tracking-tight">
                        <span class="text-google-blue">A</span><span class="text-google-red">n</span><span
                            class="text-google-yellow">a</span><span class="text-google-blue">l</span><span
                            class="text-google-green">i</span><span class="text-google-red">z</span><span
                            class="text-google-blue">e</span><span class="text-google-green">r</span>
                    </span>
                    <span class="text-gray-400 text-sm">© <?php echo date('Y'); ?></span>
                </div>
                <div class="flex space-x-6 text-sm text-gray-500">
                    <span>Privacidad</span>
                    <span>Términos</span>
                    <span>Ayuda</span>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>