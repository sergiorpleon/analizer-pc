<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Ai\EmbeddingFactory;
use App\Services\Ai\GeminiService;
use App\Services\Ai\OllamaService;

class EmbeddingFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Limpiar variables de entorno antes de cada test
        unset($_ENV['EMBEDDING_PROVIDER']);
        unset($_ENV['GEMINI_API_KEY']);
    }

    public function testCreatesGeminiServiceWhenProviderIsGemini(): void
    {
        $_ENV['EMBEDDING_PROVIDER'] = 'gemini';
        $_ENV['GEMINI_API_KEY'] = 'test-key-12345';

        $service = EmbeddingFactory::create();

        $this->assertInstanceOf(GeminiService::class, $service);
    }

    public function testCreatesOllamaServiceWhenProviderIsOllama(): void
    {
        $_ENV['EMBEDDING_PROVIDER'] = 'ollama';

        $service = EmbeddingFactory::create();

        $this->assertInstanceOf(OllamaService::class, $service);
    }

    public function testCreatesOllamaServiceByDefault(): void
    {
        // No establecer EMBEDDING_PROVIDER
        $service = EmbeddingFactory::create();

        $this->assertInstanceOf(OllamaService::class, $service);
    }

    public function testThrowsExceptionForUnsupportedProvider(): void
    {
        $_ENV['EMBEDDING_PROVIDER'] = 'unsupported-provider';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Proveedor de embeddings no soportado');

        EmbeddingFactory::create();
    }

    public function testUsesEnvVariableOverGetenv(): void
    {
        // Establecer en $_ENV pero no en getenv
        $_ENV['EMBEDDING_PROVIDER'] = 'gemini';
        $_ENV['GEMINI_API_KEY'] = 'test-key';

        $service = EmbeddingFactory::create();

        $this->assertInstanceOf(GeminiService::class, $service);
    }
}
