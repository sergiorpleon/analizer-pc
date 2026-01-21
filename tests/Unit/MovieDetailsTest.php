<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Controllers\SearchController;

class MovieDetailsTest extends TestCase
{
    public function testParseDetailsExtractsInformationCorrectly()
    {
        $controller = new SearchController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('parseDetails');
        $method->setAccessible(true);

        $rawDetails = "Película: imdb_top_1000.csv. Poster_Link: http://example.com/img.jpg. Series_Title: The Godfather. Released_Year: 1972. Certificate: A. Runtime: 175 min. Genre: Crime, Drama. IMDB_Rating: 9.2. Overview: An organized crime dynasty's aging patriarch transfers control of his clandestine empire to his reluctant son. Meta_score: 100. Director: Francis Ford Coppola. Star1: Marlon Brando. Star2: Al Pacino. Star3: James Caan. Star4: Diane Keaton. No_of_Votes: 1620367. Gross: 134,966,411.";

        $result = $method->invoke($controller, $rawDetails);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('Poster_Link', $result);
        $this->assertEquals('http://example.com/img.jpg', $result['Poster_Link']);

        $this->assertArrayHasKey('Series_Title', $result);
        $this->assertEquals('The Godfather', $result['Series_Title']);

        $this->assertArrayHasKey('Released_Year', $result);
        $this->assertEquals('1972', $result['Released_Year']);

        $this->assertArrayHasKey('Director', $result);
        $this->assertEquals('Francis Ford Coppola', $result['Director']);

        $this->assertArrayHasKey('Overview', $result);
        $this->assertStringContainsString('aging patriarch', $result['Overview']);
    }

    public function testParseDetailsHandlesMissingPrefix()
    {
        $controller = new SearchController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('parseDetails');
        $method->setAccessible(true);

        $rawDetails = "Series_Title: The Dark Knight. Released_Year: 2008.";

        $result = $method->invoke($controller, $rawDetails);

        $this->assertArrayHasKey('Series_Title', $result);
        $this->assertEquals('The Dark Knight', $result['Series_Title']);
    }
}
