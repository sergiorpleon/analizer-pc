<?php
/**
 * Script de verificación de la estructura MVC
 * Ejecutar desde la raíz del proyecto: php test_mvc.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

// Verificar archivos de configuración
echo "📋 Verificando archivos de configuración...\n";
$configFile = __DIR__ . '/../config/config.php';
if (file_exists($configFile)) {
    echo "✅ config/config.php existe\n";
} else {
    echo "❌ config/config.php NO existe\n";
}

// Verificar Models
echo "\n📦 Verificando Models...\n";
$models = [
    'src/Models/Database.php',
    'src/Models/Component.php',
    'src/Models/OllamaService.php'
];

foreach ($models as $model) {
    $path = __DIR__ . '/../' . $model;
    if (file_exists($path)) {
        echo "✅ $model existe\n";
    } else {
        echo "❌ $model NO existe\n";
    }
}

// Verificar Controllers
echo "\n🎮 Verificando Controllers...\n";
$controllers = [
    'src/Controllers/HomeController.php',
    'src/Controllers/SearchController.php',
    'src/Controllers/DataController.php'
];

foreach ($controllers as $controller) {
    $path = __DIR__ . '/../' . $controller;
    if (file_exists($path)) {
        echo "✅ $controller existe\n";
    } else {
        echo "❌ $controller NO existe\n";
    }
}

// Verificar Views
echo "\n🎨 Verificando Views...\n";
$views = [
    'src/Views/layouts/main.php',
    'src/Views/home.php',
    'src/Views/search.php'
];

foreach ($views as $view) {
    $path = __DIR__ . '/../' . $view;
    if (file_exists($path)) {
        echo "✅ $view existe\n";
    } else {
        echo "❌ $view NO existe\n";
    }
}

// Verificar Front Controller
echo "\n🚀 Verificando Front Controller...\n";
$frontController = __DIR__ . '/../public/index.php';
if (file_exists($frontController)) {
    echo "✅ public/index.php existe\n";
} else {
    echo "❌ public/index.php NO existe\n";
}

// Verificar .htaccess
echo "\n🔧 Verificando .htaccess...\n";
$htaccess = __DIR__ . '/../.htaccess';
if (file_exists($htaccess)) {
    echo "✅ .htaccess existe\n";
} else {
    echo "❌ .htaccess NO existe\n";
}

// Verificar archivos antiguos renombrados
echo "\n📁 Verificando archivos antiguos...\n";
$oldFiles = [
    'index_old.php',
    'data_old.php',
    'question_old.php'
];

foreach ($oldFiles as $oldFile) {
    $path = __DIR__ . '/../' . $oldFile;
    if (file_exists($path)) {
        echo "✅ $oldFile renombrado correctamente\n";
    } else {
        echo "⚠️  $oldFile no encontrado (puede haber sido eliminado)\n";
    }
}

echo "\n=== Verificación Completa ===\n";
echo "\n📚 Documentación disponible:\n";
echo "   - README.md: Guía principal\n";
echo "   - ARCHITECTURE.md: Diagrama de arquitectura\n";
echo "   - OLD_FILES.md: Lista de archivos antiguos\n";

echo "\n🚀 Para iniciar el proyecto:\n";
echo "   docker-compose up -d\n";
echo "   Luego visita: http://localhost:8000/\n";
