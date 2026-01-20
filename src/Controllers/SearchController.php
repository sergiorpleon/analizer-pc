<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Component;
use App\Models\OllamaService;

class SearchController
{
    private Component $componentModel;
    private OllamaService $ollamaService;

    public function __construct()
    {
        $this->componentModel = new Component();
        $this->ollamaService = new OllamaService();
    }

    /**
     * Muestra el formulario de búsqueda y resultados
     */
    public function index(): void
    {
        $query = $_GET['q'] ?? '';
        $results = [];
        $error = null;
        $noData = false;

        if (!empty($query)) {
            try {
                // Generar embedding de la pregunta
                $queryVector = $this->ollamaService->generateEmbedding($query);

                // Buscar componentes similares
                $results = $this->componentModel->searchSimilar($queryVector);

                // Calcular similitud (1 - distancia)
                foreach ($results as &$result) {
                    $result['similarity'] = round(1 - $result['distancia'], 4);
                }
            } catch (\PDOException $e) {
                // Detectar si la tabla no existe
                $errorMsg = $e->getMessage();
                if (
                    strpos($errorMsg, 'relation "componentes_pc" does not exist') !== false ||
                    strpos($errorMsg, 'Undefined table') !== false ||
                    (strpos($errorMsg, 'componentes_pc') !== false && strpos($errorMsg, 'does not exist') !== false)
                ) {
                    $noData = true;
                } else {
                    $error = 'Error al buscar componentes: ' . $errorMsg;
                }
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();

                // Detectar si la tabla no existe (puede venir en cualquier tipo de excepción)
                if (
                    strpos($errorMsg, 'relation "componentes_pc" does not exist') !== false ||
                    strpos($errorMsg, 'Undefined table') !== false ||
                    (strpos($errorMsg, 'componentes_pc') !== false && strpos($errorMsg, 'does not exist') !== false)
                ) {
                    $noData = true;
                } else {
                    $error = $errorMsg;
                }
            }
        }

        // Cargar vista
        require __DIR__ . '/../Views/search.php';
    }
}
