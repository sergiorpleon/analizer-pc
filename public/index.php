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

// Cargar el autoloader PSR-4 de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Importar clases necesarias
use App\Controllers\HomeController;
use App\Controllers\SearchController;
use App\Controllers\DataController;
use App\Controllers\ErrorController;

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
            $controller = new DataController();
            $controller->import();
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
