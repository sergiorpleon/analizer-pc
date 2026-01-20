<?php
// src/Controllers/SearchController.php

namespace App\Controllers;

use App\Models\Component;
use App\Models\OllamaService;

class SearchController
{
    private $componentModel;
    private $ollamaService;

    public function __construct()
    {
        $this->componentModel = new Component();
        $this->ollamaService = new OllamaService();
    }

    /**
     * Muestra el formulario de búsqueda y resultados
     */
    public function index()
    {
        $query = $_GET['q'] ?? '';
        $results = [];
        $error = null;

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
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        // Cargar vista
        require __DIR__ . '/../Views/search.php';
    }
}
