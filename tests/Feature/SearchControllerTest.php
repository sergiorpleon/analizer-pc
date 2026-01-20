<?php

namespace App\Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Controllers\SearchController;

class SearchControllerTest extends TestCase
{
    private $searchController;

    protected function setUp(): void
    {
        $this->searchController = new SearchController();

        // Limpiar variables GET
        $_GET = [];
    }

    public function testIndexMethodExists()
    {
        $this->assertTrue(
            method_exists($this->searchController, 'index'),
            'SearchController should have index method'
        );
    }

    public function testIndexWithoutQuery()
    {
        // Capturar output
        ob_start();
        $this->searchController->index();
        $output = ob_get_clean();

        // Debe mostrar el formulario de búsqueda
        $this->assertStringContainsString('Buscador de Componentes', $output);
        $this->assertStringContainsString('form', $output);
    }

    public function testIndexWithEmptyQuery()
    {
        $_GET['q'] = '';

        ob_start();
        $this->searchController->index();
        $output = ob_get_clean();

        // Debe mostrar el formulario sin resultados
        $this->assertStringContainsString('Buscador de Componentes', $output);
    }

    public function testIndexWithQueryButNoData()
    {
        $_GET['q'] = 'procesador gaming';

        ob_start();
        $this->searchController->index();
        $output = ob_get_clean();

        // Debe mostrar mensaje de no hay datos o error manejado
        $this->assertStringContainsString('Buscador de Componentes', $output);

        // Puede mostrar "No hay datos" o "No se encontraron resultados"
        $hasNoDataMessage =
            strpos($output, 'No hay datos disponibles') !== false ||
            strpos($output, 'No se encontraron resultados') !== false ||
            strpos($output, 'Error') !== false;

        $this->assertTrue($hasNoDataMessage, 'Should show appropriate message when no data');
    }

    public function testSearchFormHasCorrectAction()
    {
        ob_start();
        $this->searchController->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('action="/search"', $output);
        $this->assertStringContainsString('method="GET"', $output);
    }

    public function testSearchFormHasQueryInput()
    {
        ob_start();
        $this->searchController->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('name="q"', $output);
        $this->assertStringContainsString('type="text"', $output);
    }

    public function testSearchFormHasSubmitButton()
    {
        ob_start();
        $this->searchController->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('type="submit"', $output);
        $this->assertStringContainsString('Buscar', $output);
    }

    public function testQueryValueIsEscaped()
    {
        $_GET['q'] = '<script>alert("xss")</script>';

        ob_start();
        $this->searchController->index();
        $output = ob_get_clean();

        // El script no debe aparecer sin escapar
        $this->assertStringNotContainsString('<script>alert("xss")</script>', $output);

        // Debe estar escapado
        $this->assertStringContainsString('&lt;script&gt;', $output);
    }

    public function testNoDataMessageHasHelpfulInformation()
    {
        $_GET['q'] = 'test query';

        ob_start();
        $this->searchController->index();
        $output = ob_get_clean();

        // Si muestra mensaje de no datos, debe tener información útil
        if (strpos($output, 'No hay datos disponibles') !== false) {
            $this->assertStringContainsString('importar', strtolower($output));
        }
    }

    protected function tearDown(): void
    {
        $_GET = [];
    }
}
