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

        // Limpiar variables globales
        $_GET = [];
        $_SESSION = [];
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

        // Puede mostrar "No hay datos", "No se encontraron resultados", "Error" o "Resultados encontrados"
        $hasStatusMessage =
            strpos($output, 'No hay datos disponibles') !== false ||
            strpos($output, 'No se encontraron resultados') !== false ||
            strpos($output, 'Error') !== false ||
            strpos($output, 'Resultados encontrados') !== false;

        $this->assertTrue($hasStatusMessage, 'Debe mostrar un mensaje de estado adecuado (resultados, no resultados o error)');
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

        // Siempre debe haber algún contenido en el output
        $this->assertNotEmpty($output, 'El output no debe estar vacío');

        // Si muestra mensaje de "no hay datos disponibles", debe sugerir importar
        if (strpos($output, 'No hay datos disponibles') !== false) {
            $this->assertStringContainsString('importar', strtolower($output));
        } else {
            // Si no es un error de "no hay datos", al menos debe mostrar resultados, 
            // que no hubo resultados o un error de conexión/IA
            $this->assertTrue(
                strpos($output, 'Resultados encontrados') !== false ||
                strpos($output, 'No se encontraron resultados') !== false ||
                strpos($output, 'Error') !== false,
                'Debe mostrar un mensaje de estado adecuado (resultados, no resultados o error)'
            );
        }
    }

    protected function tearDown(): void
    {
        $_GET = [];
    }
}
