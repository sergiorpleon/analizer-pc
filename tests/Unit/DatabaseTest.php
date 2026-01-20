<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Database;

class DatabaseTest extends TestCase
{
    public function testGetInstanceReturnsSingleton()
    {
        $instance1 = Database::getInstance();
        $instance2 = Database::getInstance();

        $this->assertSame($instance1, $instance2, 'Database should return the same instance');
    }

    public function testGetInstanceReturnsDatabase()
    {
        $instance = Database::getInstance();

        $this->assertInstanceOf(Database::class, $instance);
    }
}
