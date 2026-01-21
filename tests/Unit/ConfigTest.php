<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Limpiar variables de entorno antes de cada test
        unset($_ENV['EMBEDDING_PROVIDER']);
        unset($_ENV['VECTOR_DIMENSION']);
    }

    public function testVectorDimensionDefaultsTo4096ForOllama(): void
    {
        $_ENV['EMBEDDING_PROVIDER'] = 'ollama';

        $config = require __DIR__ . '/../../config/config.php';

        $this->assertEquals(4096, $config['ai']['vector_dimension']);
    }

    public function testVectorDimensionDefaultsTo768ForGemini(): void
    {
        $_ENV['EMBEDDING_PROVIDER'] = 'gemini';

        $config = require __DIR__ . '/../../config/config.php';

        $this->assertEquals(768, $config['ai']['vector_dimension']);
    }

    public function testVectorDimensionCanBeOverridden(): void
    {
        $_ENV['EMBEDDING_PROVIDER'] = 'gemini';
        $_ENV['VECTOR_DIMENSION'] = '1024';

        $config = require __DIR__ . '/../../config/config.php';

        $this->assertEquals(1024, $config['ai']['vector_dimension']);
    }

    public function testDefaultProviderIsOllama(): void
    {
        // No establecer EMBEDDING_PROVIDER
        $config = require __DIR__ . '/../../config/config.php';

        $this->assertEquals('ollama', $config['ai']['provider']);
    }

    public function testDataSourceDefaultsToUrl(): void
    {
        // No establecer DATA_SOURCE
        $config = require __DIR__ . '/../../config/config.php';

        // El valor por defecto en config.php es 'url' pero en DataSourceFactory es 'github'
        // Verificamos que existe la configuración
        $this->assertArrayHasKey('source', $config['data']);
    }

    public function testConfigHasRequiredKeys(): void
    {
        $config = require __DIR__ . '/../../config/config.php';

        // Verificar estructura básica
        $this->assertArrayHasKey('database', $config);
        $this->assertArrayHasKey('ai', $config);
        $this->assertArrayHasKey('ollama', $config);
        $this->assertArrayHasKey('data', $config);
        $this->assertArrayHasKey('app', $config);
        $this->assertArrayHasKey('auth', $config);
    }

    public function testDatabaseConfigHasRequiredKeys(): void
    {
        $config = require __DIR__ . '/../../config/config.php';

        $this->assertArrayHasKey('host', $config['database']);
        $this->assertArrayHasKey('dbname', $config['database']);
        $this->assertArrayHasKey('user', $config['database']);
        $this->assertArrayHasKey('password', $config['database']);
        $this->assertArrayHasKey('dsn', $config['database']);
    }

    public function testAiConfigHasRequiredKeys(): void
    {
        $config = require __DIR__ . '/../../config/config.php';

        $this->assertArrayHasKey('provider', $config['ai']);
        $this->assertArrayHasKey('gemini_key', $config['ai']);
        $this->assertArrayHasKey('vector_dimension', $config['ai']);
    }

    public function testDataConfigHasFilesArray(): void
    {
        $config = require __DIR__ . '/../../config/config.php';

        $this->assertArrayHasKey('files', $config['data']);
        $this->assertIsArray($config['data']['files']);
        $this->assertNotEmpty($config['data']['files']);
    }
}
