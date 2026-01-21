<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Models\Component;
use App\Services\Ai\EmbeddingServiceInterface;

/**
 * Tests de integración para el modelo de Películas
 * Requiere una base de datos de prueba configurada
 */
class ComponentTest extends TestCase
{
    private Component $component;

    protected function setUp(): void
    {
        parent::setUp();

        // Cargar variables de entorno para tests
        if (file_exists(__DIR__ . '/../../.env')) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        $this->component = new Component();
    }

    public function testComponentCanBeInstantiated(): void
    {
        $this->assertInstanceOf(Component::class, $this->component);
    }

    public function testInsertMethodAcceptsCorrectParameters(): void
    {
        // Este test verifica que el método insert acepta los parámetros correctos
        // sin ejecutarlo realmente (para evitar modificar la BD en tests)

        $reflection = new \ReflectionClass(Component::class);
        $method = $reflection->getMethod('insert');

        $parameters = $method->getParameters();

        $this->assertCount(4, $parameters);
        $this->assertEquals('categoria', $parameters[0]->getName());
        $this->assertEquals('nombre', $parameters[1]->getName());
        $this->assertEquals('detalles', $parameters[2]->getName());
        $this->assertEquals('embedding', $parameters[3]->getName());
    }

    public function testSearchSimilarMethodExists(): void
    {
        $this->assertTrue(
            method_exists($this->component, 'searchSimilar'),
            'Component debe tener el método searchSimilar'
        );
    }

    public function testGetAllMethodExists(): void
    {
        $this->assertTrue(
            method_exists($this->component, 'getAll'),
            'Component debe tener el método getAll'
        );
    }

    public function testCountMethodExists(): void
    {
        $this->assertTrue(
            method_exists($this->component, 'count'),
            'Component debe tener el método count'
        );
    }

    public function testFindByIdMethodExists(): void
    {
        $this->assertTrue(
            method_exists($this->component, 'findById'),
            'Component debe tener el método findById'
        );
    }

    public function testSearchSimilarAcceptsCorrectParameters(): void
    {
        $reflection = new \ReflectionClass(Component::class);
        $method = $reflection->getMethod('searchSimilar');

        $parameters = $method->getParameters();

        $this->assertCount(2, $parameters);
        $this->assertEquals('queryVector', $parameters[0]->getName());
        $this->assertEquals('limit', $parameters[1]->getName());
        $this->assertTrue($parameters[1]->isOptional());
    }
}
