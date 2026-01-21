<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Database;

class DatabaseTest extends TestCase
{
    public function testGetInstanceReturnsSingleton(): void
    {
        $instance1 = Database::getInstance();
        $instance2 = Database::getInstance();

        $this->assertSame($instance1, $instance2, 'Database should return the same instance');
    }

    public function testGetInstanceReturnsDatabase(): void
    {
        $instance = Database::getInstance();

        $this->assertInstanceOf(Database::class, $instance);
    }

    public function testGetPdoReturnsValidPdoInstance(): void
    {
        $instance = Database::getInstance();
        $pdo = $instance->getPdo();

        $this->assertInstanceOf(\PDO::class, $pdo);
    }

    public function testInitializeTableMethodExists(): void
    {
        $instance = Database::getInstance();

        $this->assertTrue(
            method_exists($instance, 'initializeTable'),
            'Database debe tener el método initializeTable'
        );
    }

    public function testTestConnectionMethodExists(): void
    {
        $instance = Database::getInstance();

        $this->assertTrue(
            method_exists($instance, 'testConnection'),
            'Database debe tener el método testConnection'
        );
    }

    public function testTestConnectionReturnsBoolean(): void
    {
        $instance = Database::getInstance();

        $reflection = new \ReflectionClass(Database::class);
        $method = $reflection->getMethod('testConnection');

        $returnType = $method->getReturnType();
        $this->assertNotNull($returnType);
        $this->assertEquals('bool', (string) $returnType);
    }
}
