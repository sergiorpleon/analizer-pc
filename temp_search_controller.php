<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Component;
use App\Services\Ai\AiServiceInterface;
use App\Services\Ai\OllamaService;
use App\Enums\SessionKey;

class SearchController
{
    private Component $componentModel;
    private AiServiceInterface $aiService;

    public function __construct()
    {
        $this->componentModel = new Component();
        $this->aiService = new OllamaService();
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
                $queryVector = $this->aiService->generateEmbedding($query);

                // Buscar componentes similares
                $results = $this->componentModel->searchSimilar($queryVector);

                // Calcular similitud (1 - distancia)
                foreach ($results as &$result) {
                    $result['similarity'] = round(1 - $result['distancia'], 4);
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

    private function handleDatabaseError(\PDOException $e, ?string &$error, bool &$noData): void
    {
        $errorMsg = $e->getMessage();
        if ($this->isTableMissingError($errorMsg)) {
            $noData = true;
        } else {
            $error = 'Error al buscar componentes: ' . $errorMsg;
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
