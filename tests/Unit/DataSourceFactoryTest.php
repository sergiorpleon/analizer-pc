<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Data\DataSourceFactory;
use App\Services\Data\LocalDataSource;
use App\Services\Data\GitHubDataSource;

class DataSourceFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Limpiar variables de entorno antes de cada test
        unset($_ENV['DATA_SOURCE']);
        putenv('DATA_SOURCE');
    }

    public function testCreatesLocalDataSourceWhenSourceIsLocal(): void
    {
        $_ENV['DATA_SOURCE'] = 'local';

        $source = DataSourceFactory::create();

        $this->assertInstanceOf(LocalDataSource::class, $source);
    }

    public function testCreatesGitHubDataSourceWhenSourceIsGithub(): void
    {
        $_ENV['DATA_SOURCE'] = 'github';

        $source = DataSourceFactory::create();

        $this->assertInstanceOf(GitHubDataSource::class, $source);
    }

    public function testCreatesGitHubDataSourceByDefault(): void
    {
        // No establecer DATA_SOURCE
        $source = DataSourceFactory::create();

        $this->assertInstanceOf(GitHubDataSource::class, $source);
    }

    public function testThrowsExceptionForUnsupportedSource(): void
    {
        $_ENV['DATA_SOURCE'] = 'unsupported-source';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Origen de datos no soportado');

        DataSourceFactory::create();
    }

    public function testUsesEnvVariableOverGetenv(): void
    {
        // Establecer en $_ENV pero no en getenv
        $_ENV['DATA_SOURCE'] = 'local';

        $source = DataSourceFactory::create();

        $this->assertInstanceOf(LocalDataSource::class, $source);
    }
}
