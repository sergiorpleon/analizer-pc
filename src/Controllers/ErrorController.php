<?php
// src/Controllers/ErrorController.php

namespace App\Controllers;

class ErrorController
{
    /**
     * Muestra la página 404
     */
    public function notFound()
    {
        http_response_code(404);

        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Cargar vista
        require __DIR__ . '/../Views/errors/404.php';
    }

    /**
     * Muestra la página de error 500
     */
    public function serverError(\Exception $exception)
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

        // Cargar vista
        require __DIR__ . '/../Views/errors/500.php';
    }
}
