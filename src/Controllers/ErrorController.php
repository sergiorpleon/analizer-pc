<?php

declare(strict_types=1);

namespace App\Controllers;

class ErrorController
{
    /**
     * Maneja errores 404 (página no encontrada)
     */
    public function notFound(): void
    {
        http_response_code(404);

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $availableRoutes = ['/', '/search', '/login', '/data'];

        require __DIR__ . '/../Views/errors/404.php';
    }

    /**
     * Maneja errores 500 (error del servidor)
     */
    public function serverError(\Exception $exception): void
    {
        http_response_code(500);

        // En producción, no mostrar detalles del error
        $isProduction = ($_ENV['APP_ENV'] ?? 'production') === 'production';

        // Log del error
        error_log(sprintf(
            "[%s] Error: %s in %s:%d\nStack trace:\n%s",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        ));

        require __DIR__ . '/../Views/errors/500.php';
    }
}
