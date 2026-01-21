<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Data\DataImporter;

/**
 * Tests simplificados para DataImporter
 * Se enfocan en verificar la estructura y métodos disponibles
 */
class DataImporterTest extends TestCase
{
    public function testDataImporterCanBeInstantiated(): void
    {
        $componentMock = $this->createMock(\App\Models\Component::class);
        $embeddingMock = $this->createMock(\App\Services\Ai\EmbeddingServiceInterface::class);
        $fileLoaderMock = $this->createMock(\App\Services\Data\FileLoader::class);

        $importer = new DataImporter($componentMock, $embeddingMock, $fileLoaderMock);

        $this->assertInstanceOf(DataImporter::class, $importer);
    }

    public function testImportFromContentMethodExists(): void
    {
        $componentMock = $this->createMock(\App\Models\Component::class);
        $embeddingMock = $this->createMock(\App\Services\Ai\EmbeddingServiceInterface::class);
        $fileLoaderMock = $this->createMock(\App\Services\Data\FileLoader::class);

        $importer = new DataImporter($componentMock, $embeddingMock, $fileLoaderMock);

        $this->assertTrue(
            method_exists($importer, 'importFromContent'),
            'DataImporter debe tener el método importFromContent'
        );
    }

    public function testImportFromCsvMethodExists(): void
    {
        $componentMock = $this->createMock(\App\Models\Component::class);
        $embeddingMock = $this->createMock(\App\Services\Ai\EmbeddingServiceInterface::class);
        $fileLoaderMock = $this->createMock(\App\Services\Data\FileLoader::class);

        $importer = new DataImporter($componentMock, $embeddingMock, $fileLoaderMock);

        $this->assertTrue(
            method_exists($importer, 'importFromCsv'),
            'DataImporter debe tener el método importFromCsv'
        );
    }

    public function testProcessRowDirectlyMethodExists(): void
    {
        $componentMock = $this->createMock(\App\Models\Component::class);
        $embeddingMock = $this->createMock(\App\Services\Ai\EmbeddingServiceInterface::class);
        $fileLoaderMock = $this->createMock(\App\Services\Data\FileLoader::class);

        $importer = new DataImporter($componentMock, $embeddingMock, $fileLoaderMock);

        $this->assertTrue(
            method_exists($importer, 'processRowDirectly'),
            'DataImporter debe tener el método processRowDirectly'
        );
    }

    public function testImportFromContentAcceptsCorrectParameters(): void
    {
        $reflection = new \ReflectionClass(DataImporter::class);
        $method = $reflection->getMethod('importFromContent');

        $parameters = $method->getParameters();

        $this->assertCount(3, $parameters);
        $this->assertEquals('content', $parameters[0]->getName());
        $this->assertEquals('filename', $parameters[1]->getName());
        $this->assertEquals('limit', $parameters[2]->getName());
    }

    public function testProcessRowDirectlyAcceptsCorrectParameters(): void
    {
        $reflection = new \ReflectionClass(DataImporter::class);
        $method = $reflection->getMethod('processRowDirectly');

        $parameters = $method->getParameters();

        $this->assertCount(3, $parameters);
        $this->assertEquals('row', $parameters[0]->getName());
        $this->assertEquals('headers', $parameters[1]->getName());
        $this->assertEquals('filename', $parameters[2]->getName());
    }
}
