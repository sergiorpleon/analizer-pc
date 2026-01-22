<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Component;
use App\Services\Ai\EmbeddingServiceInterface;
use App\Services\Ai\EmbeddingFactory;
use App\Enums\SessionKey;

class SearchController
{
    private Component $componentModel;
    private EmbeddingServiceInterface $embeddingService;

    public function __construct()
    {
        $this->componentModel = new Component();
        $this->embeddingService = EmbeddingFactory::create();
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
                // Generar embedding usando la abstracción
                $queryVector = $this->embeddingService->getEmbedding($query);

                // Buscar películas similares
                $results = $this->componentModel->searchSimilar($queryVector);

                // Calcular similitud (1 - distancia)
                foreach ($results as $key => $result) {
                    $results[$key]['similarity'] = round(1 - $result['distancia'], 4);
                    $results[$key]['parsed_details'] = $this->parseDetails($result['detalles']);
                }

                // Guardar resultados en sesión para exportar
                $_SESSION[SessionKey::SEARCH_RESULTS] = $results;
                $_SESSION[SessionKey::SEARCH_QUERY] = $query;

            } catch (\PDOException $e) {
                $this->handleDatabaseError($e, $error, $noData);
            } catch (\Exception $e) {
                $this->handleGeneralError($e, $error, $noData);
            }
        }

        require __DIR__ . '/../Views/search.php';
    }

    /**
     * Muestra los detalles de una película específica
     */
    public function show(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            header('Location: /search');
            exit;
        }

        try {
            $movie = $this->componentModel->findById($id);

            if (!$movie) {
                header('Location: /search');
                exit;
            }

            // Parsear los detalles para mostrarlos formateados
            $movie['info_formateada'] = $this->parseDetails($movie['detalles']);

            require __DIR__ . '/../Views/details.php';
        } catch (\Exception $e) {
            $error = $e->getMessage();
            require __DIR__ . '/../Views/search.php';
        }
    }

    /**
     * Parsea la cadena de detalles en un array asociativo
     */
    private function parseDetails(string $detalles): array
    {
        $info = [];
        // El formato es "Película: filename. Key: Value. Key: Value. "
        // Buscamos la primera ocurrencia de "Key: " para saber dónde empiezan los datos reales
        if (preg_match('/^Película: .*?\. ([A-Z][a-zA-Z0-9_]+: .*)/', $detalles, $matches)) {
            $cleanDetails = $matches[1];
        } else {
            $cleanDetails = $detalles;
        }

        // Dividimos por ". " asegurándonos de que lo que sigue parece una llave (Key: )
        $parts = preg_split('/\. (?=[A-Z][a-zA-Z0-9_]+: )/', $cleanDetails);

        foreach ($parts as $part) {
            if (strpos($part, ': ') !== false) {
                list($key, $value) = explode(': ', $part, 2);
                $key = trim($key);
                $value = rtrim(trim($value), '.');
                if (!empty($key) && !empty($value)) {
                    $info[$key] = $value;
                }
            }
        }

        return $info;
    }

    private function handleDatabaseError(\PDOException $e, ?string &$error, bool &$noData): void
    {
        $errorMsg = $e->getMessage();
        if ($this->isTableMissingError($errorMsg)) {
            $noData = true;
        } else {
            $error = 'Error al buscar películas: ' . $errorMsg;
        }
    }

    private function handleGeneralError(\Exception $e, ?string &$error, bool &$noData): void
    {
        $errorMsg = $e->getMessage();
        if ($this->isTableMissingError($errorMsg)) {
            $noData = true;
        } else {
            $error = $errorMsg;
        }
    }

    private function isTableMissingError(string $errorMsg): bool
    {
        return strpos($errorMsg, 'relation "componentes_pc" does not exist') !== false ||
            strpos($errorMsg, 'Undefined table') !== false ||
            (strpos($errorMsg, 'componentes_pc') !== false && strpos($errorMsg, 'does not exist') !== false);
    }
}
