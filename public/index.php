<?php
/**
 * Front Controller - Punto de entrada único de la aplicación
 * 
 * Este archivo maneja todas las peticiones HTTP y las enruta
 * a los controladores apropiados usando el patrón MVC.
 * 
 * @package App
 * @author Sergio RP Leon
 */

// Iniciar sesión ANTES de cualquier output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar el autoloader PSR-4 de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

// Validar entorno
try {
    \App\Services\Validation\EnvironmentValidator::validate();
} catch (\App\Exceptions\EnvironmentException $e) {
    die("Error de Configuración: " . $e->getMessage());
}

// Importar clases necesarias
use App\Controllers\HomeController;
use App\Controllers\SearchController;
use App\Controllers\DataController;
use App\Controllers\AuthController;
use App\Controllers\ErrorController;
use App\Controllers\InformController;

// Obtener la URI solicitada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Manejo de errores global
try {
    // Router simple basado en la URI
    switch ($uri) {
        case '/':
            $controller = new HomeController();
            $controller->index();
            break;

        case '/search':
            $controller = new SearchController();
            $controller->index();
            break;

        case '/data':
            // Requiere autenticación
            $controller = new DataController();
            $controller->import();
            break;

        case '/inform':
            $controller = new InformController();
            $controller->index();
            break;

        case '/login':
            $controller = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->login();
            } else {
                $controller->showLogin();
            }
            break;

        case '/logout':
            $controller = new AuthController();
            $controller->logout();
            break;

        default:
            // Página 404 usando el ErrorController
            $errorController = new ErrorController();
            $errorController->notFound();
            break;
    }
} catch (\Exception $e) {
    // Manejo de errores del servidor usando el ErrorController
    $errorController = new ErrorController();
    $errorController->serverError($e);
}
