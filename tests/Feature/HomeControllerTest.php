<?php

namespace App\Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Controllers\HomeController;

class HomeControllerTest extends TestCase
{
    public function testIndexMethodExists()
    {
        $controller = new HomeController();

        $this->assertTrue(method_exists($controller, 'index'));
    }
}
